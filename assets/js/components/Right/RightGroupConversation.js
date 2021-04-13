import React from "react";
import { connect } from "react-redux";
import * as actionCreators from "../../actions/conversation";

import GroupMessageInput from "./GroupMessageInput";
import GroupMessage from "./GroupMessage";
import { postGroupMessage } from "../../actions/conversation";

const mapStateToProps = (state) => {
  return state;
};

class RightGroupConversation extends React.Component {
  constructor(props) {
    super(props);
    this.bodyRef = React.createRef();
    this.state = {
      id: null,
      _groupConversationIndex: -1,
      eventSource: null,
    };
  }

  // scroll down to the latest groupMessage
  scrollDown() {
    this.bodyRef.current.scrollTop = this.bodyRef.current.scrollHeight;
  }

  componentDidUpdate(prevProps) {
    if (
      this.state._groupConversationIndex != -1 &&
      this.props.groupConversations[this.state._groupConversationIndex]
        .groupMessages?.length &&
      prevProps.groupConversations[this.state._groupConversationIndex]
        .groupMessages?.length
    ) {
      this.scrollDown();
    }
  }

  componentDidMount() {
    const _t = this;
    const id = this.props.match.params.id;
    const _groupConversationIndex = this.props.groupConversations.findIndex(
      (groupConversation) => {
        return groupConversation.id == this.props.match.params.id;
      }
    );
    this.setState({
      _groupConversationIndex: _groupConversationIndex,
    });
    if (
      this.props.groupConversations[_groupConversationIndex].groupMessages ==
      undefined
    ) {
      this.props.fetchGroupMessages(id).then(() => {
        this.scrollDown();
        if (this.state.eventSource === null) {
          let url = new URL(this.props.hubUrl);
          url.searchParams.append(
            "topic",
            `/groupConversation/${this.props.match.params.id}`
          );
          this.eventSource = new EventSource(url, {
            withCredentials: true,
          });
          this.eventSource.onmessage = function (event) {
            const data = JSON.parse(event.data);
            // debugger
            _t.props.postGroupMessage(data, data.groupConversation.id);
          };
        }
      });
    } else {
      this.scrollDown();
    }
  }

  componentWillUnmount() {
    if (this.state.eventSource instanceof EventSource) {
      this.state.eventSource.close();
      this.setState({
        eventSource: null,
      });
    }
  }

  render() {
    return (
      <div className="col-7 g-0 main-box">
        <div className="px-4 bg-white d-flex flex-column">
          <div className="overflow-scroll grow-1 scroll-box" ref={this.bodyRef}>
            {this.state._groupConversationIndex != -1 &&
            this.props.groupConversations != undefined &&
            this.props.groupConversations[this.state._groupConversationIndex]
              .groupMessages != undefined
              ? this.props.groupConversations[
                  this.state._groupConversationIndex
                ].groupMessages.map((groupMessage, index) => {
                  return <GroupMessage meessage={groupMessage} key={index} />;
                })
              : ""}
          </div>
        </div>
        <div className="row g-0">
          <GroupMessageInput id={this.props.match.params.id} />
        </div>
      </div>
    );
  }
}

export default connect(mapStateToProps, actionCreators)(RightGroupConversation);

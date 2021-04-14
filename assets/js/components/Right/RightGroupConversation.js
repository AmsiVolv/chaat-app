import React from "react";
import { connect } from "react-redux";
import * as actionCreators from "../../actions/conversation";

import GroupMessageInput from "./GroupMessageInput";
import GroupMessage from "./GroupMessage";
import RightGroupConversationMessageHelper from "./RightGroupConversationMessageHelper";
import RightMessageHelper from "./RightMessageHelper";

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
        return groupConversation.id == id;
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
          url.searchParams.append("topic", `/groupConversations/${id}`);
          this.eventSource = new EventSource(url, {
            withCredentials: true,
          });

          this.eventSource.onmessage = function (event) {
            const data = JSON.parse(event.data);

            _t.props.postGroupMessage(data, data.groupId);
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
    const nonEmptyGroupMessages =
      this.state._groupConversationIndex != -1 &&
      this.props.groupConversations != undefined &&
      this.props.groupConversations[this.state._groupConversationIndex]
        .groupMessages != undefined;

    const groupMessages = nonEmptyGroupMessages
      ? this.props.groupConversations[this.state._groupConversationIndex]
          .groupMessages
      : [];

    return (
      <div className="col-7 g-0 main-box">
        <div className="px-4 bg-white d-flex flex-column">
          <div className="row sticky-top">
            <RightGroupConversationMessageHelper
              history={this.props.history}
              group={
                this.props.groupConversations[
                  this.state._groupConversationIndex
                ]
              }
            />
          </div>
          <div className="overflow-scroll grow-1 scroll-box" ref={this.bodyRef}>
            {nonEmptyGroupMessages
              ? groupMessages.map((groupMessage, index) => {
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

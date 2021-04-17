import React from "react";
import Conversation from "./Conversation";
import GroupConversation from "./GroupConversation";
import { connect } from "react-redux";
import * as actionCreators from "../../actions/conversation";
import Search from "./Search";
import GroupSearch from "./GroupSearch";
import { Switch } from "antd";

const mapStateToProps = (state) => {
  return state;
};

class Left extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      showPrivateMessage: true,
      fetchGroupMessage: true,
      selectedConversationId: "",
    };

    this.handleSwitch = this.handleSwitch.bind(this);
    this.handleConversationSelect = this.handleConversationSelect.bind(this);
  }

  componentDidUpdate(prevProps) {
    if (!this.state.showPrivateMessage && this.state.fetchGroupMessage) {
      this.setState({ fetchGroupMessage: false });

      const _t = this;
      this.props.fetchGroupConversations().then(() => {
        let url = new URL(this.props.hubUrl);
        url.searchParams.append(
          "topic",
          `/groupConversations/${this.props.username}`
        );
        const eventSource = new EventSource(url, {
          withCredentials: true,
        });
        eventSource.onmessage = function (event) {
          const data = JSON.parse(event.data);

          _t.props.setLastGroupMessage(data, data.groupId);
        };
      });
    }
  }

  componentDidMount() {
    const _t = this;
    this.props.fetchConversations().then(() => {
      let url = new URL(this.props.hubUrl);
      url.searchParams.append("topic", `/conversations/${this.props.username}`);
      const eventSource = new EventSource(url, {
        withCredentials: true,
      });
      eventSource.onmessage = function (event) {
        // debugger
        const data = JSON.parse(event.data);
        _t.props.setLastMessage(data, data.conversation.id);
      };
    });
  }

  handleSwitch = () => {
    this.setState({ showPrivateMessage: !this.state.showPrivateMessage });
  };

  handleConversationSelect = (e) => {
    e.preventDefault();

    this.setState({
      selectedConversationId: parseInt(
        e.currentTarget.attributes.getNamedItem("data").value
      ),
    });
  };

  render() {
    return (
      <div className="col-5 g-0 main-box" id="left-side">
        <div className="bg-white" id="page-wrap">
          <div className="bg-gray px-4 py-2 bg-light">
            <p className="h5 mb-0 py-1 text-center">Recent</p>
          </div>
          <div className="messages-box">
            <div>
              {this.state.showPrivateMessage && <Search />}
              {!this.state.showPrivateMessage && <GroupSearch />}
            </div>
            <div className="d-flex justify-content-center pt-2 pb-2">
              <Switch
                onClick={this.handleSwitch}
                checkedChildren="Show group messages"
                unCheckedChildren="Show private messages"
                checked={this.state.showPrivateMessage}
              />
            </div>
            {this.state.showPrivateMessage && (
              <div className="list-group rounded-0">
                {this.props.items != undefined
                  ? this.props.items
                      .sort((a, b) => {
                        return a.createdAt < b.createdAt;
                      })
                      .map((conversation, index) => {
                        return (
                          <div
                            key={index}
                            onClick={this.handleConversationSelect}
                            data={conversation.conversationId}
                            // style={{
                            //   backgroundColor: conversation.conversationId === this.state.selectedConversationId ? '#69c0ff' : 'white',
                            // }}
                          >
                            <Conversation
                              conversation={conversation}
                              key={index}
                            />
                          </div>
                        );
                      })
                  : ""}
              </div>
            )}
            {!this.state.showPrivateMessage && (
              <div className="list-group rounded-0">
                {this.props.groupConversation != undefined
                  ? this.props.groupConversation
                      .sort((a, b) => {
                        return a.createdAt < b.createdAt;
                      })
                      .map((groupConversation, index) => {
                        return (
                          <GroupConversation
                            groupConversation={groupConversation}
                            key={index}
                          />
                        );
                      })
                  : ""}
              </div>
            )}
          </div>
        </div>
      </div>
    );
  }
}

export default connect(mapStateToProps, actionCreators)(Left);

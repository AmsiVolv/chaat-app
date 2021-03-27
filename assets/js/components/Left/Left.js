import React from "react";
import Conversation from "./Conversation";
import { connect } from "react-redux";
import * as actionCreators from "../../actions/conversation";
import Search from "./Search";

const mapStateToProps = (state) => {
  return state;
};

class Left extends React.Component {
  constructor(props) {
    super(props);
  }

  componentWillMount() {
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

  render() {
    return (
      <div className="col-5 px-0 h-100" id="left-side">
        <div className="bg-white" id="page-wrap">
          <div className="bg-gray px-4 py-2 bg-light">
            <p className="h5 mb-0 py-1  text-center">Recent</p>
          </div>
          <div className="messages-box">
            <div>
              <Search />
            </div>
            <div className="list-group rounded-0">
              {this.props.items != undefined
                ? this.props.items
                    .sort((a, b) => {
                      return a.createdAt < b.createdAt;
                    })
                    .map((conversation, index) => {
                      return (
                        <Conversation conversation={conversation} key={index} />
                      );
                    })
                : ""}
            </div>
          </div>
        </div>
      </div>
    );
  }
}

export default connect(mapStateToProps, actionCreators)(Left);

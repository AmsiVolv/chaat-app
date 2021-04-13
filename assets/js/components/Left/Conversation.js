import React from "react";
import { Link } from "react-router-dom";
import Avatar from "antd/es/avatar/avatar";

class Conversation extends React.Component {
  constructor(props) {
    super(props);

    this.getUserMedia = this.getUserMedia.bind(this);
  }

  getUserMedia(username, iconColor) {
    const capitalLetter = username.charAt(0).toUpperCase();

    return (
      <Avatar style={{ backgroundColor: "#" + iconColor }}>
        {capitalLetter}
      </Avatar>
    );
  }

  render() {
    return (
      <Link
        to={"/conversation/" + this.props.conversation.conversationId}
        className="list-group-item list-group-item-action rounded-0"
      >
        <div className="media">
          {this.getUserMedia(
            this.props.conversation.username,
            this.props.conversation.iconColor
          )}
          <div className="media-body ml-4">
            <div className="d-flex align-items-center justify-content-between mb-1">
              <h6 className="mb-0">{this.props.conversation.username}</h6>
              <small className="small font-weight-bold">
                {new Date(
                  this.props.conversation.createdAt
                ).toLocaleDateString()}
              </small>
            </div>
            <p className="font-italic mb-0 text-small">
              {this.props.conversation.content}
            </p>
          </div>
        </div>
      </Link>
    );
  }
}

export default Conversation;

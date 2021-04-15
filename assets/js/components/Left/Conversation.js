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
      <Avatar
        size={{ xs: 24, sm: 32, md: 40 }}
        style={{ backgroundColor: "#" + iconColor }}
      >
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
        <div className="d-inline-flex justify-content-between align-items-center w-100">
          <div>
            {this.getUserMedia(
              this.props.conversation.username,
              this.props.conversation.iconColor
            )}
          </div>
          <div className="p-2 flex-grow-1 d-inline-flex flex-column">
            <h6 className="mb-0">{this.props.conversation.username}</h6>
            <p className="font-italic mb-0 text-small">
              {this.props.conversation.content}
            </p>
          </div>
          <div className="p-2 flex-end align-self-end mt-auto">
            <small className="small font-weight-bold">
              {new Date(this.props.conversation.createdAt).toLocaleDateString()}
            </small>
          </div>
        </div>
      </Link>
    );
  }
}

export default Conversation;

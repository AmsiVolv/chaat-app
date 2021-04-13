import React from "react";
import { Link } from "react-router-dom";
import Avatar from "antd/es/avatar/avatar";

class GroupConversation extends React.Component {
  constructor(props) {
    super(props);

    this.getUserMedia = this.getUserMedia.bind(this);
  }

  getUserMedia(username, iconColor) {
    const capitalLetter = username.slice(1).toUpperCase();

    return (
      <Avatar size="large" style={{ backgroundColor: iconColor }}>
        {capitalLetter}
      </Avatar>
    );
  }

  render() {
    return (
      <Link
        to={"/groupConversation/" + this.props.groupConversation.id}
        className="list-group-item list-group-item-action rounded-0"
      >
        <div className="media">
          {this.getUserMedia(
            this.props.groupConversation.groupName,
            this.props.groupConversation.groupColor
          )}
          <div className="media-body ml-4">
            <div className="d-flex align-items-center justify-content-between mb-1">
              <h6 className="mb-0">{this.props.groupConversation.groupName}</h6>
              <small className="small font-weight-bold">
                {new Date(
                  this.props.groupConversation.createdAt
                ).toLocaleDateString()}
              </small>
            </div>
            <p className="font-italic mb-0 text-small">
              {this.props.groupConversation.content}
            </p>
          </div>
        </div>
      </Link>
    );
  }
}

export default GroupConversation;

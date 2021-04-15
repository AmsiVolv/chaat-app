import React from "react";
import store from "../../store";
import Avatar from "antd/es/avatar/avatar";

class Message extends React.Component {
  constructor(props) {
    super(props);

    this.getUserMedia = this.getUserMedia.bind(this);
  }

  componentDidMount() {}

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
    let img = ``;
    let mine =
      this.props.message.recipientUser.username === store.getState().username;

    if (mine) {
      img = this.getUserMedia(
        this.props.message.recipientUser.username,
        this.props.message.recipientUser.iconColor
      );
    }
    return (
      <div className={`media w-50 mb-3 ${!mine ? `ms-auto` : ``}`}>
        {img}
        <div className="media-body ml-3">
          <div
            className={`rounded py-2 px-3 mb-2 ${
              !mine ? "bg-primary" : "bg-light"
            }`}
          >
            <p
              className={`text-small mb-0 ${
                !mine ? "text-white" : "text-muted"
              }`}
            >
              {this.props.message.content}
            </p>
          </div>
          <p className="small text-muted">
            {new Date(this.props.message.createdAt).toLocaleString()}
          </p>
        </div>
      </div>
    );
  }
}

export default Message;

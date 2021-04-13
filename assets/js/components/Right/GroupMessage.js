import React from "react";
import store from "../../store";
import Avatar from "antd/es/avatar/avatar";

class GroupMessage extends React.Component {
  constructor(props) {
    super(props);

    this.getUserMedia = this.getUserMedia.bind(this);
  }

  componentDidMount() {}

  getUserMedia(username, iconColor) {
    const capitalLetter = username.charAt(0).toUpperCase();

    return (
      <Avatar style={{ backgroundColor: "#" + iconColor }}>
        {capitalLetter}
      </Avatar>
    );
  }

  render() {
    let img = ``;
    let mine = this.props.meessage.authorName !== store.getState().username;

    if (mine) {
      img = this.getUserMedia(
        this.props.meessage.authorName,
        this.props.meessage.iconColor
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
              {this.props.meessage.content}
            </p>
          </div>
          <p className="small text-muted">
            {new Date(this.props.meessage.createdAt).toLocaleString()}
          </p>
        </div>
      </div>
    );
  }
}

export default GroupMessage;

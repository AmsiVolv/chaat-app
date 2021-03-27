import React from "react";
import store from "../../store";

class Message extends React.Component {
  componentDidMount() {}

  render() {
    let img = ``;
    let mine =
      this.props.message.recipientUser.username === store.getState().username;

    if (mine) {
      img = (
        <img
          src="https://res.cloudinary.com/mhmd/image/upload/v1564960395/avatar_usae7z.svg"
          alt="user"
          width="50"
          className="rounded-circle"
        />
      );
    }
    return (
      <div className={`media w-50 mb-3 ${!mine ? `ml-auto` : ``}`}>
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

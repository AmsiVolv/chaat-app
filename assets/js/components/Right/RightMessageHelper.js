import React from "react";
import { connect } from "react-redux";
import * as actionCreators from "../../actions/conversation";
import { Button, message, PageHeader } from "antd";
import Avatar from "antd/es/avatar/avatar";

const mapStateToProps = (state) => {
  return state;
};

class RightMessageHelper extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      content: "",
    };

    this.clearConversation = this.clearConversation.bind(this);
    this.deleteConversation = this.deleteConversation.bind(this);
    this.getRecipientUsername = this.getRecipientUsername.bind(this);
  }

  clearConversation = () => {
    message.success("Jste úspěšně smazali všechny zprávy");
    this.props.clearConversation(this.props.conversationId);
    this.props.fetchConversations();
  };

  deleteConversation = () => {
    message.success("Chat byl odstraněn");
    this.props.deleteConversation(this.props.conversationId);
    this.props.history.push("/");
    this.props.fetchConversations();
  };

  getRecipientUsername = () => {
    if (typeof this.props.recepientUser !== "undefined") {
      return this.props.recepientUser.username;
    }
  };

  getRecipientAvatar = () => {
    if (typeof this.props.recepientUser !== "undefined") {
      const capitalLetter = this.props.recepientUser.username
        .charAt(0)
        .toUpperCase();

      return (
        <Avatar
          size={{ xs: 24, sm: 32, md: 40 }}
          style={{ backgroundColor: "#" + this.props.recepientUser.iconColor }}
        >
          {capitalLetter}
        </Avatar>
      );
    }
  };

  render() {
    return (
      <PageHeader
        ghost={false}
        onBack={() => this.props.history.push("/")}
        title={this.getRecipientAvatar()}
        subTitle={this.getRecipientUsername()}
        extra={[
          <Button key="2" onClick={this.deleteConversation}>
            Leave conversation
          </Button>,
          <Button key="1" onClick={this.clearConversation} type="primary">
            Clear conversation
          </Button>,
        ]}
      />
    );
  }
}

export default connect(mapStateToProps, actionCreators)(RightMessageHelper);

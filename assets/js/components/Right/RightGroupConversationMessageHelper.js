import React from "react";
import { connect } from "react-redux";
import * as actionCreators from "../../actions/conversation";
import { Button } from "antd";
import { CloseCircleOutlined } from "@ant-design/icons";
import { Nav, Navbar } from "react-bootstrap";
import Avatar from "antd/es/avatar/avatar";
import { message } from "antd";

const mapStateToProps = (state) => {
  return state;
};

class RightGroupConversationMessageHelper extends React.Component {
  constructor(props) {
    super(props);

    this.leaveConversation = this.leaveConversation.bind(this);
    this.renderGroupAvatar = this.renderGroupAvatar.bind(this);
  }

  leaveConversation = () => {
    message.success(
      `Jste úspěšně opustili skupinu ${this.props.group.groupName}`
    );
    this.props.leaveGroupConversation(this.props.group.id);
    this.props.history.push("/");
    this.props.fetchGroupConversations();
  };

  renderGroupAvatar = () => {
    if (typeof this.props.group !== "undefined") {
      return (
        <Avatar
          size={"large"}
          style={{ backgroundColor: this.props.group.groupColor }}
        >
          {this.props.group.groupName.slice(1).toUpperCase()}
        </Avatar>
      );
    }
  };

  render() {
    return (
      <>
        <Navbar bg="white" variant="light">
          <Navbar.Collapse id="basic-navbar-nav">
            <Nav className="mr-auto">{this.renderGroupAvatar()}</Nav>
          </Navbar.Collapse>
          <Navbar.Collapse className="justify-content-end">
            <Button
              title="Leave conversation"
              size="large"
              shape="round"
              type="primary"
              danger
              onClick={this.leaveConversation}
            >
              <CloseCircleOutlined />
            </Button>
          </Navbar.Collapse>
        </Navbar>
      </>
    );
  }
}

export default connect(
  mapStateToProps,
  actionCreators
)(RightGroupConversationMessageHelper);

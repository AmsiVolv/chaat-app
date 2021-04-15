import React from "react";
import { connect } from "react-redux";
import * as actionCreators from "../../actions/conversation";
import { Button, message } from "antd";
import { ClearOutlined, DeleteOutlined } from "@ant-design/icons";
import { Nav, Navbar } from "react-bootstrap";

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

  getRecepientUsername = () => {
    if (typeof this.props.recepientUser !== "undefined") {
      return this.props.recepientUser.username;
    }
  };

  render() {
    return (
      <>
        <Navbar bg="white" variant="light">
          <Navbar.Collapse id="basic-navbar-nav">
            <Nav className="mr-auto">
              <p>{this.getRecepientUsername()}</p>
            </Nav>
          </Navbar.Collapse>
          <Navbar.Collapse className="justify-content-end">
            <Button
              title="Clear"
              size="large"
              shape="round"
              onClick={this.clearConversation}
            >
              <ClearOutlined />
            </Button>
            <Button
              title="Delete"
              size="large"
              shape="round"
              type="primary"
              danger
              onClick={this.deleteConversation}
            >
              <DeleteOutlined />
            </Button>
          </Navbar.Collapse>
        </Navbar>
      </>
    );
  }
}

export default connect(mapStateToProps, actionCreators)(RightMessageHelper);

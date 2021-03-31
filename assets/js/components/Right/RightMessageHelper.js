import React from "react";
import { connect } from "react-redux";
import * as actionCreators from "../../actions/conversation";
import { Button } from "antd";
import { ClearOutlined, DeleteOutlined } from "@ant-design/icons";

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
    this.props.clearConversation(this.props.conversationId);
    this.props.fetchConversations();
  };

  deleteConversation = () => {
    this.props.deleteConversation(this.props.conversationId);
    this.props.history.push("/");
    this.props.fetchConversations();
  };

  getRecepientUsername = () => {
     if (typeof this.props.recepientUser !== 'undefined') {
         return this.props.recepientUser.username
     }
  }

  render() {
    return (
        <>
            <div className="col-12 py-3 d-flex justify-content-end">
                <div className="mr-auto align-self-center"><p>{this.getRecepientUsername()}</p></div>
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
            </div>
        </>
    );
  }
}

export default connect(mapStateToProps, actionCreators)(RightMessageHelper);

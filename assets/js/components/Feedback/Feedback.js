import React from "react";
import { Rate, Button, Form, Input, Layout, Typography, message } from "antd";
import translate from "../helpers/translate";
import { connect } from "react-redux";
import * as actionCreators from "../../actions/conversation";

const mapStateToProps = (state) => {
  return state;
};

class Feedback extends React.Component {
  formRef = React.createRef();

  constructor(props) {
    super(props);
    this.state = {
      feedback: {
        comment: "",
        rate: "",
      },
    };

    this.onSubmit = this.onSubmit.bind(this);
  }

  onSubmit = (values) => {
    this.setState(
      (prevState) => ({
        feedback: {
          ...prevState.feedback,
          comment: values.comment,
          rate: values.rate,
        },
      }),
      () => {
        this.props.sendFeedback(this.state.feedback);
        message.success(translate("feedback.success"));
      }
    );
  };

  render() {
    return (
      <Layout>
        <Layout.Content className="site-layout">
          <div
            className="site-layout-background"
            style={{ padding: 24, minHeight: "75vh" }}
          >
            <Typography.Title>{translate("feedback.title")}</Typography.Title>

            <Form ref={this.formRef} onFinish={this.onSubmit}>
              <Form.Item
                name="comment"
                label={translate("feedback.label.comment")}
                rules={[{ required: true }]}
              >
                <Input />
              </Form.Item>
              <Form.Item
                name="rate"
                label={translate("feedback.label.rate")}
                rules={[{ required: true }]}
              >
                <Rate />
              </Form.Item>
              <Form.Item>
                <Button type="primary" htmlType="submit">
                  {translate("feedback.label.send")}
                </Button>
              </Form.Item>
            </Form>
          </div>
        </Layout.Content>
      </Layout>
    );
  }
}

export default connect(mapStateToProps, actionCreators)(Feedback);

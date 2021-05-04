import React from "react";
import * as ReactDOM from "react-dom";
import Chatbot from "react-chatbot-kit";
import config from "./config/config";
import ActionProvider from "./config/ActionProvider";
import MessageParser from "./config/MessageParser";
import { routes } from "../helpers/routes";
import { savePDF } from "@progress/kendo-react-pdf";
import { Button, Typography } from "antd";
import { DownloadOutlined } from "@ant-design/icons";
import translate from "../helpers/translate";

class Bot extends React.Component {
  pdfExportComponent;

  constructor(props) {
    super(props);

    this.saveMessages = this.saveMessages.bind(this);
    this.loadMessages = this.loadMessages.bind(this);
  }

  saveMessages = (messages) => {
    const jsonMessages = JSON.stringify(messages);
    localStorage.setItem("chat_messages", jsonMessages);

    fetch(routes.chatbot.saveMessages.route, {
      method: routes.chatbot.saveMessages.method,
      body: JSON.stringify({ chatbotMessages: messages }),
    });
  };

  loadMessages = () => {
    return JSON.parse(localStorage.getItem("chat_messages"));
  };

  exportPDFWithMethod = () => {
    savePDF(ReactDOM.findDOMNode(this.chatbot), { paperSize: "A4" });
  };

  renderChabotHeader = () => {
    return (
      <div className="d-flex w-100 flex-row align-items-center">
        <div className="flex-grow-1">
          {" "}
          <h6>{translate("chatbot.header")}</h6>{" "}
        </div>
        <div className="align-self-end">
          <Button
            title={translate("chatbot.downloadPDF")}
            type="primary"
            shape="round"
            onClick={this.exportPDFWithMethod}
            icon={<DownloadOutlined />}
          />
        </div>
      </div>
    );
  };

  render() {
    return (
      <div
        className="col-md-12 col-sm-12 d-flex justify-content-center align-content-center g-0"
        ref={(chatbot) => (this.chatbot = chatbot)}
      >
        <Chatbot
          headerText={this.renderChabotHeader()}
          config={config}
          actionProvider={ActionProvider}
          messageParser={MessageParser}
          saveMessages={this.saveMessages}
          messageHistory={this.loadMessages()}
        />
      </div>
    );
  }
}

export default Bot;

import React from "react";
import Chatbot from "react-chatbot-kit";
import config from "./config/config";
import ActionProvider from "./config/ActionProvider";
import MessageParser from "./config/MessageParser";
import {routes} from "../helpers/routes";

class Bot extends React.Component {
    constructor(props) {
        super(props);

        this.saveMessages = this.saveMessages.bind(this);
        this.loadMessages = this.loadMessages.bind(this);
    }

    saveMessages = (messages) => {
        const jsonMessages = JSON.stringify(messages)
        localStorage.setItem("chat_messages", jsonMessages);

        fetch(routes.chatbot.saveMessages.route, {
            method: routes.chatbot.saveMessages.method,
            body: JSON.stringify({chatbotMessages: messages}),
        });
    };

    loadMessages = () => {
        return JSON.parse(localStorage.getItem("chat_messages"));
    };

    render() {
    return (
      <div className="col-md-12 col-sm-12 d-flex justify-content-center align-content-center g-0">
        <Chatbot
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

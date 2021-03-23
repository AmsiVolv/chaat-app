import React from "react";
import Chatbot from 'react-chatbot-kit'
import config from "./config/config";
import ActionProvider from "./config/ActionProvider";
import MessageParser from "./config/MessageParser";

class Bot extends React.Component {
    render() {
        return (
            <div className='col-md-12 d-flex justify-content-center align-content-center'>
                <Chatbot
                         config={config}
                         actionProvider={ActionProvider}
                         messageParser={MessageParser} />
            </div>
        )
    }
}

export default Bot;

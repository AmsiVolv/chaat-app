class ActionProvider {
  constructor(createChatBotMessage, setStateFunc, createClientMessage) {
    this.createChatBotMessage = createChatBotMessage;
    this.setState = setStateFunc;
    this.createClientMessage = createClientMessage;
  }

  greet() {
    const greetingMessage = this.createChatBotMessage("Hi, friend.");
    this.updateChatbotState(greetingMessage);
  }

  handleCourseList = () => {
    const message = this.createChatBotMessage(
      "Please can you provide some information about course?",
      {
        withAvatar: true,
        widget: "courseChoice",
      }
    );

    this.updateChatbotState(message);
  };

  handleCourseSelect = (val) => {
    const messageClient = this.createClientMessage(
      "Show me info about course: " + val
    );

    const messageBot = this.createChatBotMessage(
      "Select a info about course, " + val + " which i should to find",
      {
        withAvatar: true,
        widget: "CourseSelectInfoWidget",
      }
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(messageBot);
  };

  handleGetAllCourseInfo = () => {
    const messageClient = this.createClientMessage(
      "I want to get all information about this course"
    );
    this.updateChatbotState(messageClient);

    const messageBot = this.createChatBotMessage("I found this information:", {
      withAvatar: true,
      widget: "CourseShowInfoWidget",
    });
    this.updateChatbotState(messageBot);
  };

  updateChatbotState(message) {
    if (Array.isArray(message)) {
      this.setState((state) => ({
        ...state,
        messages: [...state.messages, ...message],
      }));
    } else {
      this.setState((state) => ({
        ...state,
        messages: [...state.messages, message],
      }));
    }
  }
}

export default ActionProvider;

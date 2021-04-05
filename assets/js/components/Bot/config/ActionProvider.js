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

    const messageClient = this.createClientMessage(
      "Please can you provide me info about course?"
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(message);
  };

  handleApplicantsList = () => {
    const message = this.createChatBotMessage(
      "Vyberte pro vás zajímavou sekci",
      {
        withAvatar: true,
        widget: "applicantsChoise",
      }
    );

    const messageClient = this.createClientMessage(
      "Mě zajímá informace pro uchazeče"
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(message);
  };

  handleStudyApplication = () => {
    const message = this.createChatBotMessage(
      "Vyberte pro vás zajímavou sekci",
      {
        withAvatar: true,
        widget: "studyApplication",
      }
    );

    const messageClient = this.createClientMessage(
      "Mě zajímá informace pro přijímací řízení a přihláška ke studiu"
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(message);
  };

  handleStudyPrograms = () => {
    const message = this.createChatBotMessage(
      "Vyberte pro vás zajímavou sekci",
      {
        withAvatar: true,
        widget: "studyPrograms",
      }
    );

    const messageClient = this.createClientMessage(
      "Mě zajímá informace pro studijní programy"
    );

    const secondBotMessage = this.createChatBotMessage(
      "Možná vás zajímá něco jiného?",
      {
        delay: 500,
        withAvatar: true,
        widget: "applicantsChoise",
      }
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(message);
    this.updateChatbotState(secondBotMessage);
  };

  handlePreparatoryCourses = () => {
    const message = this.createChatBotMessage(
      "Vyberte pro vás zajímavou sekci",
      {
        withAvatar: true,
        widget: "preparatoryCourses",
      }
    );

    const messageClient = this.createClientMessage(
      "Mě zajímá informace pro přípravné kurzy"
    );

    const secondBotMessage = this.createChatBotMessage(
      "Možná vás zajímá něco jiného?",
      {
        delay: 500,
        withAvatar: true,
        widget: "applicantsChoise",
      }
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(message);
    this.updateChatbotState(secondBotMessage);
  };

  handleOpenDays = () => {
    const message = this.createChatBotMessage(
      "Vyberte pro vás zajímavou sekci",
      {
        withAvatar: true,
        widget: "openDays",
      }
    );

    const messageClient = this.createClientMessage(
      "Mě zajímá informace pro dny otevřených dveří"
    );

    const secondBotMessage = this.createChatBotMessage(
      "Možná vás zajímá něco jiného?",
      {
        delay: 500,
        withAvatar: true,
        widget: "applicantsChoise",
      }
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(message);
    this.updateChatbotState(secondBotMessage);
  };

  handleStudyAdmisions = () => {
    const messageClient = this.createClientMessage(
      "Mě zajímá informace o přimacím řizení"
    );

    const botMessage = this.createChatBotMessage("Už na tom pracují");

    const secondBotMessage = this.createChatBotMessage(
      "Vyberte fakultu, o kterou máte zájem",
      {
        delay: 500,
        withAvatar: true,
        widget: "studyAdmissions",
      }
    );

    const thirdBotMessage = this.createChatBotMessage(
      "Možná vás zajímá něco jiného?",
      {
        delay: 1000,
        withAvatar: true,
        widget: "applicantsChoise",
      }
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(botMessage);
    this.updateChatbotState(secondBotMessage);
    this.updateChatbotState(thirdBotMessage);
  };

  handleFilterCourseInfo = () => {
    const message = this.createChatBotMessage(
      "Please select information, for print",
      {
        withAvatar: true,
        widget: "courseInfoChoice",
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
      "I want to get this information about this course"
    );
    this.updateChatbotState(messageClient);

    const messageBot = this.createChatBotMessage("I found this information:", {
      withAvatar: true,
      widget: "CourseShowInfoWidget",
    });

    const secondMessageBot = this.createChatBotMessage(
      "Do you need some additional help?",
      {
        withAvatar: true,
        delay: 1000,
        widget: "QuestionOptions",
      }
    );

    this.updateChatbotState(messageBot);
    this.updateChatbotState(secondMessageBot);
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

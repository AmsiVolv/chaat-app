import translate from "../../helpers/translate";

class ActionProvider {
  constructor(createChatBotMessage, setStateFunc, createClientMessage) {
    this.createChatBotMessage = createChatBotMessage;
    this.setState = setStateFunc;
    this.createClientMessage = createClientMessage;
  }

  greet() {
    const greetingMessage = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.greeting")
    );
    this.updateChatbotState(greetingMessage);
  }

  handleUnknown = () => {
    const message = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.unknown")
    );
    const messageTwo = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.unknownTwo"),
      {
        delay: 500,
        withAvatar: true,
        widget: "QuestionOptions",
      }
    );

    this.updateChatbotState(message);
    this.updateChatbotState(messageTwo);
  };

  handleInitlist = () => {
    const message = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.selectDistination"),
      {
        withAvatar: true,
        widget: "QuestionOptions",
      }
    );

    this.updateChatbotState(message);
  };

  handleStudentQuestionOptions = () => {
    const message = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.interestingSection"),
      {
        withAvatar: true,
        widget: "studentQuestionOptions",
      }
    );

    this.updateChatbotState(message);
  };

  handleCourseList = () => {
    const message = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.subjectInfoProvide"),
      {
        withAvatar: true,
        widget: "courseChoice",
      }
    );

    const messageClient = this.createClientMessage(
      translate("chatbot.actionProvider.user.subjectInfoProvide")
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(message);
  };

  handleTeacherChoice = () => {
    const message = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.teacherShow"),
      {
        withAvatar: true,
        widget: "teacherChoice",
      }
    );

    const messageClient = this.createClientMessage(
      translate("chatbot.actionProvider.user.teacherShow")
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(message);
  };

  mealHandler = () => {
    const message = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.selectMealDistination"),
      {
        withAvatar: true,
        widget: "mealInfo",
      }
    );

    const messageClient = this.createClientMessage(
      translate("chatbot.actionProvider.user.selectMealDistination")
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(message);
  };

  handleFacultyChoice = () => {
    const message = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.oneSecond"),
      {
        withAvatar: true,
        widget: "facultyChoice",
      }
    );

    const messageClient = this.createClientMessage(
      translate("chatbot.actionProvider.user.faculty")
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(message);
  };

  handleApplicantsList = () => {
    const message = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.interestingSection"),
      {
        withAvatar: true,
        widget: "applicantsChoise",
      }
    );

    const messageClient = this.createClientMessage(
      translate("chatbot.actionProvider.user.applicantsInfo")
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(message);
  };

  handleStudyApplication = () => {
    const message = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.interestingSection"),
      {
        withAvatar: true,
        widget: "studyApplication",
      }
    );

    const messageClient = this.createClientMessage(
      translate("chatbot.actionProvider.user.applicationInfo")
    );

    this.updateChatbotState(messageClient);
    this.updateChatbotState(message);
  };

  handleStudyPrograms = () => {
    const message = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.interestingSection"),
      {
        withAvatar: true,
        widget: "studyPrograms",
      }
    );

    const messageClient = this.createClientMessage(
      translate("chatbot.actionProvider.user.studyProgramsInfo")
    );

    const secondBotMessage = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.interestingSection"),
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
      translate("chatbot.actionProvider.bot.interestingSection"),
      {
        withAvatar: true,
        widget: "preparatoryCourses",
      }
    );

    const messageClient = this.createClientMessage(
      translate("chatbot.actionProvider.user.preparatoryCoursesInfo")
    );

    const secondBotMessage = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.interestingSection"),
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
      translate("chatbot.actionProvider.bot.interestingSection"),
      {
        withAvatar: true,
        widget: "openDays",
      }
    );

    const messageClient = this.createClientMessage(
      translate("chatbot.actionProvider.user.openDaysInfo")
    );

    const secondBotMessage = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.interestingSection"),
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

    const botMessage = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.workingOnIt")
    );

    const secondBotMessage = this.createChatBotMessage(
      "Vyberte fakultu, o kterou máte zájem",
      {
        delay: 500,
        withAvatar: true,
        widget: "studyAdmissions",
      }
    );

    const thirdBotMessage = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.interestingSection"),
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

  handleTrialTests = () => {
    const messageClient = this.createClientMessage(
      translate("chatbot.actionProvider.user.trialTest")
    );

    const botMessage = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.workingOnIt")
    );

    const secondBotMessage = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.foundThis"),
      {
        delay: 500,
        withAvatar: true,
        widget: "trialTests",
      }
    );

    const thirdBotMessage = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.interestingSection"),
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
      translate("chatbot.actionProvider.bot.infoForPrint"),
      {
        withAvatar: true,
        widget: "courseInfoChoice",
      }
    );

    this.updateChatbotState(message);
  };

  handleCourseSelect = (val) => {
    const messageClient = this.createClientMessage(
      translate("chatbot.actionProvider.user.showMe") + val
    );

    const messageBot = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.selectPartOne") +
        val +
        translate("chatbot.actionProvider.bot.selectPartTwo"),
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
      translate("chatbot.actionProvider.user.courseGetThis")
    );
    this.updateChatbotState(messageClient);

    const messageBot = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.foundThis"),
      {
        withAvatar: true,
        widget: "CourseShowInfoWidget",
      }
    );

    const secondMessageBot = this.createChatBotMessage(
      translate("chatbot.actionProvider.bot.interestingSection"),
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

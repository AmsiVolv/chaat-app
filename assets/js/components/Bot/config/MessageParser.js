import translate from "../../helpers/translate";

// MessageParser starter code
class MessageParser {
  constructor(actionProvider, state) {
    this.actionProvider = actionProvider;
    this.state = state;
  }

  parse(message) {
    const lowerCaseMessage = message.toLowerCase();
    let answerDetected = false;

    if (
      lowerCaseMessage.includes("hello") ||
      lowerCaseMessage.includes(translate("chatbot.messages.back"))
    ) {
      this.actionProvider.handleInitlist();
      answerDetected = true;
    }

    if (lowerCaseMessage.includes(translate("chatbot.messages.student"))) {
      this.actionProvider.handleStudentQuestionOptions();
      answerDetected = true;
    }

    if (lowerCaseMessage.includes(translate("chatbot.messages.course"))) {
      this.actionProvider.handleCourseList();
      answerDetected = true;
    }

    if (
      lowerCaseMessage.includes(translate("chatbot.messages.teacher")) ||
      lowerCaseMessage.includes(translate("chatbot.messages.teacherTwo")) ||
      lowerCaseMessage.includes(translate("chatbot.messages.teacherThree"))
    ) {
      this.actionProvider.handleTeacherChoice();
      answerDetected = true;
    }

    if (lowerCaseMessage.includes(translate("chatbot.messages.faculty"))) {
      this.actionProvider.handleFacultyChoice();
      answerDetected = true;
    }

    if (
      lowerCaseMessage.includes(translate("chatbot.messages.meal")) ||
      lowerCaseMessage.includes(translate("chatbot.messages.mealTwo")) ||
      lowerCaseMessage.includes(translate("chatbot.messages.mealThree"))
    ) {
      this.actionProvider.mealHandler();
      answerDetected = true;
    }

    if (lowerCaseMessage.includes(translate("chatbot.messages.abiturients"))) {
      this.actionProvider.handleApplicantsList();
      answerDetected = true;
    }

    if (
      lowerCaseMessage.includes(translate("chatbot.messages.studyAdmisions"))
    ) {
      this.actionProvider.handleStudyAdmisions();
      answerDetected = true;
    }

    if (lowerCaseMessage.includes(translate("chatbot.messages.tests"))) {
      this.actionProvider.handleTrialTests();
      answerDetected = true;
    }

    if (
      lowerCaseMessage.includes(translate("chatbot.messages.studyPrograms"))
    ) {
      this.actionProvider.handleStudyPrograms();
      answerDetected = true;
    }

    if (lowerCaseMessage.includes(translate("chatbot.messages.openDays"))) {
      this.actionProvider.handleOpenDays();
      answerDetected = true;
    }

    if (
      lowerCaseMessage.includes(
        translate("chatbot.messages.preparatoryCourses")
      )
    ) {
      this.actionProvider.handlePreparatoryCourses();
      answerDetected = true;
    }

    if (!answerDetected) {
      this.actionProvider.handleUnknown();
    }
  }
}

export default MessageParser;

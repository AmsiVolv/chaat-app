import translate from "../../helpers/translate";

// MessageParser starter code
class MessageParser {
  constructor(actionProvider, state) {
    this.actionProvider = actionProvider;
    this.state = state;
  }

  parse(message) {
    const lowerCaseMessage = message.toLowerCase();

    if (
      lowerCaseMessage.includes("hello") ||
      lowerCaseMessage.includes(translate("chatbot.messages.back"))
    ) {
      this.actionProvider.handleInitlist();
    }

    if (lowerCaseMessage.includes(translate("chatbot.messages.student"))) {
      this.actionProvider.handleStudentQuestionOptions();
    }

    if (lowerCaseMessage.includes(translate("chatbot.messages.course"))) {
      this.actionProvider.handleCourseList();
    }

    if (
      lowerCaseMessage.includes(translate("chatbot.messages.teacher")) ||
      lowerCaseMessage.includes(translate("chatbot.messages.teacherTwo")) ||
      lowerCaseMessage.includes(translate("chatbot.messages.teacherThree"))
    ) {
      this.actionProvider.handleTeacherChoice();
    }

    if (lowerCaseMessage.includes(translate("chatbot.messages.faculty"))) {
      this.actionProvider.handleFacultyChoice();
    }

    if (
      lowerCaseMessage.includes(translate("chatbot.messages.meal")) ||
      lowerCaseMessage.includes(translate("chatbot.messages.mealTwo")) ||
      lowerCaseMessage.includes(translate("chatbot.messages.mealThree"))
    ) {
      this.actionProvider.mealHandler();
    }

    if (lowerCaseMessage.includes(translate("chatbot.messages.abiturients"))) {
      this.actionProvider.handleApplicantsList();
    }

    if (
      lowerCaseMessage.includes(translate("chatbot.messages.studyAdmisions"))
    ) {
      this.actionProvider.handleStudyAdmisions();
    }

    if (lowerCaseMessage.includes(translate("chatbot.messages.tests"))) {
      this.actionProvider.handleTrialTests();
    }

    if (
      lowerCaseMessage.includes(translate("chatbot.messages.studyPrograms"))
    ) {
      this.actionProvider.handleStudyPrograms();
    }

    if (lowerCaseMessage.includes(translate("chatbot.messages.openDays"))) {
      this.actionProvider.handleOpenDays();
    }

    if (
      lowerCaseMessage.includes(
        translate("chatbot.messages.preparatoryCourses")
      )
    ) {
      this.actionProvider.handlePreparatoryCourses();
    }
  }
}

export default MessageParser;

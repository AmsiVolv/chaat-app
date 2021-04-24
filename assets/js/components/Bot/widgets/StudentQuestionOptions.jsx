import React from "react";
import translate from "../../helpers/translate";

const StudentQuestionOptions = (props) => {
  const options = [
    {
      text: translate("chatbot.courseInfo"),
      handler: props.actionProvider.handleCourseList,
      id: 1,
    },
    {
      text: translate("chatbot.teacherInfo"),
      handler: props.actionProvider.handleTeacherChoice,
      id: 2,
    },
    {
      text: translate("chatbot.facultyInfo"),
      handler: props.actionProvider.handleFacultyChoice,
      id: 3,
    },
    {
      text: translate("chatbot.mealInfo"),
      handler: props.actionProvider.mealHandler,
      id: 4,
    },
    {
      text: translate("back"),
      handler: props.actionProvider.handleInitlist,
      id: 5,
    },
  ];

  const optionsMarkup = options.map((option) => (
    <button
      className="learning-option-button"
      key={option.id}
      onClick={option.handler}
    >
      {option.text}
    </button>
  ));

  return <div className="learning-options-container">{optionsMarkup}</div>;
};

export default StudentQuestionOptions;

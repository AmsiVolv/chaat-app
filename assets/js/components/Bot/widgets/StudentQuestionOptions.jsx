import React from "react";
import translate from "../../helpers/translate";

const StudentQuestionOptions = (props) => {
  const options = [
    {
      text: "Course info",
      handler: props.actionProvider.handleCourseList,
      id: 1,
    },
    {
      text: "Teacher info",
      handler: props.actionProvider.handleTeacherChoice,
      id: 2,
    },
    {
      text: "Faculty info",
      handler: props.actionProvider.handleFacultyChoice,
      id: 3,
    },
    { text: "Stravování", handler: props.actionProvider.mealHandler, id: 4 },
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

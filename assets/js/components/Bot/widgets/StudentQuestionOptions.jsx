import React from "react";

const StudentQuestionOptions = (props) => {
  const options = [
    {
      text: "Course info",
      handler: props.actionProvider.handleCourseList,
      id: 1,
    },
    { text: "Teacher info", handler: () => {}, id: 2 },
    { text: "Faculty info", handler: () => {}, id: 3 },
    { text: "Stravování", handler: props.actionProvider.mealHandler, id: 4 },
    { text: "Zpět", handler: props.actionProvider.handleInitlist, id: 5 },
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

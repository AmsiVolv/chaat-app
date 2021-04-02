import React from "react";

const QuestionOptions = (props) => {
  const options = [
    {
      text: "Course info",
      handler: props.actionProvider.handleCourseList,
      id: 1,
    },
    { text: "Teacher info", handler: () => {}, id: 2 },
    { text: "Reading info", handler: () => {}, id: 3 },
    { text: "Faculty info", handler: () => {}, id: 4 },
    { text: "VŠE news", handler: () => {}, id: 5 },
    { text: "Pro absolventy", handler: () => {}, id: 6 },
    {
      text: "Pro uchazeče",
      handler: props.actionProvider.handleApplicantsList,
      id: 7,
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

export default QuestionOptions;

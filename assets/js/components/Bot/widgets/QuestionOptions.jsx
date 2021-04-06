import React from "react";

const QuestionOptions = (props) => {
  const options = [
    {
      text: "Pro studenty",
      handler: props.actionProvider.handleStudentQuestionOptions,
      id: 1,
    },
    {
      text: "Pro uchazeče",
      handler: props.actionProvider.handleApplicantsList,
      id: 2,
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

import React from "react";

const StudyApplication = (props) => {
  const options = [
    {
      text: "Info o přijímacím řízení",
      handler: props.actionProvider.handleStudyAdmisions,
      id: 1,
    },
    {
      text: "Vzoroé testy",
      handler: props.actionProvider.handleStudyPrograms,
      id: 2,
    },
    {
      text: "Zpět",
      handler: props.actionProvider.handleApplicantsList,
      id: 3,
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

export default StudyApplication;

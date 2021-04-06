import React from "react";

const ApplicantsChoiceList = (props) => {
  const options = [
    {
      text: "Přijímací řízení a přihláška ke studiu",
      handler: props.actionProvider.handleStudyApplication,
      id: 1,
    },
    {
      text: "Studijní programy",
      handler: props.actionProvider.handleStudyPrograms,
      id: 2,
    },
    {
      text: "Dny otevřených dveří",
      handler: props.actionProvider.handleOpenDays,
      id: 3,
    },
    {
      text: "Přípravné kurzy",
      handler: props.actionProvider.handlePreparatoryCourses,
      id: 4,
    },
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

export default ApplicantsChoiceList;

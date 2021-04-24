import React from "react";
import { routes } from "../../../helpers/routes";
import translate from "../../../helpers/translate";

const CourseSelectInfoWidget = (props) => {
  const { setState, actionProvider } = props;

  const options = [
    {
      text: translate("chatbot.selectAnotherCourse"),
      handler: props.actionProvider.handleCourseList,
      id: 1,
    },
    {
      text: translate("chatbot.selectInteresting"),
      handler: props.actionProvider.handleFilterCourseInfo,
      id: 2,
    },
    {
      text: translate("chatbot.selectAll"),
      handler: fetchCourseInfo,
      id: 3,
    },
  ];

  function fetchCourseInfo() {
    fetch(routes.course.get.route, {
      method: routes.course.get.method,
      body: JSON.stringify({ course: props.course }),
    })
      .then((r) => r.json())
      .then((data) => {
        setState((state) => ({ ...state, courseInfo: data }));
      });
    props.actionProvider.handleGetAllCourseInfo(props.course);
  }

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

export default CourseSelectInfoWidget;

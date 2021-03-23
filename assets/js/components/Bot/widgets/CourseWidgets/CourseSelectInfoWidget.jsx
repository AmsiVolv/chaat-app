import React from "react";

const CourseSelectInfoWidget = (props) => {
    const { setState, actionProvider } = props

    const options = [
        {
            text: "Select another curse",
            handler: props.actionProvider.handleCourseList,
            id: 1,
        },
        {
            text: "All information",
            handler: fetchCourseInfo,
            id: 2,
        }
    ]

      function fetchCourseInfo() {
         fetch('/course/get', {
             method: "POST",
             body: JSON.stringify({course: props.course})
         })
             .then(r => r.json())
             .then((data) => {
                 setState((state) => ({...state, courseInfo: data[0]}))
             })
          props.actionProvider.handleGetAllCourseInfo(props.course)
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

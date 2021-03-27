import React from "react";

const CourseShowInfoWidget = (props) => {
  const { setState, actionProvider } = props;

  function prepareData(key, value, i) {
    if (
      value instanceof Array ||
      value instanceof Object ||
      value === "undefined"
    ) {
      if (key === "readings") {
        console.log(key);
        console.log(value);
      }

      return;
    }
    return (
      <div className="row" key={i}>
        <div className="col-md-2">{key}</div>
        <div className="col-md-10">{value}</div>
      </div>
    );
  }

  return (
    <div className="col-md-12">
      {Object.entries(props.courseInfo).map(([key, value], i) =>
        prepareData(key, value, i)
      )}
    </div>
  );
};

export default CourseShowInfoWidget;

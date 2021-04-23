import React from "react";
import { Button, Select } from "antd";
import translate from "../../helpers/translate";
import { routes } from "../../helpers/routes";

const Courses = (props) => {
  const { setState, actionProvider } = props;
  const { Option } = Select;

  function onChange(value) {
    setState((state) => ({
      ...state,
      course: value,
      isFetchingCourseSelectOptions: false,
    }));
  }

  function onSearch(val) {
    fetchCourse(val);
  }

  function fetchCourse(course) {
    fetch(routes.course.getCourseByName.route, {
      method: routes.course.getCourseByName.method,
      body: JSON.stringify({ course: course }),
    })
      .then((r) => r.json())
      .then((data) => {
        setState((state) => ({ ...state, courses: data }));
      });
  }

  function renderButton() {
    return (
      <Button
        onClick={() => {
          actionProvider.handleCourseSelect(props.course);
        }}
        style={{ marginLeft: 10, marginTop: 5 }}
        type="primary"
        loading={props.isFetchingCourseSelectOptions}
      >
        {props.isFetchingCourseSelectOptions
          ? translate("loading")
          : translate("teacherChoiceSelectButton")}
      </Button>
    );
  }

  if (typeof props.courses !== "undefined" && props.courses.length === 0) {
    fetchCourse("");
  }

  const createCourseOptions = () => {
    return props.courses.map((item) => {
      return (
        <Option key={item.id} value={item.subjectCode}>
          {item.subjectCode + " - " + item.courseTitle}
        </Option>
      );
    });
  };

  return (
    <div className="react-chatbot-kit-chat-bot-message-container">
      <div className="react-chatbot-kit-chat-bot-avatar-container">
        <p className="react-chatbot-kit-chat-bot-avatar-letter">B</p>
      </div>
      <Select
        showSearch
        style={{ width: 350, marginTop: 5 }}
        placeholder="Select a course"
        optionFilterProp="children"
        onChange={onChange}
        onSearch={onSearch}
        filterOption={(input, option) =>
          option.children.toLowerCase().indexOf(input.toLowerCase()) >= 0
        }
      >
        {createCourseOptions()}
      </Select>
      {renderButton()}
    </div>
  );
};

export default Courses;

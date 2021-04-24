import React, { useEffect } from "react";
import { Select, Tooltip, Button } from "antd";
import { SearchOutlined } from "@ant-design/icons";
import translate from "../../../helpers/translate";
import { routes } from "../../../helpers/routes";

const CourseInfoChoice = (props) => {
  const { setState, actionProvider } = props;

  const fetchSelect = () => {
    if (props.isFetchingCourseSelect) {
      useEffect(() => {
        setState((state) => ({ ...state, isFetchingCourseSelect: false }));
      });
      fetch(routes.course.getFilterParams.route, {
        method: routes.course.getFilterParams.method,
        body: JSON.stringify({ course: props.course }),
      })
        .then((r) => r.json())
        .then((data) => {
          setState((state) => ({ ...state, courseFilterOptions: data }));
        });
    }
  };

  function returnSelect(array) {
    return array.map((item) => {
      return (
        <Select.Option key={item} value={item}>
          {translate(item)}
        </Select.Option>
      );
    });
  }

  const createSelectOptions = () => {
    fetchSelect();
    if (!props.isFetchingCourseSelect && props.courseFilterOptions !== []) {
      return Object.entries(props.courseFilterOptions).map(
        ([key, value], i) => {
          return (
            <Select.OptGroup label={translate(key)} key={i}>
              {returnSelect(value)}
            </Select.OptGroup>
          );
        }
      );
    }
  };

  const onChange = (value) => {
    setState((state) => ({ ...state, courseSelectedFilters: value }));
  };

  function intersect(a, b) {
    let setA = new Set(a);
    let setB = new Set(b);
    let intersection = new Set([...setA].filter((x) => setB.has(x)));
    return Array.from(intersection);
  }

  const onSearch = () => {
    let filterParams = {
      course: [],
      readings: [],
      courseScheduling: [],
      teacher: [],
    };
    Object.entries(props.courseFilterOptions).map(([key, array], i) => {
      let intersection = intersect(array, props.courseSelectedFilters);
      if (intersection.length > 0) {
        filterParams[key] = intersection;
      }
    });
    if (
      Object.keys(filterParams).length !== 0 &&
      filterParams.constructor === Object
    ) {
      fetch(routes.course.get.route, {
        method: routes.course.get.method,
        body: JSON.stringify({ course: props.course, filterParams }),
      })
        .then((r) => r.json())
        .then((data) => {
          setState((state) => ({ ...state, courseInfo: data }));
        });
      props.actionProvider.handleGetAllCourseInfo(props.course);
    }
  };

  return (
    <div className="react-chatbot-kit-chat-bot-message-container">
      <div className="react-chatbot-kit-chat-bot-avatar-container">
        <p className="react-chatbot-kit-chat-bot-avatar-letter">B</p>
      </div>
      <Select
        maxTagCount="responsive"
        mode="multiple"
        allowClear
        style={{ width: "50%", marginTop: 5 }}
        placeholder={translate("chatbot.selectFilter")}
        optionFilterProp="children"
        onChange={onChange}
        filterOption={(input, option) => {
          if (option.children) {
            return (
              option.children.toLowerCase().indexOf(input.toLowerCase()) >= 0
            );
          } else if (option.label) {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
          }
        }}
      >
        {createSelectOptions()}
      </Select>
      <Tooltip title="search">
        <Button
          onClick={onSearch}
          shape="round"
          size={"large"}
          style={{ marginLeft: 5 }}
          type="primary"
          icon={<SearchOutlined />}
        />
      </Tooltip>
    </div>
  );
};

export default CourseInfoChoice;

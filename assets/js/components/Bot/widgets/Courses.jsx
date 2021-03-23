import React, {useEffect} from "react";
import {Select} from 'antd';

const Courses = (props) => {
    const { setState, actionProvider } = props
    const { Option } = Select;

    function onChange(value) {
        setState((state) => ({...state, course: value}))
        actionProvider.handleCourseSelect(value)
    }

    function onSearch(val) {
        fetchCourse(val)
    }

    function fetchCourse(course) {
        fetch('/course/getCourseByName', {
            method: "POST",
            body: JSON.stringify({course: course})
        })
            .then(r => r.json())
            .then((data) => {
                setState((state) => ({...state, courses: data}))
            })
    }

    if (typeof props.courses !== 'undefined' && props.courses.length === 0) {
        fetchCourse('')
    }

    const createAirportOptions = () => {
        return props.courses.map((item) => {
            return (
                <Option key={item.id} value={item.subjectCode}>
                    {item.subjectCode + ' - ' + item.courseTitle}
                </Option>
            )
        })
    }

    return <div className="react-chatbot-kit-chat-bot-message-container">
        <div className="react-chatbot-kit-chat-bot-avatar-container"><p
            className="react-chatbot-kit-chat-bot-avatar-letter">B</p></div>
        <Select
            showSearch
            style={{width: 350, marginTop: 5}}
            placeholder="Select a course"
            optionFilterProp="children"
            onChange={onChange}
            // onFocus={onFocus}
            // onBlur={onBlur}
            onSearch={onSearch}
            filterOption={(input, option) =>
                option.children.toLowerCase().indexOf(input.toLowerCase()) >= 0
            }
        >
            {createAirportOptions()}
        </Select>
    </div>
};

export default Courses;

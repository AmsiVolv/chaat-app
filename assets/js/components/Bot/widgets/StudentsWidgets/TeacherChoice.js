import React from "react";
import { Button, Descriptions, Divider, Select, Table, Popover } from "antd";
import translate from "../../../helpers/translate";
import { coursesColumns } from "../../../helpers/columns";

class TeacherChoice extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      teachers: [],
      loading: false,
      selected: false,
      teacherInfo: [],
    };

    this.renderTeacherInfo = this.renderTeacherInfo.bind(this);
    this.onChange = this.onChange.bind(this);
    this.onSearch = this.onSearch.bind(this);
    this.renderSelectOption = this.renderSelectOption.bind(this);
    this.renderButton = this.renderButton.bind(this);
    this.renderTable = this.renderTable.bind(this);
  }

  onChange = (value) => {
    fetch("/teachers/getTeacherInfoById", {
      method: "POST",
      body: JSON.stringify({ teacher: value }),
    })
      .then((r) => r.json())
      .then((data) => {
        this.setState((state) => ({ ...state, teacherInfo: data }));
      });
  };

  onSearch = (value) => {
    fetch("/teachers/getTeacherByName", {
      method: "POST",
      body: JSON.stringify({ teacher: value }),
    })
      .then((r) => r.json())
      .then((data) => {
        this.setState((state) => ({ ...state, teachers: data }));
      });
  };

  renderButton = () => {
    return (
      <Button
        onClick={() => {
          this.setState({ selected: true });
        }}
        style={{ marginLeft: 10, marginTop: 5 }}
        type="primary"
        loading={this.state.teacherInfo.length <= 0}
      >
        {this.state.teacherInfo.length <= 0
          ? translate("loading")
          : translate("teacherChoiceSelectButton")}
      </Button>
    );
  };

  renderTable = () => {
    if (this.state.teacherInfo.courses.length > 0) {
      return (
        <Table
          key="trialTest"
          onRow={(record) => {
            return {
              onDoubleClick: (event) => {
                this.props.setState((state) => ({
                  ...state,
                  course: record.subjectCode,
                }));
                this.props.actionProvider.handleCourseSelect(
                  record.subjectCode
                );
              },
            };
          }}
          columns={coursesColumns}
          rowKey={(data) => data.id}
          dataSource={this.state.teacherInfo.courses}
          pagination={{
            pageSize: 5,
            total: this.state.teacherInfo.courses.length,
            hideOnSinglePage: true,
          }}
        />
      );
    }
  };

  renderTeacherInfo = () => {
    if (this.state.selected) {
      return (
        <div>
          <Divider>
            {" "}
            {translate("infoAbout") + this.state.teacherInfo.name}{" "}
          </Divider>
          <Descriptions bordered column={1}>
            <Descriptions.Item key="name" label={translate("name")}>
              {this.state.teacherInfo.name}
            </Descriptions.Item>
            <Descriptions.Item key="email" label={translate("email")}>
              <a
                className="ant-anchor-link text-left"
                href={`mailto: ${this.state.teacherInfo.email}`}
              >
                {translate("writeAEmail")}
              </a>
              {}
            </Descriptions.Item>
            <Descriptions.Item
              key="officeNumber"
              label={translate("officeNumber")}
            >
              {this.state.teacherInfo.officeNumber}
            </Descriptions.Item>
            <Descriptions.Item
              key="phoneNumber"
              label={translate("phoneNumber")}
            >
              {this.state.teacherInfo.phoneNumber}
            </Descriptions.Item>
            <Descriptions.Item key="teacherUri" label={translate("link")}>
              <a
                className="ant-anchor-link text-left"
                target="_blank"
                href={this.state.teacherInfo.teacherUri}
                rel="noreferrer"
              >
                {translate("findOutINSIS")}
              </a>
            </Descriptions.Item>
          </Descriptions>
          <Divider> {translate("teacherCourses")} </Divider>
          {this.renderTable()}
          <button
            className="learning-option-button"
            onClick={this.props.actionProvider.handleStudentQuestionOptions}
          >
            {translate("back")}
          </button>
        </div>
      );
    }
  };

  renderSelectOption = () => {
    if (this.state.teachers.length > 0) {
      return this.state.teachers.map((teacher) => {
        return (
          <Select.Option key={teacher.id} value={teacher.id}>
            {teacher.name}
          </Select.Option>
        );
      });
    }
  };

  render() {
    return (
      <>
        <div className="react-chatbot-kit-chat-bot-message-container">
          <div className="react-chatbot-kit-chat-bot-avatar-container">
            <p className="react-chatbot-kit-chat-bot-avatar-letter">B</p>
          </div>
          <Select
            showSearch
            style={{ width: 350, marginTop: 5 }}
            placeholder="Select a course"
            optionFilterProp="children"
            onChange={this.onChange}
            onSearch={this.onSearch}
            filterOption={(input, option) =>
              option.children.toLowerCase().indexOf(input.toLowerCase()) >= 0
            }
          >
            {this.renderSelectOption()}
          </Select>
          {this.renderButton()}
        </div>
        {this.renderTeacherInfo()}
      </>
    );
  }
}

export default TeacherChoice;

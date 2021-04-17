import React from "react";
import { Divider, Table } from "antd";
import { facultyColumns, expandStudyProgramsColumns } from "../../../helpers/columns";
import translate from "../../../helpers/translate";
import {routes} from '../../../helpers/routes'

class FacultyChoice extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      faculties: [],
      loading: true,
    };

    this.expandedRowRender = this.expandedRowRender.bind(this);
  }

  componentDidMount() {
    fetch(routes.faculty.getAll.route, {
      method: routes.faculty.getAll.method,
    })
      .then((r) => r.json())
      .then((data) => {
        this.setState((state) => ({
          ...state,
          faculties: data,
          loading: false,
        }));
      });
  }

  expandedRowRender = (studyPrograms) => {
    return (
        <Table
            columns={expandStudyProgramsColumns}
            rowKey={(data) => data.id}
            dataSource={studyPrograms}
            pagination={{
                pageSize: 5,
                total: studyPrograms.length,
                hideOnSinglePage: true,
            }}
            loading={this.state.loading}
        />
    );
  };

  render() {
    return (
      <>
        <Divider>{translate("facultyInfo")}</Divider>
        <Table
          columns={facultyColumns}
          rowKey={(data) => data.id}
          expandable={{
              expandedRowRender: data => this.expandedRowRender(data.studyPrograms),
              rowExpandable: (data) => data.studyPrograms.length > 0,
          }}
          dataSource={this.state.faculties}
          pagination={{
            pageSize: 5,
            total: this.state.faculties.length,
            hideOnSinglePage: true,
          }}
          loading={this.state.loading}
        />
        <button
          className="learning-option-button"
          onClick={this.props.actionProvider.handleStudentQuestionOptions}
        >
          {translate("back")}
        </button>
      </>
    );
  }
}

export default FacultyChoice;

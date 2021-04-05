import React from "react";
import { Table } from "antd";
import reqwest from "reqwest";
import { studyProgramsColumns } from "../../../helpers/columns";

class StudyPrograms extends React.Component {
  state = {
    data: [],
    pagination: {
      current: 1,
      pageSize: 10,
    },
    loading: false,
  };

  componentDidMount() {
    const { pagination } = this.state;
    this.fetch({ pagination });
  }

  handleTableChange = (pagination, filters, sorter) => {
    this.fetch({
      sortField: sorter.field,
      sortOrder: sorter.order,
      pagination,
      ...filters,
    });
  };

  fetch = (params = {}) => {
    this.setState({ loading: true });
    reqwest({
      url: "studyPrograms/get",
      method: "POST",
      type: "json",
    }).then((data) => {
      this.setState({
        loading: false,
        data: data,
        pagination: {
          ...params.pagination,
          total: data.length,
        },
      });
    });
  };

  render() {
    const { data, pagination, loading } = this.state;

    return (
      <Table
        columns={studyProgramsColumns}
        rowKey={(data) => data.studyProgramId}
        expandable={{
          expandedRowRender: (data) => <p style={{ margin: 0 }}>{data.aims}</p>,
          rowExpandable: (data) => data.name !== "Not Expandable",
        }}
        dataSource={data}
        pagination={pagination}
        loading={loading}
        onChange={this.handleTableChange}
      />
    );
  }
}

export default StudyPrograms;

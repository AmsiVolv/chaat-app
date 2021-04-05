import React from "react";
import { Table } from "antd";
import reqwest from "reqwest";
import { preparatoryCourseColumns } from "../../../helpers/columns";

class PreparatoryCourses extends React.Component {
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
      url: "preparatoryCourse/get",
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
        columns={preparatoryCourseColumns}
        hideOnSinglePage
        rowKey={(data) => data.id}
        dataSource={data}
        pagination={pagination}
        loading={loading}
        onChange={this.handleTableChange}
      />
    );
  }
}

export default PreparatoryCourses;

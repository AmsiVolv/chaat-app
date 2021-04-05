import React from "react";
import { Table } from "antd";
import reqwest from "reqwest";
import { openDaysColumns } from "../../../helpers/columns";

class OpenDays extends React.Component {
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
      url: "openDays/get",
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
        columns={openDaysColumns}
        rowKey={(data) => data.id}
        dataSource={data}
        pagination={pagination}
        loading={loading}
        onChange={this.handleTableChange}
      />
    );
  }
}

export default OpenDays;

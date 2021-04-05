import React from "react";
import { Col, Divider, Row, Table } from "antd";
import reqwest from "reqwest";
import { openDaysColumns } from "../../../helpers/columns";
import Title from "antd/es/typography/Title";
import translate from "../../../helpers/translate";

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
      <div>
        <Divider key={"openDays divider"} />
        <Row
          key={"openDays row"}
          align={"middle"}
          justify={"center"}
          style={{ marginTop: "3%" }}
        >
          <Col key={"openDays col"} span={24} style={{ textAlign: "center" }}>
            <Title key={"openDays title"} level={3}>
              {translate("openDays")}
            </Title>
          </Col>
        </Row>
        <Table
          columns={openDaysColumns}
          rowKey={(data) => data.id}
          dataSource={data}
          pagination={{
            pageSize: 5,
            total: pagination,
            hideOnSinglePage: true,
          }}
          loading={loading}
          onChange={this.handleTableChange}
        />
      </div>
    );
  }
}

export default OpenDays;

import React from "react";
import { Col, Divider, Row, Table } from "antd";
import reqwest from "reqwest";
import { preparatoryCourseColumns } from "../../../helpers/columns";
import Title from "antd/es/typography/Title";
import translate from "../../../helpers/translate";

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
      <div>
        <Divider key={"preparatoryCourses divider"} />
        <Row
          key={"preparatoryCourses row"}
          align={"middle"}
          justify={"center"}
          style={{ marginTop: "3%" }}
        >
          <Col
            key={"preparatoryCourses col"}
            span={24}
            style={{ textAlign: "center" }}
          >
            <Title key={"preparatoryCourses title"} level={3}>
              {translate("preparatoryCourses")}
            </Title>
          </Col>
        </Row>
        <Table
          columns={preparatoryCourseColumns}
          hideOnSinglePage
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

export default PreparatoryCourses;

import React from "react";
import { Col, Divider, Row, Table } from "antd";
import reqwest from "reqwest";
import { studyAdmissions } from "../../../helpers/columns";
import Title from "antd/es/typography/Title";
import translate from "../../../helpers/translate";

class StudyAdmission extends React.Component {
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
      url: "faculty/get",
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
        <Divider key={"studyAdmission divider"} />
        <Row
          key={"studyAdmission row"}
          align={"middle"}
          justify={"center"}
          style={{ marginTop: "3%" }}
        >
          <Col
            key={"studyAdmission col"}
            span={24}
            style={{ textAlign: "center" }}
          >
            <Title key={"studyAdmission title"} level={3}>
              {translate("studyAdmission")}
            </Title>
          </Col>
        </Row>
        <Table
          key="studyAdmission"
          columns={studyAdmissions}
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

export default StudyAdmission;

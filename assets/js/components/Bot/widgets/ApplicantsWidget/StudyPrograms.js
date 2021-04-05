import React from "react";
import { Col, Divider, Row, Table } from "antd";
import reqwest from "reqwest";
import { studyProgramsColumns } from "../../../helpers/columns";
import Title from "antd/es/typography/Title";
import translate from "../../../helpers/translate";

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
      <div>
        <Divider key={"studyPrograms divider"} />
        <Row
          key={"studyPrograms row"}
          align={"middle"}
          justify={"center"}
          style={{ marginTop: "3%" }}
        >
          <Col
            key={"studyPrograms col"}
            span={24}
            style={{ textAlign: "center" }}
          >
            <Title key={"studyPrograms title"} level={3}>
              {translate("studyPrograms")}
            </Title>
          </Col>
        </Row>
        <Table
          columns={studyProgramsColumns}
          rowKey={(data) => data.studyProgramId}
          expandable={{
            expandedRowRender: (data) => (
              <p style={{ margin: 0 }}>{data.aims}</p>
            ),
            rowExpandable: (data) => data.name !== "Not Expandable",
          }}
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

export default StudyPrograms;

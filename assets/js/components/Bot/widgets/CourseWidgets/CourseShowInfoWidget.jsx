import React from "react";
import { Table, Row, Col, Divider, Descriptions } from "antd";
import translate from "../../../helpers/translate";
import {
  courseSchedulingColumns,
  readingColumns,
  teacherColumns,
} from "../../../helpers/columns";
import Title from "antd/es/typography/Title";

class CourseShowInfoWidget extends React.Component {
  constructor(props) {
    super(props);
  }

  state = {
    data: [],
    pagination: {
      current: 1,
      pageSize: 10,
    },
    loading: false,
  };

  prepareData = (key, value) => {
    if (value instanceof Array && value.length !== 0) {
      if (
        key === "reading" ||
        key === "courseScheduling" ||
        key === "teacher"
      ) {
        const { loading } = this.state;
        const totalLength = value.length;
        const data = value;
        let columns = "";

        if (key === "reading") {
          columns = readingColumns;
        }

        if (key === "courseScheduling") {
          columns = courseSchedulingColumns;
        }

        if (key === "teacher") {
          columns = teacherColumns;
        }

        return (
          <div key={key}>
            <Divider key={key + " divider"} />
            <Row
              key={key + " row"}
              align={"middle"}
              justify={"center"}
              style={{ marginTop: "3%" }}
            >
              <Col key={key + " col"} span={24} style={{ textAlign: "center" }}>
                <Title key={key + " title"} level={3}>
                  {translate(key)}
                </Title>
              </Col>
            </Row>
            <Table
              bordered
              key={key}
              columns={columns}
              rowKey={(data) => data.id}
              dataSource={data}
              pagination={{
                pageSize: 5,
                total: totalLength,
                hideOnSinglePage: true,
              }}
              loading={loading}
            />
          </div>
        );
      } else {
        return (
          <div key={key}>
            <Divider key={key + " divider"} />
            <Row
              key={key + " row"}
              align={"middle"}
              justify={"center"}
              style={{ marginTop: "3%" }}
            >
              <Col key={key + " col"} span={24} style={{ textAlign: "center" }}>
                <Title key={key + " title"} level={3}>
                  {translate(key)}
                </Title>
              </Col>
            </Row>
            <Descriptions key={key} bordered column={1}>
              {this.renderListItems(value[0])}
            </Descriptions>
          </div>
        );
      }
    }
  };

  renderListItems = (listItemsArray) => {
    return Object.entries(listItemsArray).map(([itemKey, itemValue]) => {
      return (
        <Descriptions.Item key={itemKey} label={translate(itemKey)}>
          {itemValue}
        </Descriptions.Item>
      );
    });
  };

  componentDidMount() {
    const { pagination } = this.state;
  }

  render() {
    const { data, pagination, loading } = this.state;

    return (
      <div className="col-md-12">
        {Object.entries(this.props.courseInfo).map(([infoKey, infoValue]) =>
          this.prepareData(infoKey, infoValue)
        )}
      </div>
    );
  }
}

export default CourseShowInfoWidget;

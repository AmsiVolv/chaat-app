import {
  courseSchedulingColumns,
  readingColumns,
  teacherColumns,
} from "./columns";
import { Col, Descriptions, Divider, Row, Table } from "antd";
import Title from "antd/es/typography/Title";
import translate from "./translate";
import React from "react";

export const prepareData = (key, value) => {
  if (value instanceof Array && value.length !== 0) {
    if (key === "reading" || key === "courseScheduling" || key === "teacher") {
      const { loading } = false;
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
            {renderListItems(value[0])}
          </Descriptions>
        </div>
      );
    }
  }
};

const renderListItems = (listItemsArray) => {
  return Object.entries(listItemsArray).map(([itemKey, itemValue]) => {
    return (
      <Descriptions.Item key={itemKey} label={translate(itemKey)}>
        {itemValue}
      </Descriptions.Item>
    );
  });
};

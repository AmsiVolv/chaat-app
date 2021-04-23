import React from "react";
import { Card, List, Layout, Typography } from "antd";
import { Link } from "react-router-dom";
import translate from "../helpers/translate";

const data = [
  {
    title: (
      <Link as={Link} to="/">
        {translate("about.lists.firstTitle")}
      </Link>
    ),
    content: translate("about.lists.firstContent"),
  },
  {
    title: (
      <Link as={Link} to="/bot">
        {translate("about.lists.secondTitle")}
      </Link>
    ),
    content: translate("about.lists.secondContent"),
  },
];

class About extends React.Component {
  render() {
    return (
      <Layout>
        <Layout.Content className="site-layout">
          <div
            className="site-layout-background"
            style={{ padding: 24, minHeight: "75vh" }}
          >
            <Typography.Title>{translate("about.title")}</Typography.Title>
            <Typography.Paragraph>
              {translate("about.firstParagraph")}
            </Typography.Paragraph>
            <Typography.Paragraph>
              {translate("about.secondParagraph")}
              <List
                style={{ marginTop: "1%" }}
                grid={{
                  gutter: 16,
                  xs: 1,
                  sm: 1,
                  md: 1,
                  lg: 2,
                  xl: 2,
                  xxl: 2,
                }}
                dataSource={data}
                renderItem={(item) => (
                  <List.Item>
                    <Card title={item.title}>{item.content}</Card>
                  </List.Item>
                )}
              />
            </Typography.Paragraph>
            <Typography.Paragraph>
              {translate("about.thirdParagraph")}
            </Typography.Paragraph>
          </div>
        </Layout.Content>
      </Layout>
    );
  }
}

export default About;

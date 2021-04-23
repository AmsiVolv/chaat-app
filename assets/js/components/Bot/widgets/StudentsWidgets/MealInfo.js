import React from "react";
import { Col, Descriptions, Divider, Row } from "antd";
import Title from "antd/es/typography/Title";
import translate from "../../../helpers/translate";
import { routes } from "../../../helpers/routes";

const options = [
  {
    text: "Žížkov",
    value: "VŠE Žižkov",
    id: 1,
  },
  {
    text: "Jižní Město",
    value: "VŠE Jižní Město",
    id: 2,
  },
];

class MealInfo extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      loading: false,
      area: "",
    };

    this.selectHandler = this.selectHandler.bind(this);
    this.optionMarkup = this.optionMarkup.bind(this);
    this.fetchOpenDays = this.fetchOpenDays.bind(this);
    this.renderListItems = this.renderListItems.bind(this);
  }

  selectHandler = (area) => {
    this.setState({ area: area }, () => {
      this.fetchOpenDays();
    });
  };

  fetchOpenDays = () => {
    fetch(routes.menu.getMenu.route, {
      method: routes.menu.getMenu.method,
      body: JSON.stringify({ areal: this.state.area }),
    })
      .then((r) => r.json())
      .then((data) => {
        this.setState({ data: data });
      });
  };

  resultMarkup = () => {
    if (this.state.data.length > 0) {
      return (
        <div key="meal-info">
          <Divider key={"meal-info divider"} />
          <Row
            key={"meal-info row"}
            align={"middle"}
            justify={"center"}
            style={{ marginTop: "3%" }}
          >
            <Col
              key={"meal-info col"}
              span={24}
              style={{ textAlign: "center" }}
            >
              <Title key={"meal-info title"} level={3}>
                {translate("mealInfo")}
              </Title>
            </Col>
          </Row>
          <Descriptions key={"meal-info"} bordered column={1}>
            {this.renderListItems()}
          </Descriptions>
          <button
            className="learning-option-button"
            onClick={this.props.actionProvider.handleStudentQuestionOptions}
          >
            {translate("back")}
          </button>
        </div>
      );
    }
  };

  renderListItems = () => {
    return this.state.data.map((el) => {
      return (
        <Descriptions.Item key={el.id} label={el.mealName}>
          {el.mealContent}
        </Descriptions.Item>
      );
    });
  };

  optionMarkup = () => {
    const optionsMarkup = options.map((option) => (
      <button
        className="learning-option-button"
        key={option.id}
        onClick={(e) => this.selectHandler(option.value)}
      >
        {option.text}
      </button>
    ));

    return optionsMarkup;
  };

  render() {
    return (
      <div key="menuDiv">
        {this.optionMarkup()}
        {this.resultMarkup()}
      </div>
    );
  }
}

export default MealInfo;

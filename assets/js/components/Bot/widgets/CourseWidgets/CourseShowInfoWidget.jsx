import React from "react";
import { prepareData } from "../../../helpers/courseInfoRender";

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

  componentDidMount() {
    const { pagination } = this.state;
  }

  render() {
    return (
      <div className="col-md-12">
        {Object.entries(this.props.courseInfo).map(([infoKey, infoValue]) =>
          prepareData(infoKey, infoValue)
        )}
      </div>
    );
  }
}

export default CourseShowInfoWidget;

import React from "react";
import { connect } from "react-redux";
import * as actionCreators from "../../actions/conversation";
import { Select } from "antd";

const mapStateToProps = (state) => {
  return state;
};

class GroupSearch extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      searchQuery: "",
      data: [],
    };

    this.handleChange = this.handleChange.bind(this);
    this.groupSearch = this.groupSearch.bind(this);
    this.enterGroupConversation = this.enterGroupConversation.bind(this);
  }

  groupSearch() {
    this.props.groupSearch(this.state.searchQuery).then((json) => {
      this.setState({
        data: json.groupConversationsNames,
      });
    });
  }

  handleChange(value) {
    this.setState({
      searchQuery: value,
    });
    this.groupSearch(value);
  }

  enterGroupConversation = (val) => {
    this.props.enterGroupConversation(val).then(() => {
      this.props.fetchGroupConversations();
    });
  };

  render() {
    return (
      <Select
        showSearch
        style={{ width: "90%", margin: "5%", marginBottom: "unset" }}
        placeholder="Search for group..."
        optionFilterProp="children"
        onChange={this.enterGroupConversation}
        onSearch={this.handleChange}
        filterOption={(input, option) =>
          option.children.toLowerCase().indexOf(input.toLowerCase()) >= 0
        }
      >
        {this.state.data.map((group) => {
          return (
            <Select.Option key={group.id} value={group.id}>
              {group.groupName}
            </Select.Option>
          );
        })}
      </Select>
    );
  }
}

export default connect(mapStateToProps, actionCreators)(GroupSearch);

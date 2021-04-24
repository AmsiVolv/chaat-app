import React from "react";
import { connect } from "react-redux";
import * as actionCreators from "../../actions/conversation";
import { message, Select } from "antd";
import translate from "../helpers/translate";

const mapStateToProps = (state) => {
  return state;
};

class Search extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      searchQuery: "",
      data: [],
      showMessage: true,
    };

    this.handleChange = this.handleChange.bind(this);
    this.userSearch = this.userSearch.bind(this);
    this.createConversation = this.createConversation.bind(this);
  }

  userSearch() {
    this.props.userSearch(this.state.searchQuery).then((json) => {
      this.setState({
        data: json.user,
      });
    });
  }

  handleChange(value) {
    this.setState({
      searchQuery: value,
    });
    this.userSearch(value);
  }

  createConversation = (val) => {
    this.props.createConversation(val).then((json) => {
      if ("conversationId" in json) {
        if ("error" in json.conversationId) {
          message.error(json.conversationId.error);
        } else {
          message.success("Chat byl úspěšně vytvořen!");
        }
      }

      this.props.fetchConversations();
    });
  };

  render() {
    return (
      <Select
        showSearch
        style={{ width: "90%", margin: "5%", marginBottom: "unset" }}
        placeholder={translate("personSearch")}
        optionFilterProp="children"
        onChange={this.createConversation}
        onSearch={this.handleChange}
        filterOption={(input, option) =>
          option.children.toLowerCase().indexOf(input.toLowerCase()) >= 0
        }
      >
        {this.state.data.map((user) => {
          return <Select.Option key={user.id}>{user.username}</Select.Option>;
        })}
      </Select>
    );
  }
}

export default connect(mapStateToProps, actionCreators)(Search);

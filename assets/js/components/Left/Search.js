import React from "react";
import { connect } from "react-redux";
import * as actionCreators from "../../actions/conversation";
// import SearchResultItem from './SearchResultList'
// import {MDBBox, MDBCol, MDBInput} from 'mdbreact'

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
  }

  userSearch(event) {
    event.preventDefault();
    this.props.userSearch(this.state.searchQuery).then((json) => {
      this.setState({
        data: json.user,
      });
    });
  }

  handleChange(event) {
    this.setState({
      searchQuery: event.target.value,
    });
    this.userSearch(event);
  }

  render() {
    return (
      <></>
      // <MDBCol md='12'>
      //     <MDBInput hint='Search for a user...' type='text' containerClass='active-pink active-pink-2 mt-0 mb-3'
      //               onChange={this.handleChange} value={this.state.searchQuery}/>
      //     <MDBBox md='6' id='search-results'>
      //         {this.state.data.map(
      //             user => {
      //                 return (
      //                     <SearchResultItem user={user} key={user.id}/>
      //                 )
      //             }
      //         )}
      //     </MDBBox>
      // </MDBCol>
    );
  }
}

export default connect(mapStateToProps, actionCreators)(Search);

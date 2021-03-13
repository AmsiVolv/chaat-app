import React from "react"
import {MDBBox} from "mdbreact"
import {connect} from "react-redux"
import * as actionCreators from "../../actions/conversation"

const mapStateToProps = (state) => {
    return state;
};

class SearchResultItem extends React.Component {
    constructor(props)
    {
        super(props);
        this.state = {
            userId: ''
        };

        this.createConversation = this.createConversation.bind(this);
    }

    createConversation = () => {
        this.props.createConversation(this.props.user.id).then(() => {
            this.props.fetchConversations()
        });


    }

    render()
    {
        return (
            <MDBBox>
                <div className="media" onClick={this.createConversation}>
                    <img src="https://res.cloudinary.com/mhmd/image/upload/v1564960395/avatar_usae7z.svg" alt="user"
                         width="50" className="rounded-circle"/>
                    <div className="media-body ml-4">
                        <div className="d-flex align-items-center justify-content-between mb-1">
                            <h6 className="mb-0">{this.props.user.username}</h6>
                        </div>
                    </div>
                </div>
            </MDBBox>
        )
    }
}

export default connect(mapStateToProps, actionCreators)(SearchResultItem);

import React from "react";
import { connect } from "react-redux";
import * as actionCreators from "../../actions/conversation";
import Avatar from "antd/es/avatar/avatar";
import { message, Drawer, Button, PageHeader, Tooltip } from "antd";
import { prepareData } from "../helpers/courseInfoRender";

const mapStateToProps = (state) => {
  return state;
};

class RightGroupConversationMessageHelper extends React.Component {
  constructor(props) {
    super(props);
    this.state = { visible: false };

    this.leaveConversation = this.leaveConversation.bind(this);
    this.renderGroupAvatar = this.renderGroupAvatar.bind(this);
    this.renderGroupTitle = this.renderGroupTitle.bind(this);
    this.showDrawer = this.showDrawer.bind(this);
    this.renderDrawerContent = this.renderDrawerContent.bind(this);
    this.onClose = this.onClose.bind(this);
  }

  leaveConversation = () => {
    message.success(
      `Jste úspěšně opustili skupinu ${this.props.group.groupName}`
    );
    this.props.leaveGroupConversation(this.props.group.id);
    this.props.history.push("/");
    this.props.fetchGroupConversations();
  };

  renderGroupAvatar = () => {
    if (typeof this.props.group !== "undefined") {
      return (
        <Avatar
          size={{ xs: 24, sm: 32, md: 40 }}
          style={{ backgroundColor: this.props.group.groupColor }}
        >
          {this.props.group.groupName.slice(1).toUpperCase()}
        </Avatar>
      );
    }
  };

  renderDrawerContent = () => {
    if (this.state.visible && this.props.group.courseInfo !== undefined) {
      return (
        <div className="col-md-12">
          <Avatar.Group maxCount={5}>
            {this.props.group.participants.map((participant) => {
              const capitalLetter = participant.username
                .charAt(0)
                .toUpperCase();

              return (
                <Tooltip
                  key={participant.id}
                  title={participant.username}
                  placement="top"
                >
                  <Avatar
                    key={participant.id}
                    size={{ xs: 24, sm: 32, md: 40, lg: 64, xl: 80, xxl: 100 }}
                    style={{ backgroundColor: "#" + participant.iconColor }}
                  >
                    {capitalLetter}
                  </Avatar>
                </Tooltip>
              );
            })}
          </Avatar.Group>
          {Object.entries(
            this.props.group.courseInfo
          ).map(([infoKey, infoValue]) => prepareData(infoKey, infoValue))}
        </div>
      );
    }
  };

  renderGroupTitle = () => {
    if (typeof this.props.group !== "undefined") {
      return this.props.group.groupName.slice(1).toUpperCase();
    }
  };

  showDrawer = () => {
    if (this.props.group.courseInfo === undefined) {
      this.props.fetchGroupInfo(this.props.group.id);
    }
    this.setState({
      visible: true,
    });
  };

  onClose = () => {
    this.setState({
      visible: false,
    });
  };

  render() {
    return (
      <>
        <PageHeader
          ghost={false}
          onBack={() => this.props.history.push("/")}
          title={this.renderGroupAvatar()}
          subTitle={this.renderGroupTitle()}
          extra={[
            <Button key="2" onClick={this.leaveConversation}>
              Leave conversation
            </Button>,
            <Button key="1" onClick={this.showDrawer} type="primary">
              Info
            </Button>,
          ]}
        ></PageHeader>
        <Drawer
          width={window.innerWidth <= 768 ? '100%' : '75%'}
          title={`Informace o předmětu ${this.renderGroupTitle()}`}
          placement="right"
          getContainer={false}
          closable={true}
          onClose={this.onClose}
          visible={this.state.visible}
        >
          {this.renderDrawerContent()}
        </Drawer>
      </>
    );
  }
}

export default connect(
  mapStateToProps,
  actionCreators
)(RightGroupConversationMessageHelper);

import React from "react";
import { Route, Switch, withRouter } from "react-router-dom";

import Left from "./Left/Left";
import Right from "./Right/Right";
import Blank from "./Right/Blank";
import NavbarPage from "./Header/NavbarPage";
import Bot from "./Bot/Bot";

class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      location: "/",
    };
  }
  componentDidMount() {
    this.unlistenHistory = this.props.history.listen((location, action) => {
      this.setState((_) => ({ location: location.pathname }));
    });
  }
  componentWillUnmount() {
    this.unlistenHistory();
  }

  render() {
    const { location } = this.state;
    return (
      <>
        <div className="row">
          <NavbarPage />
        </div>
        <div className="row overflow-hidden mt-4 w-75 mx-auto rounded">
          {location !== "/bot" && <Left />}
          <Switch>
            <Route path="/" component={Blank} exact />
            <Route
              path="/conversation/:id"
              render={(props) => (
                <Right {...props} key={props.match.params.id} />
              )}
            />
            <Route path="/" component={Bot} />
          </Switch>
        </div>
      </>
    );
  }
}

export default withRouter(App);

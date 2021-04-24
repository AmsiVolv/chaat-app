import ReactDOM from "react-dom";
import React from "react";
import store from "./store";
import { Provider } from "react-redux";
import { MemoryRouter } from "react-router-dom";
import "react-app-polyfill/ie9";
import "react-app-polyfill/ie11";
import "react-app-polyfill/stable";
import * as actionCreators from "./actions/conversation";

import App from "./components/App";

store.dispatch(
  actionCreators.setUsername(document.querySelector("#app").dataset.username)
);

ReactDOM.render(
  <Provider store={store}>
    <MemoryRouter>
      <App />
    </MemoryRouter>
  </Provider>,
  document.getElementById("app")
);

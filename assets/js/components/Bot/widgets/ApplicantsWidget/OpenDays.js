import React, { useEffect } from "react";
import translate from "../../../helpers/translate";

const OpenDays = (props) => {
  const { setState, actionProvider } = props;

  const fetchOpenDays = () => {
    if (props.isFetchingOpenDays) {
      useEffect(() => {
        setState((state) => ({ ...state, isFetchingOpenDays: false }));
      });

      fetch("/openDays/get", {
        method: "POST",
      })
        .then((r) => r.json())
        .then((data) => {
          setState((state) => ({ ...state, openDays: data }));
        });
    }
  };

  function isURL(str) {
    const pattern = new RegExp(
      "^(https?:\\/\\/)?" + // protocol
        "((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|" + // domain name
        "((\\d{1,3}\\.){3}\\d{1,3}))" + // OR ip (v4) address
        "(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*" + // port and path
        "(\\?[;&a-z\\d%_.~+=-]*)?" + // query string
        "(\\#[-a-z\\d_]*)?$",
      "i"
    ); // fragment locator
    return !!pattern.test(str);
  }

  function renderTableHead() {
    return (
      <thead className="thead-dark">
        <tr>
          {Object.keys(props.openDays[0]).map((theadKey) => {
            return (
              <th scope="col" key={theadKey}>
                {translate(theadKey)}
              </th>
            );
          })}
        </tr>
      </thead>
    );
  }

  function renderTableBody() {
    return props.openDays.map((element, index) => {
      return (
        <tr key={index}>
          {Object.entries(element).map(([key, value]) => {
            if (value !== null && isURL(value)) {
              return (
                <td key={value + key} className="text-center">
                  <a className="link-info" href={value} title={value}>
                    Link
                  </a>
                </td>
              );
            }
            return <td key={value + key}>{value}</td>;
          })}
        </tr>
      );
    });
  }

  const openDaysMarkup = () => {
    fetchOpenDays();

    if (props.openDays.length > 0) {
      return (
        <table className="table">
          {renderTableHead()}
          <tbody>{renderTableBody()}</tbody>
        </table>
      );
    }
  };

  return <div className="learning-options-container">{openDaysMarkup()}</div>;
};

export default OpenDays;

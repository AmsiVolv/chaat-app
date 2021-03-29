import React from "react";

const CourseShowInfoWidget = (props) => {
  function prepareData(key, value, index) {
    if (
      value instanceof Array ||
      value instanceof Object ||
      value === "undefined"
    ) {
      if (value instanceof Array && key !== "keys" && value.length !== 0) {
        return (
          <div className="com-md-12 mt-2 mb-2" key={key}>
            <p className="text-center bg-info text-white w-100">{key}</p>
            <table className="table table-bordered table-hover">
              {renderTableHead(value[0])}
              <tbody>{renderTableBody(value)}</tbody>
            </table>
          </div>
        );
      }
      if (value instanceof Object && key === "course") {
        return Object.entries(value).map(([key, value], infoIndex) =>
          prepareData(key, value, infoIndex)
        );
      }
      return;
    }

    return (
      <div className="row" key={index}>
        <div className="col-md-3">
          <p>{key}</p>
        </div>
        <div className="col-md-9">
          <p>{value}</p>
        </div>
      </div>
    );
  }

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

  function isLibraryUrl(str) {
    const libraryBase = "library.vse.cz";

    return str.includes(libraryBase);
  }

  function getCitationUrl(str) {
    const bookID = str.substr(str.indexOf("?") + 1);

    return `https://katalog.vse.cz/Record/${bookID}/Cite?layout=lightbox`;
  }

  function isEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  }

  function renderTableBody(value) {
    return value.map((element, index) => {
      return (
        <tr key={index}>
          {Object.entries(element).map(([key, value]) => {
            if (key !== "keys") {
              if (isURL(value)) {
                if (isLibraryUrl(value)) {
                  const citationUrl = getCitationUrl(value);
                  return (
                    <td key={value + key} className="text-center">
                      <a className="badge badge-pill badge-info" href={value}>
                        Link
                      </a>
                      <a
                        target="_blank"
                        className="badge badge-pill badge-primary"
                        href={citationUrl}
                      >
                        Citation
                      </a>
                    </td>
                  );
                } else {
                  return (
                    <td key={value + key} className="text-center">
                      <a className="badge badge-pill badge-info" href={value}>
                        Link
                      </a>
                    </td>
                  );
                }
              } else if (isEmail(value)) {
                return (
                  <td key={value + key} className="text-center">
                    <a
                      className="badge badge-pill badge-info"
                      href={"mailto:" + value}
                    >
                      Write email
                    </a>
                  </td>
                );
              } else {
                return <td key={value + key}>{value}</td>;
              }
            }
          })}
        </tr>
      );
    });
  }

  function renderTableHead(value) {
    return (
      <thead>
        <tr>
          {Object.keys(value).map((theadKey) => {
            if (theadKey !== "keys") {
              return (
                <th scope="col" key={theadKey}>
                  {theadKey}
                </th>
              );
            }
          })}
        </tr>
      </thead>
    );
  }

  return (
    <div className="col-md-12">
      <div className="bg-info text-white">
        <div className="com-md-12">
          <p className="text-center w-100">Course info</p>
        </div>
      </div>
      {Object.entries(props.courseInfo).map(([infoKey, infoValue], infoIndex) =>
        prepareData(infoKey, infoValue, infoIndex)
      )}
    </div>
  );
};

export default CourseShowInfoWidget;

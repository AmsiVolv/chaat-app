import React, { useEffect } from "react";
import translate from "../../../helpers/translate";
import { Link } from "react-router-dom";

const PreparatoryCourses = (props) => {
  const { setState, actionProvider } = props;

  const fetchPreparatoryCourses = () => {
    if (props.isFetchingPreparatoryCourses) {
      useEffect(() => {
        setState((state) => ({
          ...state,
          isFetchingPreparatoryCourses: false,
        }));
      });

      fetch("/preparatoryCourse/get", {
        method: "POST",
      })
        .then((r) => r.json())
        .then((data) => {
          setState((state) => ({ ...state, preparatoryCourses: data }));
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
          <th scope="col" key='subjectTitle'>
            {translate('subjectTitle')}
          </th>
          <th scope="col" key='preparatoryCourseScope'>
            {translate('preparatoryCourseScope')}
          </th>
          <th scope="col" key='preparatoryCourseDate'>
            {translate('preparatoryCourseDate')}
          </th>
          <th scope="col" key='preparatoryCourseContactPersonName'>
            {translate('preparatoryCourseContactPersonName')}
          </th>
        </tr>
      </thead>
    );
  }

  function renderTableBody() {
    return props.preparatoryCourses.map((element, index) => {
      const {
        id,
        subjectTitle,
        subjectLink,
        preparatoryCourseScope,
        preparatoryCourseDate,
        preparatoryCourseContactPersonName,
        preparatoryCourseContactPersonEmail,
      } = element;
      return (
        <tr key={index}>
          <td key={id + subjectTitle}>
            <Link className="link-info" target="_blank" to={subjectLink}>
              {subjectTitle}
            </Link>
          </td>
          <td key={id + preparatoryCourseScope}>{preparatoryCourseScope}</td>
          <td key={id + preparatoryCourseDate}>{preparatoryCourseDate}</td>
          <td key={id + preparatoryCourseContactPersonEmail}>
            <Link
              className="link-info"
              target="_blank"
              to={`mailto:${preparatoryCourseContactPersonEmail}`}
            >
              {preparatoryCourseContactPersonName}
            </Link>
          </td>
        </tr>
      );
    });
  }

  const preparatoryCoursesMarkup = () => {
    fetchPreparatoryCourses();

    if (props.preparatoryCourses.length > 0) {
      return (
        <table className="table">
          {renderTableHead()}
          <tbody>{renderTableBody()}</tbody>
        </table>
      );
    }
  };

  return (
    <div className="learning-options-container">
      {preparatoryCoursesMarkup()}
    </div>
  );
};

export default PreparatoryCourses;

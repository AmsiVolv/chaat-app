import React, {useEffect} from "react";
import translate from "../../../helpers/translate";

const PreparatoryCourses = (props) => {
    const { setState, actionProvider } = props;

    const fetchPreparatoryCourses = () => {
        if (props.isFetchingPreparatoryCourses) {
            useEffect(() => {
                setState((state) => ({ ...state, isFetchingPreparatoryCourses: false }));
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

    function isURL(str)
    {
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

    function renderTableHead()
    {
        return (
        <thead className="thead-dark">
        <tr>
          {Object.keys(props.preparatoryCourses[0]).map((theadKey) => {
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

    function renderTableBody()
    {
        return props.preparatoryCourses.map((element, index) => {
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

    return <div className="learning-options-container">{preparatoryCoursesMarkup()}</div>;
};

export default PreparatoryCourses;

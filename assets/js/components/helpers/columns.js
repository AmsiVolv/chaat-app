import translate from "./translate";
import React from "react";
import { getCitationUrl } from "./otherHelpers";
import { DownloadOutlined } from "@ant-design/icons";
import { Button } from "antd";

export const courseSchedulingColumns = [
  {
    title: translate("day"),
    dataIndex: "day",
  },
  {
    title: translate("time"),
    dataIndex: "time",
  },
  {
    title: translate("room"),
    dataIndex: "room",
  },
  {
    title: translate("eventType"),
    dataIndex: "eventType",
  },
  {
    title: translate("capacity"),
    dataIndex: "capacity",
  },
];

export const teacherColumns = [
  {
    title: translate("name"),
    dataIndex: "name",
    render: (text, data) => (
      <a
        className="ant-anchor-link text-left"
        href={`mailto: ${data.teacherUri}`}
      >
        {text}
      </a>
    ),
  },
  {
    title: translate("officeNumber"),
    dataIndex: "officeNumber",
  },
  {
    title: translate("phoneNumber"),
    dataIndex: "phoneNumber",
  },
];

export const readingColumns = [
  {
    title: translate("readingType"),
    dataIndex: "readingType",
  },
  {
    title: translate("Author"),
    dataIndex: "Author",
  },
  {
    title: translate("title"),
    dataIndex: "title",
    render: (text, data) => (
      <a
        className="ant-anchor-link text-left"
        target="_blank"
        href={data.libraryLink}
      >
        {text}
      </a>
    ),
  },
  {
    title: translate("ISBN"),
    dataIndex: "ISBN",
  },
  {
    title: translate("libraryLink"),
    dataIndex: "libraryLink",
    render: (text, data) => (
      <>
        <a
          className="ant-anchor-link text-left"
          target="_blank"
          href={getCitationUrl(data.libraryLink)}
        >
          Citace
        </a>
      </>
    ),
  },
];

export const preparatoryCourseColumns = [
  {
    title: translate("subjectTitle"),
    dataIndex: "subjectTitle",
    render: (text, data) => (
      <a
        className="ant-anchor-link text-left"
        target="_blank"
        href={data.subjectLink}
      >
        {text}
      </a>
    ),
  },
  {
    title: translate("preparatoryCourseScope"),
    dataIndex: "preparatoryCourseScope",
  },
  {
    title: translate("preparatoryCourseDate"),
    dataIndex: "preparatoryCourseDate",
  },
  {
    title: translate("preparatoryCoursePrice"),
    dataIndex: "preparatoryCoursePrice",
  },
  {
    title: translate("preparatoryCourseContactPersonName"),
    dataIndex: "preparatoryCourseContactPersonName",
    render: (text, data) => (
      <a
        className="ant-anchor-link text-left"
        href={`mailto: ${data.preparatoryCourseContactPersonEmail}`}
      >
        {text}
      </a>
    ),
  },
];

export const openDaysColumns = [
  {
    title: translate("openDaysDescription"),
    dataIndex: "openDaysDescription",
  },
  {
    title: translate("openDayDate"),
    dataIndex: "openDayDate",
  },
  {
    title: translate("link"),
    dataIndex: "link",
    render: (text, data) => (
      <a className="ant-anchor-link text-left" target="_blank" href={data.link}>
        Odkáz na událost
      </a>
    ),
  },
];

export const studyProgramsColumns = [
  {
    title: translate("studyProgramTitle"),
    dataIndex: "studyProgramTitle",
    sorter: (a, b) => a.studyProgramTitle.length < b.studyProgramTitle.length,
    render: (text, data) => (
      <a
        className="ant-anchor-link text-center"
        target="_blank"
        href={data.link}
      >
        {text}
      </a>
    ),
  },
  {
    title: translate("facultyName"),
    dataIndex: "facultyName",
    sorter: (a, b) => a.facultyName.length < b.facultyName.length,
    filters: [
      {
        text: "Fakulta financí a účetnictví",
        value: "Fakulta financí a účetnictví",
      },
      {
        text: "Fakulta mezinárodních vztahů",
        value: "Fakulta mezinárodních vztahů",
      },
      {
        text: "Fakulta podnikohospodářská",
        value: "Fakulta podnikohospodářská",
      },
      {
        text: "Fakulta informatiky a statistiky",
        value: "Fakulta informatiky a statistiky",
      },
      { text: "Národohospodářská fakulta", value: "Národohospodářská fakulta" },
      { text: "Fakulta managementu", value: "Fakulta managementu" },
    ],
    render: (text, data) => (
      <a
        className="ant-anchor-link text-center"
        target="_blank"
        href={data.webLink}
      >
        {text}
      </a>
    ),
    onFilter: (value, data) => data.facultyName.indexOf(value) === 0,
  },
  {
    title: translate("studyProgramCapacity"),
    dataIndex: "studyProgramCapacity",
    sorter: (a, b) => a.studyProgramCapacity - b.studyProgramCapacity,
  },
  {
    title: translate("language"),
    dataIndex: "language",
    filters: [
      { text: "čeština", value: "čeština" },
      { text: "angličtina", value: "angličtina" },
      { text: "ruština", value: "ruština" },
    ],
    onFilter: (value, data) => data.language.indexOf(value) === 0,
  },
  {
    title: translate("form"),
    dataIndex: "form",
    filters: [
      { text: "prezenční", value: "prezenční" },
      { text: "kombinovaná", value: "kombinovaná" },
    ],
    onFilter: (value, data) => data.form.indexOf(value) === 0,
  },
];

export const studyAdmissions = [
  {
    title: translate("facultyName"),
    dataIndex: "facultyName",
    render: (text, data) => (
      <a
        className="ant-anchor-link text-center"
        target="_blank"
        href={data.webLink}
      >
        {text}
      </a>
    ),
  },
  {
    title: translate("studyAdmissionLink"),
    dataIndex: "studyAdmissionLink",
    render: (text, data) => (
      <a
        key={text}
        className="ant-anchor-link text-center"
        target="_blank"
        href={text}
      >
        <Button
          key={text}
          type="primary"
          shape="round"
          icon={<DownloadOutlined />}
        />
      </a>
    ),
  },
];

export const trialTestsColumns = [
  {
    title: translate("trialTestTitle"),
    dataIndex: "trialTestTitle",
    filters: [
      { text: "Angličtina", value: "Angličtina" },
      { text: "Arts", value: "Arts" },
      { text: "Francouzština", value: "Francouzština" },
      { text: "Italština", value: "Italština" },
      { text: "Němčina", value: "Němčina" },
      { text: "Ruština", value: "Ruština" },
      { text: "Španělština", value: "Španělština" },
      { text: "Matematika", value: "Matematika" },
    ],
    onFilter: (value, data) => data.keyword.indexOf(value) === 0,
  },
  {
    title: translate("trialTestLink"),
    dataIndex: "trialTestLink",
    render: (text, data) => (
      <a
        key={text}
        className="ant-anchor-link text-center"
        target="_blank"
        href={text}
      >
        <Button
          key={text}
          type="primary"
          shape="round"
          icon={<DownloadOutlined />}
        />
      </a>
    ),
  },
];

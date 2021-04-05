import React from "react";

import { createChatBotMessage } from "react-chatbot-kit";
import QuestionOptions from "../widgets/QuestionOptions";
import CourseSelectInfoWidget from "../widgets/CourseWidgets/CourseSelectInfoWidget";
import CourseShowInfoWidget from "../widgets/CourseWidgets/CourseShowInfoWidget";
import Courses from "../widgets/Courses";
import CourseInfoChoice from "../widgets/CourseWidgets/CourseInfoChoice";
import ApplicantsChoiceList from "../widgets/ApplicantsWidget/ApplicantsChoiceList";
import StudyApplication from "../widgets/ApplicantsWidget/StudyApplication";
import PreparatoryCourses from "../widgets/ApplicantsWidget/PreparatoryCourses";
import StudyPrograms from "../widgets/ApplicantsWidget/StudyPrograms";
import OpenDays from "../widgets/ApplicantsWidget/OpenDays";
import StudyAdmisions from "../widgets/ApplicantsWidget/StudyAdmisions";

const config = {
  botName: "In-VSE Bot",
  initialMessages: [
    createChatBotMessage("I'm here to help. What do you want to find?"),
    createChatBotMessage(
      "Here's a quick overview over what I need to function. ask me about the different parts to dive deeper.",
      {
        withAvatar: true,
        delay: 500,
        widget: "QuestionOptions",
      }
    ),
  ],
  customStyles: {
    botMessageBox: {
      backgroundColor: "#376B7E",
    },
    chatButton: {
      backgroundColor: "#376B7E",
    },
  },
  state: {
    initChoice: "",
    courses: [],
    courseInfo: {},
    courseFilterOptions: [],
    courseSelectedFilters: [],
    openDays: [],
    preparatoryCourses: [],
    isFetchingCourseSelect: true,
    isFetchingCourseInfo: true,
    isFetchingOpenDays: true,
    isFetchingPreparatoryCourses: true,
    course: "",
    applicantsChoice: "",
  },
  widgets: [
    {
      widgetName: "QuestionOptions",
      widgetFunc: (props) => <QuestionOptions {...props} />,
      mapStateToProps: ["initChoice"],
    },
    {
      widgetName: "courseChoice",
      widgetFunc: (props) => <Courses {...props} />,
      mapStateToProps: ["courses", "course"],
    },
    {
      widgetName: "applicantsChoise",
      widgetFunc: (props) => <ApplicantsChoiceList {...props} />,
      mapStateToProps: ["applicantsChoice"],
    },
    {
      widgetName: "studyPrograms",
      widgetFunc: (props) => <StudyPrograms {...props} />,
      mapStateToProps: ["applicantsChoice"],
    },
    {
      widgetName: "openDays",
      widgetFunc: (props) => <OpenDays {...props} />,
      mapStateToProps: ["openDays", "isFetchingOpenDays"],
    },
    {
      widgetName: "studyAdmissions",
      widgetFunc: (props) => <StudyAdmisions {...props} />,
      mapStateToProps: ["openDays", "isFetchingOpenDays"],
    },
    {
      widgetName: "studyApplication",
      widgetFunc: (props) => <StudyApplication {...props} />,
      mapStateToProps: ["applicantsChoice"],
    },
    {
      widgetName: "preparatoryCourses",
      widgetFunc: (props) => <PreparatoryCourses {...props} />,
      mapStateToProps: ["preparatoryCourses", "isFetchingPreparatoryCourses"],
    },
    {
      widgetName: "CourseSelectInfoWidget",
      widgetFunc: (props) => <CourseSelectInfoWidget {...props} />,
      mapStateToProps: ["course", "courseInfo"],
    },
    {
      widgetName: "CourseShowInfoWidget",
      widgetFunc: (props) => <CourseShowInfoWidget {...props} />,
      mapStateToProps: ["course", "courseInfo", "isFetchingCourseInfo"],
    },
    {
      widgetName: "courseInfoChoice",
      widgetFunc: (props) => <CourseInfoChoice {...props} />,
      mapStateToProps: [
        "course",
        "courseInfo",
        "courseFilterOptions",
        "isFetchingCourseSelect",
        "courseSelectedFilters",
      ],
    },
  ],
};

export default config;

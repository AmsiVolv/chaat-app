export const routes = {
  conversation: {
    route: "/conversations/",
    create: {
      route: "/conversations/create",
      method: "POST",
    },
    clear: {
      route: "/conversations/clear",
      method: "POST",
    },
    delete: {
      route: "/conversations/delete",
      method: "POST",
    },
  },
  groupConversations: {
    route: "/groupConversations/",
    leave: {
      route: "/groupConversations/leave",
      method: "POST",
    },
    enter: {
      route: "/groupConversations/enter",
      method: "POST",
    },
    find: {
      route: "/groupConversations/find",
      method: "POST",
    },
    getCourseInfo: {
      route: "/groupConversations/getCourseInfo",
      method: "POST",
    },
  },
  message: {
    route: "/messages/",
    addMessage: {
      route: "/messages/",
      method: "POST",
    },
  },
  groupMessages: {
    route: "/groupMessages/",
    addMessage: {
      route: "/groupMessages/",
      method: "POST",
    },
  },
  users: {
    route: "/api/users",
    method: "POST",
  },
  course: {
    getFilterParams: {
      route: "/course/getFilterParams",
      method: "POST",
    },
    getCourseByName: {
      route: "/course/getCourseByName",
      method: "POST",
    },
    get: {
      route: "/course/get",
      method: "POST",
    },
  },
  faculty: {
    getAll: {
      route: "/faculty/getAll",
      method: "GET",
    },
  },
  menu: {
    getMenu: {
      route: "/menu/get",
      method: "POST",
    },
  },
  teachers: {
    getTeacherInfoById: {
      route: "/teachers/getTeacherInfoById",
      method: "POST",
    },
    getTeacherInfoByName: {
      route: "/teachers/getTeacherByName",
      method: "POST",
    },
  },
  chatbot: {
    saveMessages: {
      route: "/chatbot/saveMessages",
      method: "POST",
    },
  },
  feedback: {
    send: {
      route: "/feedback/save",
      method: "POST",
    },
  },
};

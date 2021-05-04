import {
  GET_CONVERSATIONS,
  RECIEVE_CONVERSATIONS,
  GET_MESSAGES,
  RECIEVE_MESSAGES,
  ADD_MESSAGE,
  SET_GROUP_CONVERSATION_ID,
  SET_HUBURL,
  SET_USERNAME,
  SET_LAST_MESSAGE,
  SET_SEARCH_USERNAME,
  ADD_SEARCH_USERNAME,
  SET_CONVERSATION_ID,
  RECIEVE_GROUP_CONVERSATIONS,
  SET_LAST_GROUP_MESSAGE,
  GET_GROUP_CONVERSATIONS,
  GET_GROUP_MESSAGES,
  RECIEVE_GROUP_MESSAGES,
  ADD_GROUP_MESSAGE,
  SET_SEARCH_GROUP,
  ADD_SEARCH_GROUP_NAME,
  GET_COURSE_INFO,
  SAVE_FEEDBACK,
} from "../constants/actionTypes";
import { routes } from "../components/helpers/routes";

export const requestConversations = () => ({
  type: GET_CONVERSATIONS,
});

export const requestGroupConversations = () => ({
  type: GET_GROUP_CONVERSATIONS,
});

export const receiveConversations = (json) => {
  return {
    type: RECIEVE_CONVERSATIONS,
    items: json,
  };
};

export const receiveGroupConversations = (json) => {
  return {
    type: RECIEVE_GROUP_CONVERSATIONS,
    groupConversations: json,
  };
};

export const requestMessages = (id) => ({
  type: GET_MESSAGES,
  conversationId: id,
});

export const requestGroupMessages = (id) => ({
  type: GET_GROUP_MESSAGES,
  groupId: id,
});

export const receiveMessages = (json, id) => {
  return {
    type: RECIEVE_MESSAGES,
    messages: json,
    conversationId: id,
  };
};

export const receiveGroupMessages = (json, id) => {
  return {
    type: RECIEVE_GROUP_MESSAGES,
    groupMessages: json,
    groupId: id,
  };
};

export const postMessage = (json, id) => {
  return {
    type: ADD_MESSAGE,
    message: json,
    conversationId: id,
  };
};

export const postGroupMessage = (json, id) => {
  return {
    type: ADD_GROUP_MESSAGE,
    groupMessage: json,
    groupConversationId: id,
  };
};

export const setLastMessage = (message, conversationId) => {
  return {
    type: SET_LAST_MESSAGE,
    message,
    conversationId,
  };
};

export const setLastGroupMessage = (groupMessage, groupConversationId) => {
  return {
    type: SET_LAST_GROUP_MESSAGE,
    groupMessage,
    groupConversationId,
  };
};

export const setSearchUsername = (username) => {
  return {
    type: SET_SEARCH_USERNAME,
    username,
  };
};

export const setSearchGroupConversation = (groupConversationGroupName) => {
  return {
    type: SET_SEARCH_GROUP,
    groupConversationGroupName,
  };
};

export const postUsernameFromSearch = (json) => {
  return {
    type: ADD_SEARCH_USERNAME,
    user: json,
  };
};

export const postGroupConversationFromSearch = (json) => {
  return {
    type: ADD_SEARCH_GROUP_NAME,
    groupConversationsNames: json,
  };
};

export const getCourseInfo = (json) => {
  return {
    type: GET_COURSE_INFO,
    courseInfo: json,
  };
};

export const saveFeedback = (json) => {
  return {
    type: SAVE_FEEDBACK,
    feedback: json,
  };
};

export const setHuburl = (url) => {
  return {
    type: SET_HUBURL,
    url,
  };
};

export const setUsername = (username) => {
  return {
    type: SET_USERNAME,
    username,
  };
};

export const setConversationId = (conversationId) => {
  return {
    type: SET_CONVERSATION_ID,
    conversationId,
  };
};

export const setGroupConversationId = (groupConversation) => {
  return {
    type: SET_GROUP_CONVERSATION_ID,
    groupConversation,
  };
};

export const fetchConversations = () => (dispatch) => {
  dispatch(requestConversations());
  return fetch(routes.conversation.route)
    .then((response) => {
      // TODO: set the HUB URL right here
      const hubUrl = response.headers
        .get("Link")
        .match(/<([^>]+)>;\s+rel=(?:mercure|"[^"]*mercure[^"]*")/)[1];
      dispatch(setHuburl(hubUrl));
      return response.json();
    })
    .then((json) => {
      return dispatch(receiveConversations(json));
    });
};

export const fetchGroupConversations = () => (dispatch) => {
  dispatch(requestGroupConversations());
  return fetch(routes.groupConversations.route)
    .then((response) => {
      const hubUrl = response.headers
        .get("Link")
        .match(/<([^>]+)>;\s+rel=(?:mercure|"[^"]*mercure[^"]*")/)[1];
      dispatch(setHuburl(hubUrl));
      return response.json();
    })
    .then((json) => {
      return dispatch(receiveGroupConversations(json));
    });
};

export const fetchMessages = (id) => (dispatch) => {
  dispatch(requestMessages(id));
  return fetch(routes.message.route + id)
    .then((response) => response.json())
    .then((json) => {
      return dispatch(receiveMessages(json, id));
    });
};

export const fetchGroupMessages = (id) => (dispatch) => {
  dispatch(requestGroupMessages(id));
  return fetch(routes.groupMessages.route + id)
    .then((response) => response.json())
    .then((json) => {
      return dispatch(receiveGroupMessages(json, id));
    });
};

export const addMessage = (content, conversationId) => (dispatch) => {
  let formData = new FormData();
  formData.append("content", content);
  return fetch(routes.message.addMessage.route + conversationId, {
    method: routes.message.addMessage.method,
    body: formData,
  })
    .then((response) => response.json())
    .then((json) => {
      dispatch(setLastMessage(json, conversationId));
      return dispatch(postMessage(json, conversationId));
    });
};

export const addMessageToGroupConversation = (content, conversationId) => (
  dispatch
) => {
  let formData = new FormData();
  formData.append("content", content);
  return fetch(routes.groupMessages.addMessage.route + conversationId, {
    method: routes.groupMessages.addMessage.method,
    body: formData,
  })
    .then((response) => response.json())
    .then((json) => {
      dispatch(setLastGroupMessage(json, conversationId));
      return dispatch(postGroupMessage(json, conversationId));
    });
};

export const userSearch = (username) => (dispatch) => {
  let formData = new FormData();
  formData.append("username", username);
  return fetch(routes.users.route, {
    method: routes.users.method,
    body: formData,
  })
    .then((response) => response.json())
    .then((json) => {
      dispatch(setSearchUsername(json));
      return dispatch(postUsernameFromSearch(json));
    });
};

export const createConversation = (userId) => (dispatch) => {
  let formData = new FormData();
  formData.append("userId", userId);
  return fetch(routes.conversation.create.route, {
    method: routes.conversation.create.method,
    body: formData,
  })
    .then((response) => response.json())
    .then((json) => {
      return dispatch(setConversationId(json));
    });
};

export const clearConversation = (conversationId) => (dispatch) => {
  fetch(routes.conversation.clear.route, {
    method: routes.conversation.clear.method,
    body: JSON.stringify({ conversationId: conversationId }),
  });
};

export const deleteConversation = (conversationId) => (dispatch) => {
  fetch(routes.conversation.delete.route, {
    method: routes.conversation.delete.method,
    body: JSON.stringify({ conversationId: conversationId }),
  });
};

export const leaveGroupConversation = (groupConversationId) => () => {
  fetch(routes.groupConversations.leave.route, {
    method: routes.groupConversations.leave.method,
    body: JSON.stringify({ groupConversationId: groupConversationId }),
  });
};

export const enterGroupConversation = (groupConversationId) => (dispatch) => {
  return fetch(routes.groupConversations.enter.route, {
    method: routes.groupConversations.enter.method,
    body: JSON.stringify({ groupConversationId: groupConversationId }),
  })
    .then((response) => response.json())
    .then((json) => {
      return dispatch(setGroupConversationId(json));
    });
};

export const groupSearch = (groupConversationGroupName) => (dispatch) => {
  return fetch(routes.groupConversations.find.route, {
    method: routes.groupConversations.find.method,
    body: JSON.stringify({
      groupConversationGroupName: groupConversationGroupName,
    }),
  })
    .then((response) => response.json())
    .then((json) => {
      dispatch(setSearchGroupConversation(json));
      return dispatch(postGroupConversationFromSearch(json));
    });
};

export const fetchGroupInfo = (groupConversationId) => (dispatch) => {
  fetch(routes.groupConversations.getCourseInfo.route, {
    method: routes.groupConversations.getCourseInfo.method,
    body: JSON.stringify({ groupConversationId: groupConversationId }),
  })
    .then((response) => response.json())
    .then((json) => {
      return dispatch(getCourseInfo(json));
    });
};

export const sendFeedback = (feedback) => (dispatch) => {
  fetch(routes.feedback.send.route, {
    method: routes.feedback.send.method,
    body: JSON.stringify({ feedback: feedback }),
  })
    .then((response) => response.json())
    .then((json) => {
      return dispatch(saveFeedback(json));
    });
};

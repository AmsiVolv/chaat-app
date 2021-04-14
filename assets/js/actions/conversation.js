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
} from "../constants/actionTypes";

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
    conversationId: id,
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

export const setGroupConversationId = (groupConversationId) => {
  return {
    type: SET_GROUP_CONVERSATION_ID,
    groupConversationId,
  };
};

export const fetchConversations = () => (dispatch) => {
  dispatch(requestConversations());
  return fetch(`/conversations/`)
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
  return fetch(`/groupConversations/`)
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
  return fetch(`/messages/${id}`)
    .then((response) => response.json())
    .then((json) => {
      return dispatch(receiveMessages(json, id));
    });
};

export const fetchGroupMessages = (id) => (dispatch) => {
  dispatch(requestGroupMessages(id));
  return fetch(`/groupMessages/${id}`)
    .then((response) => response.json())
    .then((json) => {
      return dispatch(receiveGroupMessages(json, id));
    });
};

export const addMessage = (content, conversationId) => (dispatch) => {
  let formData = new FormData();
  formData.append("content", content);
  return fetch(`/messages/${conversationId}`, {
    method: "POST",
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
  return fetch(`/groupMessages/${conversationId}`, {
    method: "POST",
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
  return fetch("/api/users", {
    method: "POST",
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
  return fetch("/conversations/create", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((json) => {
      return dispatch(setConversationId(json.id));
    });
};

export const clearConversation = (conversationId) => (dispatch) => {
  fetch("/conversations/clear", {
    method: "POST",
    body: JSON.stringify({ conversationId: conversationId }),
  });
};

export const deleteConversation = (conversationId) => (dispatch) => {
  fetch("/conversations/delete", {
    method: "POST",
    body: JSON.stringify({ conversationId: conversationId }),
  });
};

export const leaveGroupConversation = (groupConversationId) => () => {
  fetch("/groupConversations/leave", {
    method: "POST",
    body: JSON.stringify({ groupConversationId: groupConversationId }),
  });
};

export const enterGroupConversation = (groupConversationId) => (dispatch) => {
  return fetch("/groupConversations/enter", {
    method: "POST",
    body: JSON.stringify({ groupConversationId: groupConversationId }),
  })
    .then((response) => response.json())
    .then((json) => {
      return dispatch(setGroupConversationId(json.id));
    });
};

export const groupSearch = (groupConversationGroupName) => (dispatch) => {
  return fetch("/groupConversations/find", {
    method: "POST",
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

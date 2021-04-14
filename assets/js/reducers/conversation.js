import {
  GET_CONVERSATIONS,
  RECIEVE_CONVERSATIONS,
  GET_MESSAGES,
  RECIEVE_MESSAGES,
  SET_GROUP_CONVERSATION_ID,
  ADD_MESSAGE,
  SET_HUBURL,
  SET_USERNAME,
  SET_LAST_MESSAGE,
  SET_LAST_GROUP_MESSAGE,
  SET_CONVERSATION_ID,
  GET_GROUP_CONVERSATIONS,
  RECIEVE_GROUP_CONVERSATIONS,
  GET_GROUP_MESSAGES,
  RECIEVE_GROUP_MESSAGES,
  ADD_GROUP_MESSAGE,
  ADD_SEARCH_GROUP_NAME,
} from "../constants/actionTypes";

export default (
  state = {
    isFetching: false,
    didInvalidate: false,
    items: [],
    hubUrl: null,
    username: null,
  },
  action
) => {
  switch (action.type) {
    case GET_CONVERSATIONS:
      return {
        ...state,
        isFetching: true,
        didInvalidate: false,
      };
    case RECIEVE_CONVERSATIONS:
      return {
        ...state,
        isFetching: false,
        didInvalidate: false,
        items: action.items,
      };
    case GET_GROUP_CONVERSATIONS:
      return {
        ...state,
        isFetching: true,
        didInvalidate: false,
      };
    case RECIEVE_GROUP_CONVERSATIONS:
      return {
        ...state,
        isFetching: false,
        didInvalidate: false,
        groupConversations: action.groupConversations,
      };
    case GET_MESSAGES:
      return {
        ...state,
        isFetching: true,
        didInvalidate: false,
      };
    case RECIEVE_MESSAGES:
      const _newConversations = state.items.map((conversation) => {
        return conversation.conversationId == action.conversationId
          ? Object.assign({}, conversation, { messages: action.messages })
          : conversation;
      });
      return {
        ...state,
        isFetching: false,
        didInvalidate: false,
        items: [..._newConversations],
      };
    case GET_GROUP_MESSAGES:
      return {
        ...state,
        isFetching: true,
        didInvalidate: false,
      };
    case RECIEVE_GROUP_MESSAGES:
      const _newGropConversations = state.groupConversations.map(
        (groupConversation) => {
          return groupConversation.id == action.groupId
            ? Object.assign({}, groupConversation, {
                groupMessages: action.groupMessages,
              })
            : groupConversation;
        }
      );
      return {
        ...state,
        isFetching: false,
        didInvalidate: false,
        groupConversations: [..._newGropConversations],
      };

    case ADD_MESSAGE:
      const _newItemsFinal = state.items.map((item) => {
        return item.conversationId === action.conversationId
          ? Object.assign({}, item, {
              messages: [...item.messages, action.message],
            })
          : Object.assign({}, item);
      });
      return {
        ...state,
        isFetching: false,
        didInvalidate: false,
        items: [..._newItemsFinal],
      };
    case ADD_GROUP_MESSAGE:
      const _newItemsFinal4 = state.groupConversations.map((item) => {
        return item.groupId === action.groupId
          ? Object.assign({}, item, {
              groupMessages: [...item.groupMessages, action.groupMessage],
            })
          : Object.assign({}, item);
      });
      return {
        ...state,
        isFetching: false,
        didInvalidate: false,
        groupConversations: [..._newItemsFinal4],
      };
    case SET_LAST_MESSAGE:
      const _newItemsFinal2 = state.items.map((item) => {
        return item.conversationId == action.conversationId
          ? ((item.content = action.message.content),
            (item.createdAt = action.message.createdAt),
            Object.assign({}, item))
          : Object.assign({}, item);
      });
      return {
        ...state,
        isFetching: false,
        didInvalidate: false,
        items: [..._newItemsFinal2],
      };
    case SET_LAST_GROUP_MESSAGE:
      const _newItemsFinal3 = state.groupConversations.map((item) => {
        return item.groupId == action.groupId
          ? ((item.content = action.groupMessage.content),
            (item.createdAt = action.groupMessage.createdAt),
            Object.assign({}, item))
          : Object.assign({}, item);
      });
      return {
        ...state,
        isFetching: false,
        didInvalidate: false,
        groupConversations: [..._newItemsFinal3],
      };
    case SET_HUBURL:
      return {
        ...state,
        isFetching: false,
        didInvalidate: false,
        hubUrl: action.url,
      };
    case SET_USERNAME:
      return {
        ...state,
        username: action.username,
      };
    case SET_CONVERSATION_ID:
      return {
        ...state,
        showMSearchResults: false,
      };
    case SET_GROUP_CONVERSATION_ID:
      return {
        ...state,
        showGroupSearchResults: false,
      };
    default:
      return state;
  }
};

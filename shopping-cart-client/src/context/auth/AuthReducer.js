import types from '../../types';
import {
  saveTokenInLocalStorage,
  removeTokenFromLocalStorage,
} from '../../helpers';

import { initAuth } from '../../config';

const AuthReducer = (state, action) => {
  switch (action.type) {

    case types.LOGIN_SUCCESS:
      saveTokenInLocalStorage(action.payload.data.access_token);
      initAuth();
      return {
        ...state,
        message: action.payload.message,
        isLoggedIn: true,
        loggedUserInfo: { ...action.payload.data },
      };

    case types.USER_IS_LOGGED:
      initAuth();
      return {
        ...state,
        isLoggedIn: true,
      };

    case types.LOGOUT:
      removeTokenFromLocalStorage();
      return {
        ...state,
        token: null,
        isLoggedIn: false,
        loggedUserInfo: null,
        message: action.payload,
        loading: false,
      };

    case types.LOGIN_ERROR:
      return {
        ...state,
        loading: false,
        message: { ...action.payload },
      };

    default:
      return state;
  }
};

export default AuthReducer;

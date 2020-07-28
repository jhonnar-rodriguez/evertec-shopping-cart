import React, { useReducer, useEffect } from 'react';
import { AuthContext, AuthReducer } from './index';
import axiosClient from '../../config/axios';
import { formatResponse, generateKey } from '../../helpers';
import types from '../../types';

const defaultDataClientKey = {
  client_key: generateKey(),
};

const AuthState = (props) => {
  const initialState = {
    token: localStorage.getItem('token'),
    message: null,
    loading: false,
    isLoggedIn: false,
    loggedUserInfo: null,
  };

  // Dispatch Actions
  const [state, dispatch] = useReducer(AuthReducer, initialState);

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (token) {
      dispatch({
        type: types.USER_IS_LOGGED,
      });
    }
  }, []);

  /**
   * Sign in a user displaying the message according the server response
   *
   * @param {*} data
   */
  const signInUser = async (data) => {
    try {
      const response = await axiosClient.post('/api/login', data);

      if (response.status === 200) {
        console.log('response.data.firstName', response.data);
        dispatch({
          type: types.LOGIN_SUCCESS,
          payload: {
            data: response.data.data,
            message: response.data.message,
            level: 'success',
          },
        });
      } else {
        dispatch({
          type: types.LOGIN_ERROR,
          payload: {
            message: 'Something went wrong trying login the user. Please try again later.',
            level: 'error',
          },
        });
      }
    } catch (error) {
      const serverMessage = formatResponse('error', error);

      dispatch({
        type: types.LOGIN_ERROR,
        payload: {
          message: serverMessage,
          level: 'error',
        },
      });
    }
  };

  /**
   * Logout the user and destroy the token
   */
  const signOutUser = async () => {
    try {
      const response = await axiosClient.post('/api/logout', defaultDataClientKey);

      if (response.status === 200) {
        dispatch({
          type: types.LOGOUT,
          message: 'User logged out successfully',
        });
      } else {
        dispatch({
          type: types.LOGIN_ERROR,
          payload: {
            message: 'Something went wrong trying logout the user. Please try again later.',
            level: 'error',
          },
        });
      }
    } catch (error) {
      const serverMessage = formatResponse('error', error);

      dispatch({
        type: types.LOGIN_ERROR,
        payload: {
          message: serverMessage,
          level: 'error',
        },
      });
    }
  };

  /**
   * Clear the message from the state to avoid issues displaying the notification
   */
  const clearMessages = () => {
    dispatch({
      type: types.CLEAR_MESSAGE,
    });
  };

  const { children } = props;
  return (
    <AuthContext.Provider
      value={{
        token: state.token,
        message: state.message,
        loading: state.loading,
        isLoggedIn: state.isLoggedIn,
        loggedUserInfo: state.loggedUserInfo,
        signInUser,
        signOutUser,
        clearMessages,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export default AuthState;

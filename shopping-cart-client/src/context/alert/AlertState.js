import React, { useReducer, useCallback } from 'react';
import types from '../../types';
import { AlertReducer, AlertContext } from './index';

const AlertState = (props) => {
  const { children } = props;
  const initialState = {
    alert: null,
  };

  const [state, dispatch] = useReducer(AlertReducer, initialState);

  const showAlert = useCallback((message, level) => {
    const initAlert = (duration = 6000) => {
      dispatch({
        type: types.SHOW_ALERT,
        payload: {
          message,
          level,
          duration,
        },
      });

      setTimeout(() => {
        dispatch({
          type: types.HIDE_ALERT,
        });
      }, 5000);
    };

    initAlert();
  }, []);

  return (
    <AlertContext.Provider
      value={{
        alert: state.alert,
        showAlert,
      }}
    >
      {children}
    </AlertContext.Provider>
  );
};

export default AlertState;

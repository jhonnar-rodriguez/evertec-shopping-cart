import React, { useEffect, useReducer } from 'react';
import {
  OrderContext,
  OrderReducer,
} from './index';

import types from '../../types';
import { axiosClient } from '../../config';
import { formatResponse } from '../../helpers';

const OrderState = (props) => {
  const { children } = props;
  const initialState = {
    total: 0,
    orders: [],
    message: null,
    loading: false,
    reOpened: false,
    getOrders: false,
    orderItems: [],
  };

  // Dispatch Actions
  const [state, dispatch] = useReducer(OrderReducer, initialState);

  const fireOrderRequest = () => {
    dispatch({
      type: types.FIRE_GET_ORDERS,
    });
  };

  useEffect(() => {
    if (!state.getOrders) {
      return;
    }

    const getOrdersByUser = async () => {
      await axiosClient.get('/api/business/orders')
        .then((result) => {
          if (result.status === 200) {
            dispatch({
              type: types.ORDERS_RECEIVED,
              payload: {
                data: result.data.data,
              },
            });
          } else {
            console.log('Unexpected output from service', result);
          }
        })
        .catch((error) => {
          let serverMessage = formatResponse('error', error);
          try {
            console.log('formatResponse', error);
            let customMessage = [];
            const responseStatusCode = error.response.status;

            // Format the output message to an array
            if (!Array.isArray(serverMessage) && responseStatusCode === 422) {
              Object.keys(serverMessage).forEach((key) => {
                const element = serverMessage[key][0];
                customMessage = [
                  ...customMessage,
                  element,
                ];
              });

              if (customMessage.length) {
                serverMessage = customMessage;
              }
            }
          } catch (error) {
            serverMessage = 'Something went wrong getting your orders.';
          }

          dispatch({
            type: types.GET_ORDER_ERROR,
            payload: {
              message: serverMessage,
              level: 'error',
            },
          });
        });
    };
    getOrdersByUser();
  }, [state.getOrders]);

  /**
   * Generate a new shopping cart with the same items
   *
   * @param {*} cartId
   * @param {*} data
   */
  const reOpen = async (id) => {
    try {
      dispatch({
        type: types.SENDING_REQUEST,
      });
      const endpointUrl = `/api/business/orders/${id}/re-order`;
      const response = await axiosClient.post(endpointUrl);
      if (response.status === 201) {
        const serverMessage = formatResponse('success', response);
        dispatch({
          type: types.RE_OPEN_ORDER,
          payload: {
            message: serverMessage,
            level: 'success',
            data: response.data.data,
          },
        });
      } else {
        const serverMessage = formatResponse('error', response);

        dispatch({
          type: types.GET_ORDER_ERROR,
          payload: {
            message: serverMessage,
            level: 'error',
          },
        });
      }
    } catch (error) {
      let serverMessage = formatResponse('error', error);
      try {
        let customMessage = [];
        const responseStatusCode = error.response.status;

        // Format the output message to an array
        if (!Array.isArray(serverMessage) && responseStatusCode === 422) {
          Object.keys(serverMessage).forEach((key) => {
            const element = serverMessage[key][0];
            customMessage = [
              ...customMessage,
              element,
            ];
          });

          if (customMessage.length) {
            serverMessage = customMessage;
          }
        }
      } catch (error) {
        serverMessage = 'Something went wrong re opening your order';
      }

      dispatch({
        type: types.GET_ORDER_ERROR,
        payload: {
          message: serverMessage,
          level: 'error',
        },
      });
    }
  };

  const clearMessage = () => {
    dispatch({
      type: types.CLEAR_MESSAGE,
    });
  };

  return (
    <OrderContext.Provider
      value={{
        total: state.total,
        orders: state.orders,
        message: state.message,
        loading: state.loading,
        reOpened: state.reOpened,
        orderItems: state.orderItems,
        reOpen,
        clearMessage,
        fireOrderRequest,
      }}
    >
      {children}
    </OrderContext.Provider>
  );
};

export default OrderState;

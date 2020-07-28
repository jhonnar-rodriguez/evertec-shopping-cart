import React, { useEffect, useReducer } from 'react';
import {
  CartContext,
  CartReducer,
} from './index';

import types from '../../types';
import { axiosClient } from '../../config';
import { formatResponse, generateKey } from '../../helpers';

const defaultDataClientKey = {
  client_key: generateKey(),
};

const CartState = (props) => {
  const { children } = props;
  const initialState = {
    total: 0,
    loading: false,
    message: null,
    getCart: false,
    cartItems: [],
  };

  // Dispatch Actions
  const [state, dispatch] = useReducer(CartReducer, initialState);

  const fireGetCartRequest = () => {
    dispatch({
      type: types.FIRE_GET_CART,
    });
  };

  useEffect(() => {
    if (!state.getCart) {
      return;
    }

    const getCartByClient = async () => {
      await axiosClient.post('/api/business/cart', defaultDataClientKey)
        .then((result) => {
          if (result.status === 200) {
            dispatch({
              type: types.CART_RECEIVED,
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
            serverMessage = 'Something went wrong adding the item to the cart';
          }

          dispatch({
            type: types.GET_CART_ERROR,
            payload: {
              message: serverMessage,
              level: 'error',
            },
          });
        });
    };
    getCartByClient();
  }, [state.getCart]);

  /**
   * Add a new item to the user shopping cart
   *
   * @param {*} data
   */
  const addItem = async (productId, data) => {
    try {
      const response = await axiosClient.post(`api/business/cart/${productId}/add`, data);
      if (response.status === 201) {
        const serverMessage = formatResponse('success', response);

        dispatch({
          type: types.ADD_ITEM,
          payload: {
            message: serverMessage,
            level: 'success',
            data: {
              total: response.data.data.total,
            },
          },
        });
      } else {
        const serverMessage = formatResponse('error', response);

        dispatch({
          type: types.ADD_ITEM_ERROR,
          payload: {
            message: serverMessage,
            category: 'error',
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
        serverMessage = 'Something went wrong adding the item to the cart';
      }

      dispatch({
        type: types.ADD_ITEM_ERROR,
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
    <CartContext.Provider
      value={{
        total: state.total,
        loading: state.loading,
        message: state.message,
        cartItems: state.cartItems,
        addItem,
        clearMessage,
        fireGetCartRequest,
      }}
    >
      {children}
    </CartContext.Provider>
  );
};

export default CartState;

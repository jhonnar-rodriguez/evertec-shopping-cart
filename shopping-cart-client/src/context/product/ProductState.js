import React, { useReducer, useEffect } from 'react';
import {
  ProductContext,
  ProductReducer,
} from './index';

import types from '../../types';
import { axiosClient } from '../../config';
import { formatResponse, generateKey } from '../../helpers';

const ProductState = (props) => {
  const { children } = props;
  const initialState = {
    loading: true,
    message: null,
    products: {},
    clientKey: null,
  };

  // Dispatch Actions
  const [state, dispatch] = useReducer(ProductReducer, initialState);

  useEffect(() => {
    const getProducts = async () => {
      await axiosClient.get('/api/business/products')
        .then((result) => {
          if (result.status === 200) {
            dispatch({
              type: types.GET_PRODUCTS,
              payload: {
                data: result.data,
              },
            });
          } else {
            console.log('Unexpected output from service', result);
          }
        })
        .catch((error) => {
          let serverMessage = formatResponse('error', error);
          try {
            console.log('ERROR 2', error);
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
            type: types.GET_PRODUCTS_ERROR,
            payload: {
              message: serverMessage,
              level: 'error',
            },
          });
        });
    };
    getProducts();
  }, []);

  useEffect(() => {
    const generateClientKey = () => {
      dispatch({
        type: types.ADD_CART_KEY,
        payload: generateKey(),
      });
    };
    generateClientKey();
  }, []);

  return (
    <ProductContext.Provider
      value={{
        loading: state.loading,
        message: state.message,
        products: state.products,
        clientKey: state.clientKey,
      }}
    >
      {children}
    </ProductContext.Provider>
  );
};

export default ProductState;

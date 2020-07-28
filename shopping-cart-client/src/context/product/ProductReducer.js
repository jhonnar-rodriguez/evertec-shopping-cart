import types from '../../types';

export default (state, action) => {
  switch (action.type) {
    case types.GET_PRODUCTS:
      return {
        ...state,
        loading: false,
        products: {
          ...action.payload.data,
        },
      };

    case types.ADD_CART_KEY:
      return {
        ...state,
        loading: false,
        clientKey: action.payload,
      };

    case types.UPDATE_PRODUCT:
      return {
        ...state,
        loading: false,
        message: action.payload.message,
      };

    case types.GET_PRODUCTS_ERROR:
      return {
        ...state,
        loading: false,
        message: { ...action.payload },
      };
    default:
      return state;
  }
};

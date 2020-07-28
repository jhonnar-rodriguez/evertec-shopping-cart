import types from '../../types';
import { getCartTotal } from '../../helpers';

export default (state, action) => {
  switch (action.type) {
    case types.SENDING_REQUEST:
      return {
        ...state,
        loading: true,
      };

    case types.ADD_ITEM:
      getCartTotal(action.payload.data.total);
      return {
        ...state,
        loading: false,
        message: { ...action.payload },
        total: action.payload.data.total,
      };

    case types.CART_RECEIVED:
      return {
        ...state,
        loading: false,
        getCart: false,
        cartItems: [...action.payload.data],
        total: action.payload.data.length,
      };

    case types.FIRE_GET_CART:
      return {
        ...state,
        getCart: true,
        loading: true,
      };

    case types.ADD_ITEM_ERROR:
    case types.GET_CART_ERROR:
      return {
        ...state,
        loading: false,
        message: { ...action.payload },
      };

    case types.CLEAR_MESSAGE:
      return {
        ...state,
        message: null,
      };
    default:
      return state;
  }
};

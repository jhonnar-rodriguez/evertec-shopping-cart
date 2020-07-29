import types from '../../types';
import { getCartTotal, removeClientKeyFromLocalStorage } from '../../helpers';

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
        total: action.payload.data.total,
        loading: false,
        message: { ...action.payload },
      };

    case types.CART_RECEIVED:
      return {
        ...state,
        getCart: false,
        total: action.payload.data.total_items,
        cartId: action.payload.data.cart_id,
        loading: false,
        cartItems: action.payload.data.total_items ? [...action.payload.data.content] : [],
      };

    case types.FIRE_GET_CART:
      return {
        ...state,
        getCart: true,
        loading: true,
      };

    case types.ORDER_GENERATED:
      removeClientKeyFromLocalStorage();
      return {
        ...state,
        total: 0,
        cartId: 0,
        loading: false,
        message: { ...action.payload },
        cartItems: [],
        processUrl: action.payload.data.process_url,
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
        orderPlaced: false,
      };
    default:
      return state;
  }
};

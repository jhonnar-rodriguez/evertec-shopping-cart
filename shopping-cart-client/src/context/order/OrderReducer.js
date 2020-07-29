import types from '../../types';
import { saveClientKeyInLocalStorage } from '../../helpers';

export default (state, action) => {
  switch (action.type) {
    case types.SENDING_REQUEST:
      return {
        ...state,
        loading: true,
      };

    case types.ORDERS_RECEIVED:
      return {
        ...state,
        total: action.payload.data.length,
        orders: [...action.payload.data],
        loading: false,
        getOrders: false,
      };

    case types.FIRE_GET_ORDERS:
      return {
        ...state,
        loading: true,
        getOrders: true,
      };

    case types.RE_OPEN_ORDER:
      saveClientKeyInLocalStorage(action.payload.data.client_key);
      return {
        ...state,
        loading: false,
        reOpened: true,
        message: { ...action.payload },
      };

    case types.GET_ORDER_ERROR:
      return {
        ...state,
        loading: false,
        message: { ...action.payload },
        getOrders: false,
      };

    case types.CLEAR_MESSAGE:
      return {
        ...state,
        message: null,
        orderPlaced: false,
        reOpened: false,
      };
    default:
      return state;
  }
};

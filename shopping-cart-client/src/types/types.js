const types = {
  LOGOUT: '[auth] user was logged out successfully',
  ADD_ITEM: '[carts] add item',
  SHOW_ALERT: '[alerts] sending request',
  HIDE_ALERT: '[generic] sending request',
  LOGIN_ERROR: '[auth] something went wrong with the user login',
  ADD_CART_KEY: '[carts] add cart key',
  GET_PRODUCTS: '[products] get products',
  FIRE_GET_CART: '[carts] get the cart related to the session',
  CART_RECEIVED: '[carts] cart was loaded',
  CLEAR_MESSAGE: '[generic] clear message from context',
  LOGIN_SUCCESS: '[auth] user logged in successfully',
  GET_CART_ERROR: '[carts] get products error',
  USER_IS_LOGGED: '[auth] user is logged in',
  ADD_ITEM_ERROR: '[carts] request error',
  SENDING_REQUEST: '[generic] sending request',
  ORDER_GENERATED: '[orders] new order has been generated',
  GET_ORDER_ERROR: '[orders] something went wrong getting the order',
  ORDERS_RECEIVED: '[orders] orders were received',
  FIRE_GET_ORDERS: '[orders] begin the request to get the orders',
};

export default types;

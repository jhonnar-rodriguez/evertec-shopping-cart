/**
 * Format the server response according the notification type you want to make
 *
 * @param {*} type Validation to make
 * @param {*} serverMessage Response returned by the server
 */
const formatResponse = (type, serverMessage) => {
  const somethingWentWrong = 'Ha ocurrido un error con su solicitud, por favor intente de nuevo.';

  if (type !== 'success' && (typeof serverMessage === 'undefined' || typeof serverMessage.response === 'undefined')) {
    return somethingWentWrong;
  }

  let messageToReturn = '';

  if (type === 'error') {
    const serverResponse = serverMessage.response;
    if (serverResponse.status === 422) {
      messageToReturn =
        typeof serverResponse.data.errors !== 'undefined' ?
          serverResponse.data.errors :
          'La información proporcionada es inválida, por favor verifique.';
    } else {
      messageToReturn =
        typeof serverResponse.data.message !== 'undefined' ?
          serverResponse.data.message :
          somethingWentWrong;
    }
  } else {
    messageToReturn =
      typeof serverMessage.data.message !== 'undefined' ?
        serverMessage.data.message :
        'Your request was successfully executed!';
  }

  return messageToReturn;
};

/**
 * The client key will be used by the server to generate a shopping cart based on this id
 * If the key hasn't been saved in local storage a new key will be created and passed to the response
 */
const generateKey = () => {
  let clientId = localStorage.getItem('client_key');
  if (clientId) {
    return clientId;
  }
  // Math.random should be unique because of its seeding algorithm.
  // Convert it to base 36 (numbers + letters), and grab the first 9 characters
  // after the decimal.
  clientId = `_${Math.random().toString(36).substr(2, 9)}`;
  localStorage.setItem('client_key', clientId);
  return clientId;

};

const getCartTotal = (defaultTotal = 0) => {
  const cartTotal = parseInt(localStorage.getItem('cart_total'), 10);
  if (cartTotal && cartTotal === defaultTotal) {
    return cartTotal;
  }

  localStorage.setItem('cart_total', defaultTotal);
  return cartTotal;

};

export {
  formatResponse,
  generateKey,
  getCartTotal,
};

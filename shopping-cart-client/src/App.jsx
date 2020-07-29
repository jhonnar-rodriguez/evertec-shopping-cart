import React from 'react';
import { createBrowserHistory } from 'history';
import { AppRouter } from './router';
import {
  CartState,
  AuthState,
  AlertState,
  OrderState,
  ProductState,
} from './context';

import { initAuth } from './config';

const browserHistory = createBrowserHistory();

// Set token global
initAuth();

function App() {
  return (
    <AuthState>
      <AlertState>
        <OrderState>
          <ProductState>
            <CartState>
              <AppRouter history={browserHistory} />
            </CartState>
          </ProductState>
        </OrderState>
      </AlertState>
    </AuthState>
  );
}

export default App;

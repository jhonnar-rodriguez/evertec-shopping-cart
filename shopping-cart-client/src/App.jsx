import React from 'react';
import { AppRouter } from './router';
import {
  CartState,
  AlertState,
  AuthState,
  ProductState,
} from './context';

import { initAuth } from './config';

// Set token global
initAuth();

function App() {
  return (
    <AuthState>
      <AlertState>
        <ProductState>
          <CartState>
            <AppRouter />
          </CartState>
        </ProductState>
      </AlertState>
    </AuthState>
  );
}

export default App;

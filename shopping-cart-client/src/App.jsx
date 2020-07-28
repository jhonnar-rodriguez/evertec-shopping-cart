import React from 'react';
import { AppRouter } from './router';
import {
  ProductState,
  CartState,
  AlertState,
} from './context';

import { initAuth } from './config';

// Set token global
initAuth();

function App() {
  return (
    <AlertState>
      <ProductState>
        <CartState>
          <AppRouter />
        </CartState>
      </ProductState>
    </AlertState>
  );
}

export default App;

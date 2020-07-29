import React, { useContext, useEffect } from 'react';
import {
  BrowserRouter as Router,
} from 'react-router-dom';

// App Context
import { CartContext } from '../../context';

// App Router
import { StoreRoutes } from '..';

const AppRouter = () => {

  const { fireGetCartRequest } = useContext(CartContext);

  useEffect(() => {
    fireGetCartRequest();
  }, []);

  return (
    <Router>
      <StoreRoutes />
    </Router>
  );
};

export default AppRouter;

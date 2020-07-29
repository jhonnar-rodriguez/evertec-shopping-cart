import React, { useContext, useEffect } from 'react';
import {
  BrowserRouter as Router,
} from 'react-router-dom';

// App Context
import { CartContext } from '../../context';

// App Router
import { StoreRoutes } from '..';

const AppRouter = (props) => {
  const { history } = props;
  const { fireGetCartRequest } = useContext(CartContext);

  useEffect(() => {
    fireGetCartRequest();
  }, []);

  return (
    <Router>
      <StoreRoutes {...history} />
    </Router>
  );
};

export default AppRouter;

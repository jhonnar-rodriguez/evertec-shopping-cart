import React from 'react';
import {
  BrowserRouter as Router,
  Switch,
} from 'react-router-dom';

// App Components
import PrivateRoutes from '../../hoc';
import DashboardRoutes from '../DashboardRoutes';

const AppRouter = () => {

  return (
    <Router>
      <>
        <Switch>
          <PrivateRoutes
            path='/'
            isAuthenticated={true}
            component={DashboardRoutes}
          />
        </Switch>
      </>
    </Router>
  );
};

export default AppRouter;

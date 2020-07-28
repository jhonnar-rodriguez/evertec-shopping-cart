import React, { useContext } from 'react';
import {
  BrowserRouter as Router,
  Switch,
} from 'react-router-dom';

// App Context
import { AuthContext } from '../../context';

// HOC
import {
  PrivateRoutes,
  PublicRoutes,
} from '../../hoc';

// App Router
import { DashboardRoutes } from '..';

// App Screens
import { SignInScreen } from '../../screens';

const AppRouter = () => {
  const { isLoggedIn } = useContext(AuthContext);

  return (
    <Router>
      <>
        <Switch>
          <PublicRoutes
            exact
            path='/login'
            isAuthenticated={isLoggedIn}
            component={SignInScreen}
          />

          <PrivateRoutes
            path='/'
            isAuthenticated={isLoggedIn}
            component={DashboardRoutes}
          />
        </Switch>
      </>
    </Router>
  );
};

export default AppRouter;

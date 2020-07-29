import React, { useContext } from 'react';
import PropTypes from 'prop-types';
import { Route, Redirect } from 'react-router-dom';
import { AuthContext } from '../../context';

const PublicRoute = (props) => {
  const { component: Component, restricted, ...rest } = props;
  const { isLoggedIn } = useContext(AuthContext);

  return (
    // restricted = false meaning public route
    // restricted = true meaning restricted route on for users that aren't logged in
    <Route
      {...rest}
      render={(props) => (
        isLoggedIn && restricted ?
          <Redirect to='/dashboard' /> :
          <Component {...props} />
      )}
    />
  );
};

PublicRoute.propTypes = {
  component: PropTypes.func.isRequired,
};

export default PublicRoute;

import React from 'react';
import { Route, Redirect } from 'react-router-dom';
import PropTypes from 'prop-types';

const PrivateRoutes = (props) => {
  const { isAuthenticated, component: Component, ...rest } = props;
  localStorage.setItem('last_path', rest.location.pathname);

  return (
    <Route
      {...rest}
      component={(props) => ((isAuthenticated) ? <Component {...props} /> : <Redirect to='login' />
      )}
    />
  );
};

PrivateRoutes.propTypes = {
  isAuthenticated: PropTypes.bool.isRequired,
  component: PropTypes.func.isRequired,
};

export default PrivateRoutes;

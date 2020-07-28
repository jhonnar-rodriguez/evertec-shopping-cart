import React from 'react';
import { Route, Redirect } from 'react-router-dom';
import PropTypes from 'prop-types';

const PublicRoutes = (props) => {
  const { isAuthenticated, component: Component, ...rest } = props;

  return (
    <Route
      {...rest}
      component={(props) => ((!isAuthenticated) ? <Component {...props} /> : <Redirect to='/' />
      )}
    />
  );
};

PublicRoutes.propTypes = {
  isAuthenticated: PropTypes.bool.isRequired,
  component: PropTypes.func.isRequired,
};

export default PublicRoutes;

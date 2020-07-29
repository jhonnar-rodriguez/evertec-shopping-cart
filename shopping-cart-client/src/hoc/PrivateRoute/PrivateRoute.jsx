import React, { useContext } from 'react';
import PropTypes from 'prop-types';
import { Route, Redirect } from 'react-router-dom';
import { AuthContext } from '../../context';

const PrivateRoute = (props) => {
  const { component: Component, ...rest } = props;
  const { isLoggedIn } = useContext(AuthContext);

  return (

    // Show the component only when the user is logged in
    // Otherwise, redirect the user to /signin page
    <Route
      {...rest}
      render={(props) => (
        isLoggedIn ?
          <Component {...props} /> :
          <Redirect to='/signin' />
      )}
    />
  );
};

PrivateRoute.propTypes = {
  component: PropTypes.func.isRequired,
};

export default PrivateRoute;

import React, { useContext } from 'react';
import { Link as RouterLink } from 'react-router-dom';
import {
  Button,
} from '@material-ui/core';

import { AuthContext } from '../../../../context';

const DesktopMenu = () => {
  const { signOutUser, isLoggedIn } = useContext(AuthContext);

  return (
    <>
      <Button
        component={RouterLink}
        color='inherit'
        to='/products'
      >
        Products
      </Button>

      {
        isLoggedIn ? (
          <>
            <Button
              component={RouterLink}
              color='inherit'
              to='/orders'
            >
              Orders
            </Button>
            <Button
              color='inherit'
              onClick={signOutUser}
            >
              Logout
            </Button>
          </>
        ) : (
          <Button
            component={RouterLink}
            color='inherit'
            to='/signin'
          >
            Login
          </Button>
        )
      }
    </>
  );
};

export default DesktopMenu;

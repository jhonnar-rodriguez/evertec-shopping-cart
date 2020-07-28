import React, { useContext } from 'react';
import { Link as RouterLink } from 'react-router-dom';
import {
  Button,
} from '@material-ui/core';

import { AuthContext } from '../../../../context';

const DesktopMenu = () => {
  const { signOutUser } = useContext(AuthContext);

  return (
    <>
      <Button
        component={RouterLink}
        color='inherit'
        to='/products'
      >
        Products
      </Button>
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
  );
};

export default DesktopMenu;

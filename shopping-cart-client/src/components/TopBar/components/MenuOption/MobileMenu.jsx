import React, { useContext } from 'react';
import PropTypes from 'prop-types';
import { Link as RouterLink } from 'react-router-dom';
import {
  Button,
  IconButton,
  Menu,
  MenuItem,

} from '@material-ui/core';
import { AccountCircle } from '@material-ui/icons';
import { AuthContext } from '../../../../context';

const MobileMenu = (props) => {
  const { open, anchorEl, handleMenu, handleClose } = props;
  console.log(typeof anchorEl);
  const { signOutUser } = useContext(AuthContext);

  return (
    <>
      <IconButton
        aria-label='account of current user'
        aria-controls='menu-appbar'
        aria-haspopup='true'
        onClick={handleMenu}
        color='inherit'
      >
        <AccountCircle />
      </IconButton>
      <Menu
        id='menu-appbar'
        anchorEl={anchorEl}
        anchorOrigin={{
          vertical: 'top',
          horizontal: 'right',
        }}
        keepMounted
        transformOrigin={{
          vertical: 'top',
          horizontal: 'right',
        }}
        open={open}
        onClose={handleClose}
      >
        <MenuItem
          component={RouterLink}
          to='/products'
        >
          Products
        </MenuItem>

        <MenuItem
          component={RouterLink}
          to='/orders'
        >
          Orders
        </MenuItem>
        <MenuItem
          color='inherit'
          onClick={signOutUser}
          component={Button}
          style={{ textTransform: 'capitalize' }}
        >
          Logout
        </MenuItem>
      </Menu>
    </>
  );
};

MobileMenu.propTypes = {
  open: PropTypes.bool.isRequired,
  // anchorEl: PropTypes.isRequired,
  handleMenu: PropTypes.func.isRequired,
  handleClose: PropTypes.func.isRequired,
};

export default MobileMenu;

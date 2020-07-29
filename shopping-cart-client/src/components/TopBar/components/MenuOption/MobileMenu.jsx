import React, { useContext, useEffect } from 'react';
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
  const { open, anchorEl, setAnchorEl } = props;
  const { isLoggedIn, signOutUser } = useContext(AuthContext);

  const handleMenu = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  useEffect(() => {
    return () => {
      handleClose();
    };
  }, []);

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
        // keepMounted
        transformOrigin={{
          vertical: 'top',
          horizontal: 'right',
        }}
        open={open}
        onClose={handleClose}
      >
        <MenuItem
          to='/products'
          onClick={handleClose}
          component={RouterLink}
        >
          Products
        </MenuItem>

        {
          isLoggedIn ? (
            <>
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
            </>
          ) : (
            <MenuItem
              component={RouterLink}
              color='inherit'
              to='/signin'
              style={{ textTransform: 'capitalize' }}
              onClick={handleClose}
            >
              Login
            </MenuItem>
          )
        }
      </Menu>
    </>
  );
};

MobileMenu.propTypes = {
  open: PropTypes.bool.isRequired,
  anchorEl: PropTypes.object,
  // handleMenu: PropTypes.func.isRequired,
  // handleClose: PropTypes.func.isRequired,
};

export default MobileMenu;

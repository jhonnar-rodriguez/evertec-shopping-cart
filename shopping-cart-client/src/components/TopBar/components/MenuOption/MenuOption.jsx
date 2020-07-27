import React, { useState } from 'react';
import { Link as RouterLink } from 'react-router-dom';
import {
  Button,
  IconButton,
  Menu,
  MenuItem,
  useMediaQuery,
} from '@material-ui/core';
import { useTheme } from '@material-ui/core/styles';
import AccountCircle from '@material-ui/icons/AccountCircle';

const renderMobileMenu = (props) => {
  const { open, anchorEl, handleMenu, handleClose } = props;
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
      </Menu>
    </>
  );
};

const renderDesktopMenu = () => {
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
    </>
  );
};

const MenuOption = () => {
  const [anchorEl, setAnchorEl] = useState(null);
  const open = Boolean(anchorEl);
  const theme = useTheme();
  const isMobileView = useMediaQuery(theme.breakpoints.down('sm'));

  const handleMenu = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  return (
    <>
      {
        isMobileView ? renderMobileMenu({ open, anchorEl, handleMenu, handleClose }) : renderDesktopMenu()
      }
    </>
  );
};

export default MenuOption;

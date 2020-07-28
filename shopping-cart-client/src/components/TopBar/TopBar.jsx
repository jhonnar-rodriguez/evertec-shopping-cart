import React, { useContext } from 'react';
import { Link as RouterLink } from 'react-router-dom';
import { makeStyles } from '@material-ui/core/styles';
import {
  AppBar,
  Toolbar,
  Button,
} from '@material-ui/core';

import { CartContext } from '../../context';

// App components
import ShoppingCart from '../ShoppingCart';
import MenuOption from './components';

const useStyles = makeStyles((theme) => ({
  toolbar: {
    paddingRight: 24, // keep right padding when drawer closed
  },
  toolbarIcon: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'flex-end',
    padding: '0 8px',
    ...theme.mixins.toolbar,
  },
  appBar: {
    zIndex: theme.zIndex.drawer + 1,
    transition: theme.transitions.create(['width', 'margin'], {
      easing: theme.transitions.easing.sharp,
      duration: theme.transitions.duration.leavingScreen,
    }),
  },
  title: {
    flexGrow: 1,
    justifyContent: 'left',
    '&:hover': {
      backgroundColor: 'transparent',
    },
  },
  link: {
    color: 'white',
    wrap: 'noWrap',
    textDecoration: 'none',
  },
}));

const TopBar = (props) => {
  const classes = useStyles();
  const { total: totalItems } = useContext(CartContext);

  return (
    <AppBar position='absolute' className={classes.appBar}>
      <Toolbar className={classes.toolbar}>
        <Button
          to='/dashboard'
          color='inherit'
          component={RouterLink}
          className={classes.title}
          disableRipple
          disableTouchRipple
          disableElevation
        >
          Shopping Cart
        </Button>
        <MenuOption />

        <ShoppingCart totalItems={totalItems} />

      </Toolbar>
    </AppBar>
  );
};

export default TopBar;

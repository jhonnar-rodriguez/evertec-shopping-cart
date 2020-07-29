import React from 'react';
import { Redirect, Switch } from 'react-router-dom';
import { makeStyles } from '@material-ui/core/styles';
import CssBaseline from '@material-ui/core/CssBaseline';

import {
  Container,
  Grid,
  Box,
} from '@material-ui/core';

// App Components
import { TopBar, Footer } from '../../components';

// App Screens
import {
  CartScreen,
  OrderScreen,
  SignInScreen,
  ProductScreen,
  DashboardScreen,
} from '../../screens';
import { PublicRoute, PrivateRoute } from '../../hoc';

const drawerWidth = 240;

const useStyles = makeStyles((theme) => ({
  root: {
    display: 'flex',
  },
  drawerPaper: {
    position: 'relative',
    whiteSpace: 'nowrap',
    width: drawerWidth,
    transition: theme.transitions.create('width', {
      easing: theme.transitions.easing.sharp,
      duration: theme.transitions.duration.enteringScreen,
    }),
  },
  drawerPaperClose: {
    overflowX: 'hidden',
    transition: theme.transitions.create('width', {
      easing: theme.transitions.easing.sharp,
      duration: theme.transitions.duration.leavingScreen,
    }),
    width: theme.spacing(7),
    [theme.breakpoints.up('sm')]: {
      width: theme.spacing(9),
    },
  },
  appBarSpacer: theme.mixins.toolbar,
  content: {
    flexGrow: 1,
    height: '100vh',
    overflow: 'auto',
  },
  container: {
    paddingTop: theme.spacing(4),
    paddingBottom: theme.spacing(4),
  },
  paper: {
    padding: theme.spacing(2),
    display: 'flex',
    overflow: 'auto',
    flexDirection: 'column',
  },
  fixedHeight: {
    height: 240,
  },
}));

const StoreRoutes = (props) => {
  const classes = useStyles();

  return (
    <div className={classes.root}>
      <CssBaseline />
      <TopBar />

      <main className={classes.content}>
        <div className={classes.appBarSpacer} />
        <Container
          maxWidth='lg'
          className={classes.container}
        >
          <Grid
            container
            spacing={3}
          >
            <Switch>
              <PublicRoute path='/dashboard' exact restricted={false} component={DashboardScreen} />
              <PublicRoute path='/products' exact restricted={false} component={ProductScreen} />
              <PublicRoute path='/cart' exact restricted={false} component={CartScreen} />
              <PublicRoute path='/signin' exact restricted={true} component={SignInScreen} />
              <PrivateRoute {...props} path='/orders' exact component={OrderScreen} />
              <PrivateRoute path='/orders/:id' exact component={OrderScreen} />
              <Redirect to='/dashboard' />
            </Switch>
          </Grid>
          <Box pt={4}>
            <Footer />
          </Box>
        </Container>
      </main>

    </div>
  );
};

export default StoreRoutes;

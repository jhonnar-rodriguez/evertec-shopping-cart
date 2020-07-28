import React from 'react';
import { Route, Redirect, Switch } from 'react-router-dom';
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
  ProductScreen,
  DashboardScreen,
} from '../../screens';

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

const DashboardRoutes = () => {
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
              <Route exact path='/dashboard' component={DashboardScreen} />
              <Route exact path='/products' component={ProductScreen} />
              <Route exact path='/orders' component={OrderScreen} />
              <Route exact path='/cart' component={CartScreen} />

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

export default DashboardRoutes;

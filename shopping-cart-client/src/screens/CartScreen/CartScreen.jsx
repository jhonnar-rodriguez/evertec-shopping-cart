import React, { useContext, useEffect, useState } from 'react';
import { makeStyles } from '@material-ui/core/styles';
import {
  Box,
  Grid,
  List,
  Button,
  ListItem,
  Typography,
  ListItemText,
} from '@material-ui/core';

import {
  CartContext,
  AlertContext,
  AuthContext,
} from '../../context';

import { CustomSnackbar, SimpleBackdrop } from '../../components';

const useStyles = makeStyles((theme) => ({
  listItem: {
    padding: theme.spacing(1, 0),
  },
  total: {
    fontWeight: 700,
  },
  title: {
    marginTop: theme.spacing(2),
  },
  buttons: {
    textAlign: 'end',
  },
  button: {
    marginTop: theme.spacing(3),
    marginLeft: theme.spacing(1),
  },
  warningColor: {
    color: theme.palette.warning.main,
  },
}));

const CartScreen = (props) => {
  const classes = useStyles();
  const [placingOrder, setPlacingOrder] = useState(false);
  const { cartId, placeOrder, processUrl, cartItems, message: cartMessages, fireGetCartRequest } = useContext(CartContext);
  const { alert, showAlert } = useContext(AlertContext);
  const { isLoggedIn } = useContext(AuthContext);

  useEffect(() => {
    fireGetCartRequest();
  }, []);

  useEffect(() => {
    setPlacingOrder(false);
    if (cartMessages) {
      showAlert(cartMessages.message, cartMessages.level);
    }
  }, [showAlert, cartMessages]);

  useEffect(() => {
    setPlacingOrder(false);
    if (processUrl) {
      window.location.href = processUrl;
    }
  }, [processUrl]);

  const getCartTotal = () => {
    if (!cartItems.length) {
      return 0;
    }

    return cartItems.reduce((total, cartItem) => {
      return total + (cartItem.quantity * cartItem.price);
    }, 0);
  };

  const handlePlaceOrder = () => {
    const cartTotal = getCartTotal();

    if (!cartTotal) {
      return;
    }
    setPlacingOrder(true);
    const data = {
      order_total: cartTotal,
      order_status: 'CREATED',
      redirect_base: `${process.env.REACT_APP_CLIENT_URL}/orders`,
    };

    placeOrder(cartId, data);
  };

  return (
    <>
      <Grid
        item
        xs={12}
      >
        <Typography
          align='center'
          variant='h6'
          gutterBottom
        >
          Order summary
        </Typography>

        <List disablePadding>
          {

            cartItems.length ? (
              <>
                {cartItems.map((product) => (
                  <ListItem
                    className={classes.listItem}
                    key={product.name}
                  >
                    <ListItemText
                      primary={product.name}
                      secondary={product.description}
                    />
                    <Typography variant='body2'>
                      {product.price}
                    </Typography>
                  </ListItem>
                ))}

                <ListItem
                  className={classes.listItem}
                >
                  <ListItemText primary='Total' />
                  <Typography
                    variant='subtitle1'
                    className={classes.total}
                  >
                    {`USD$ ${Math.trunc(getCartTotal() * 100) / 100}`}
                  </Typography>
                </ListItem>
              </>
            ) : 'Your cart is empty'

          }
        </List>

        <div className={classes.buttons}>
          <Button
            variant='contained'
            color='primary'
            onClick={handlePlaceOrder}
            className={classes.button}
            disabled={!cartItems.length || !isLoggedIn}
          >
            Place Order
          </Button>

          {
            !isLoggedIn && (
              <Typography
                variant='caption'
                display='block'
                className={classes.warningColor}
                gutterBottom
              >
                You need to be logged in to place an order
              </Typography>
            )
          }
        </div>

      </Grid>

      {
        alert ? (
          <Box
            m={2}
          >
            <CustomSnackbar {...alert} />
          </Box>
        ) : null
      }

      {placingOrder && <SimpleBackdrop />}
    </>
  );
};

export default CartScreen;

import React, { useContext, useEffect } from 'react';
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

import { CartContext, AlertContext, AuthContext } from '../../context';

import { CustomSnackbar } from '../../components';

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
    display: 'flex',
    justifyContent: 'flex-end',
  },
  button: {
    marginTop: theme.spacing(3),
    marginLeft: theme.spacing(1),
  },
}));

const CartScreen = () => {
  const classes = useStyles();
  const { cartItems, message: cartItemMessage } = useContext(CartContext);
  const { alert, showAlert } = useContext(AlertContext);
  const { isLoggedIn } = useContext(AuthContext);

  useEffect(() => {
    if (cartItemMessage) {
      showAlert(cartItemMessage.message, cartItemMessage.level);
    }
  }, [showAlert, cartItemMessage]);

  const handleNext = () => {
    console.log('next');
  };

  const getCartTotal = () => {
    if (!cartItems.length) {
      return 0;
    }

    return cartItems.reduce((total, cartItem) => {
      return total + (cartItem.quantity * cartItem.price);
    }, 0);
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
            onClick={handleNext}
            className={classes.button}
            disabled={!cartItems.length || !isLoggedIn}
          >
            Place Order
          </Button>
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
    </>
  );
};

export default CartScreen;

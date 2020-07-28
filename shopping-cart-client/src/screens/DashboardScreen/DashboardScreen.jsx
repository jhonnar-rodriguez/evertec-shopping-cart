import React, { useContext, useEffect } from 'react';
import {
  Box,
  Grid,
  Link,
  Typography,
} from '@material-ui/core';
import { CartContext, AlertContext } from '../../context';

import { CustomSnackbar } from '../../components';

const DashboardScreen = () => {

  const { message: cartItemMessage, fireGetCartRequest } = useContext(CartContext);
  const { alert, showAlert } = useContext(AlertContext);

  useEffect(() => {
    if (cartItemMessage) {
      showAlert(cartItemMessage.message, cartItemMessage.level);
    }
  }, [showAlert, cartItemMessage]);

  useEffect(() => {
    fireGetCartRequest();
  }, []);

  return (
    <>
      <Grid
        item
        xs={12}
      >
        <Typography
          variant='h4'
          gutterBottom
        >
          Welcome,
        </Typography>

        <Typography
          variant='body1'
          gutterBottom
        >
          This is a basic store app, where a customer can buy items. First they need to add the desired products to the shopping cart and then
          proceed to the checkout section.
        </Typography>

        <Typography
          variant='body1'
          gutterBottom
        >
          Note: The PaymentGateway used in this application is PlacetoPay yout can learn more
          <Link
            href='https://placetopay.github.io/web-checkout-api-docs/#webcheckout'
            target='_blank'
          >
            {' here'}
          </Link>
        </Typography>
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

export default DashboardScreen;

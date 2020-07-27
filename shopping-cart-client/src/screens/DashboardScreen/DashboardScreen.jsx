import React from 'react';
import {
  Grid,
  Typography,
  Link,
} from '@material-ui/core';

const DashboardScreen = () => {
  return (
    <Grid
      item
      xs={12}
    >
      <Typography
        variant='h4'
        gutterBottom
      >
        Welcome
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
  );
};

export default DashboardScreen;

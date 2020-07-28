import React from 'react';
import { Link as RouterLink } from 'react-router-dom';
import PropTypes from 'prop-types';
import {
  Badge,
  Button,
} from '@material-ui/core';

import {
  ShoppingCart as ShoppingCartIcon,
} from '@material-ui/icons';

const ShoppingCart = (props) => {
  const { totalItems } = props;

  return (
    <Button
      to='/cart'
      color='inherit'
      component={RouterLink}
    >
      <Badge
        badgeContent={totalItems}
        color='secondary'
      >
        <ShoppingCartIcon />
      </Badge>
    </Button>
  );
};

ShoppingCart.propTypes = {
  totalItems: PropTypes.number.isRequired,
};

export default ShoppingCart;

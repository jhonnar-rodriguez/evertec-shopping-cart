import React from 'react';
import PropTypes from 'prop-types';
import {
  Badge,
  IconButton,
} from '@material-ui/core';

import {
  ShoppingCart as ShoppingCartIcon,
} from '@material-ui/icons';

const ShoppingCart = (props) => {
  const { totalItems } = props;

  return (
    <IconButton color='inherit'>
      <Badge
        badgeContent={totalItems}
        color='secondary'
      >
        <ShoppingCartIcon />
      </Badge>
    </IconButton>
  );
};

ShoppingCart.propTypes = {
  totalItems: PropTypes.number.isRequired,
};

export default ShoppingCart;

import React from 'react';
import {
  Typography,
  Link,
} from '@material-ui/core';

const Footer = () => {
  return (
    <Typography
      variant='body2'
      color='textSecondary'
      align='center'
    >
      {'Copyright © '}
      <Link
        color='inherit'
        href='https://www.linkedin.com/in/jhonnar-rodriguez'
        target='_target'
        style={{ textDecoration: 'none' }}
      >
        Jhonnar Rodriguez
      </Link>
      {' '}
      {new Date().getFullYear()}
      {'.'}
    </Typography>
  );
};

export default Footer;

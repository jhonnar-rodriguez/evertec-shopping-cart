import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import PropTypes from 'prop-types';
import {
  Card,
  Grid,
  Button,
  CardMedia,
  CardActions,
  CardContent,
  Typography,
  CardActionArea,
} from '@material-ui/core';

const useStyles = makeStyles((theme) => ({
  media: {
    height: 140,
  },
  cardActions: {
    display: 'flex',
    justifyContent: 'space-between',
    borderTop: `1px solid ${theme.palette.grey.A200}`,
  },
  addToCartButton: {
    '&:hover': {
      color: theme.palette.primary.contrastText,
      backgroundColor: theme.palette.primary.main,
    },
  },
}));

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;

const ProductITem = (props) => {
  const classes = useStyles();
  const { name, price, image, description, handleAddToCart } = props;

  return (
    <Grid
      item
      xs={12}
      sm={6}
      lg={3}
    >
      <Card
        raised
        style={{ height: '100%' }}
      >
        <CardActionArea>
          <CardMedia
            className={classes.media}
            image={`${BACKEND_URL}/storage/images/${image}`}
            title={name}
          />
          <CardContent>
            <Typography
              gutterBottom
              variant='h5'
              component='h2'
            >
              {name.substring(0, 30)}
              {' ...'}
            </Typography>
            <Typography
              variant='body2'
              color='textSecondary'
              component='p'
            >
              {description.substring(0, 150)}
              {'...'}
            </Typography>
          </CardContent>
        </CardActionArea>

        <CardActions
          className={classes.cardActions}
        >
          <Typography
            variant='body2'
          >
            {`USD$ ${Math.trunc(price * 100) / 100}`}
          </Typography>

          <Button
            size='small'
            color='primary'
            variant='outlined'
            className={classes.addToCartButton}
            onClick={handleAddToCart}
          >
            Add to Cart
          </Button>
        </CardActions>
      </Card>
    </Grid>
  );
};

ProductITem.propTypes = {
  name: PropTypes.string.isRequired,
  price: PropTypes.number.isRequired,
  image: PropTypes.string.isRequired,
  description: PropTypes.string.isRequired,
};

export default ProductITem;

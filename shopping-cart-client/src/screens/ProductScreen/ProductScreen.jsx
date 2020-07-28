import React, { useContext, useEffect, useState } from 'react';
import {
  Grid,
  Box,
} from '@material-ui/core';

// App Context
import {
  CartContext,
  AlertContext,
  ProductContext,
} from '../../context';

// App Components
import ProductItem from './component';

// General Components
import { SimpleBackdrop, CustomSnackbar } from '../../components';

const ProductScreen = () => {
  const [sendingRequest, setSendingRequest] = useState(false);
  const { message: productsMessage, products, loading: loadingProducts, clientKey } = useContext(ProductContext);
  const { addItem, message: cartItemMessage, clearMessage } = useContext(CartContext);
  const { alert, showAlert } = useContext(AlertContext);

  const handleAddToCart = async (productId, quantity = 1) => {
    const data = {
      quantity,
      client_key: clientKey,
    };
    setSendingRequest(true);
    await addItem(productId, data);
  };

  useEffect(() => {

    return () => {
      clearMessage();
    };
  }, []);

  useEffect(() => {
    if (cartItemMessage) {
      setSendingRequest(false);
      showAlert(cartItemMessage.message, cartItemMessage.level);
    }
  }, [showAlert, cartItemMessage]);

  useEffect(() => {
    if (productsMessage) {
      setSendingRequest(false);
      showAlert(productsMessage.message, productsMessage.level);
    }
  }, [showAlert, productsMessage]);

  return (
    <>
      <Grid
        container
        spacing={3}
        direction='row'
        alignItems='stretch'
      >
        {(loadingProducts || sendingRequest) && <SimpleBackdrop />}
        {
          !loadingProducts && Object.keys(products).length > 0 && products.data.total > 0 ? (
            products.data.data.map((product) => (
              <ProductItem
                key={product.id}
                name={product.name}
                price={product.price}
                image={product.image}
                description={product.description}
                handleAddToCart={() => handleAddToCart(product.id)}
              />
            ))
          ) :
            <>No product available</>
        }
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

export default ProductScreen;

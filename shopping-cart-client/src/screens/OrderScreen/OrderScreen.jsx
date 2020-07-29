import React, { useContext, useEffect, useState } from 'react';
import {
  Box,
  Table,
  TableRow,
  TableBody,
  TableCell,
  TableHead,
  Typography,
} from '@material-ui/core';

import {
  OrderContext,
  AlertContext,
} from '../../context';

// Generic Components
import { CustomSnackbar, SimpleBackdrop } from '../../components';

// Screen Internal Component
import OrderItem, { ReopenConfirmation } from './components';

const OrderScreen = (props) => {
  const { history } = props;
  const { loading, orders, message, reOpen, reOpened, fireOrderRequest, clearMessage } = useContext(OrderContext);
  const { alert, showAlert } = useContext(AlertContext);
  const [reOpenConfirmation, setReopenConfirmation] = useState(false);
  const [orderId, setOrderId] = useState('');

  const handleClickOpen = (id) => {
    setReopenConfirmation(true);
    setOrderId(id);
  };

  const handleClose = (choice = false) => {
    setReopenConfirmation(false);
    if (choice) {
      console.log('orderId', orderId);
      reOpen(orderId);
    } else {
      setOrderId('');
    }
  };

  useEffect(() => {
    fireOrderRequest();
  }, []);

  useEffect(() => {
    if (message) {
      showAlert(message.message, message.level);
    }
  }, [showAlert, message]);

  useEffect(() => {
    if (reOpened) {
      clearMessage();
      history.push('/cart');
    }
  }, [reOpened]);

  return (
    <>
      <Typography
        color='primary'
        variant='h6'
        component='h2'
        gutterBottom
      >
        Recent Orders
      </Typography>

      <Table size='small'>
        <TableHead>
          <TableRow>
            <TableCell>Order #</TableCell>
            <TableCell>Date</TableCell>
            <TableCell>Name</TableCell>
            <TableCell>Status</TableCell>
            <TableCell align='right'>Sale Amount</TableCell>
            <TableCell>Actions</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {typeof orders !== 'undefined' && orders.length ? (
            orders.map((order) => (
              <OrderItem
                key={order.id}
                handleClickOpen={handleClickOpen}
                {...order}
              />
            ))
          ) : (
            <TableRow>
              <TableCell
                colSpan={5}
              >
                <Typography
                  align='center'
                  color='secondary'
                  variant='h6'
                  component='h3'
                  gutterBottom
                >
                  There are no orders available, please place a new one first.
                </Typography>
              </TableCell>
            </TableRow>
          )}
        </TableBody>
      </Table>

      {
        alert ? (
          <Box
            m={2}
          >
            <CustomSnackbar {...alert} />
          </Box>
        ) : null
      }

      {loading && <SimpleBackdrop />}

      <ReopenConfirmation
        open={reOpenConfirmation}
        handleClose={handleClose}
      />
    </>
  );
};

export default OrderScreen;

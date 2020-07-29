import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import {
  Button,
  TableRow,
  TableCell,
  Table,
  TableBody,
  Link,
} from '@material-ui/core';

const useStyles = makeStyles((theme) => ({
  actionLink: {
    margin: 0,
    padding: 0,
    textAlign: 'center',
    borderBottom: 'none',
  },
  anchor: {
    textTransform: 'capitalize',
    color: 'blue',
    '&:visited': {
      color: 'blue',
    },
  },
}));

const OrderItem = (order) => {
  const classes = useStyles();
  const { id, created_at: createdAt, name, status, total: orderTotal, process_url: processUrl, handleClickOpen } = order;

  const handleReorder = (id) => {
    handleClickOpen(id);
  };

  return (
    <TableRow>
      <TableCell>{id}</TableCell>
      <TableCell>{createdAt}</TableCell>
      <TableCell>{name}</TableCell>
      <TableCell>{status}</TableCell>
      <TableCell align='right'>
        USD $
        {orderTotal}
      </TableCell>

      <TableCell colSpan={status !== 'CREATED' ? 2 : 1}>

        <Table>
          <TableBody>
            <TableRow>
              {status !== 'CREATED' && (
                <TableCell
                  className={classes.actionLink}
                >
                  <Button
                    component={Link}
                    onClick={() => handleReorder(id)}
                    className={classes.anchor}
                    disableTouchRipple
                  >
                    Reorder
                  </Button>
                </TableCell>
              )}

              <TableCell
                className={classes.actionLink}
              >
                <Button
                  rel='noreferrer'
                  href={processUrl}
                  target='_blank'
                  component={Link}
                  className={classes.anchor}
                  disableTouchRipple
                >
                  Payment Detail
                </Button>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>

      </TableCell>
    </TableRow>
  );
};

export default OrderItem;

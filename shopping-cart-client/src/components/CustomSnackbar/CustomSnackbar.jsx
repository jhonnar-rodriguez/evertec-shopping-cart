import React from 'react';
import PropTypes from 'prop-types';
import Snackbar from '@material-ui/core/Snackbar';
import MuiAlert from '@material-ui/lab/Alert';
import { makeStyles } from '@material-ui/core/styles';

const Alert = (props) => {
  return <MuiAlert elevation={6} variant='filled' {...props} />;
};

const renderRow = (messages, classes, level) => {
  let index = 0;
  return Array.isArray(messages) === true ? (
    <>
      <ul className={classes.ul}>
        {messages.map((msg) => {
          index++;
          return (
            <Alert
              key={index}
              severity={`${level}`}
              className={classes.mt}
            >
              <li>
                {msg}
              </li>
            </Alert>
          );
        })}
      </ul>
    </>
  ) : (
    <Alert severity={`${level}`}>
      {messages}
    </Alert>
  );
};

const useStyles = makeStyles((theme) => ({
  root: {
    width: '100%',
    '& > * + *': {
      marginTop: theme.spacing(2),
    },
  },
  list: {
    width: '100%',
    maxWidth: 360,
    backgroundColor: theme.palette.background.paper,
  },
  ul: {
    listStyle: 'none',
  },
  mt: {
    marginTop: theme.spacing(1),
  },
}));

const CustomSnackbar = ({ message, level, duration = 6000, open = true }) => {
  const classes = useStyles();
  return (
    <div
      className={classes.root}
      m={2}
    >
      <Snackbar
        open={open}
        autoHideDuration={duration}
      >
        {renderRow(message, classes, level)}
      </Snackbar>
    </div>
  );
};

CustomSnackbar.propTypes = {
  message: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.array,
  ]).isRequired,
  level: PropTypes.string.isRequired,
  duration: PropTypes.number,
};

export default CustomSnackbar;

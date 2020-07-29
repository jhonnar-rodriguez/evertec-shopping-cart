import React, { useState, useContext, useEffect } from 'react';
import {
  Box,
  Avatar,
  Button,
  Container,
  TextField,
  Typography,
  CssBaseline,
} from '@material-ui/core';
import LockOutlinedIcon from '@material-ui/icons/LockOutlined';
import { makeStyles } from '@material-ui/core/styles';

// Context
import {
  AuthContext,
  AlertContext,
} from '../../context';

// General Components
import { CustomSnackbar, SimpleBackdrop } from '../../components';

const useStyles = makeStyles((theme) => ({
  paper: {
    marginTop: theme.spacing(8),
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
  },
  avatar: {
    margin: theme.spacing(1),
    backgroundColor: theme.palette.secondary.main,
  },
  form: {
    width: '100%', // Fix IE 11 issue.
    marginTop: theme.spacing(1),
  },
  submit: {
    margin: theme.spacing(3, 0, 2),
  },
}));

const SignInScreen = () => {
  const classes = useStyles();

  const [isLoading, setLoading] = useState(false);

  const [formState, setFormState] = useState({
    values: {},
  });

  // Alert Context
  const { alert, showAlert } = useContext(AlertContext);

  // Auth Context
  const {
    message,
    signInUser,
  } = useContext(AuthContext);

  const handleChange = (event) => {
    event.persist();

    setFormState((formState) => ({
      ...formState,
      values: {
        ...formState.values,
        [event.target.name]: event.target.value,
      },
    }));
  };

  const handleSignIn = (event) => {
    event.preventDefault();
    setLoading(true);

    // Actions
    signInUser({
      ...formState.values,
    });
  };

  useEffect(() => {
    if (message) {
      setLoading(false);
      showAlert(message.message, message.level);
    }
  }, [showAlert, message]);

  return (
    <>
      <Container
        component='main'
        maxWidth='xs'
      >
        <CssBaseline />
        <div
          className={classes.paper}
        >
          <Avatar
            className={classes.avatar}
          >
            <LockOutlinedIcon />
          </Avatar>

          <Typography
            variant='h5'
            component='h1'
          >
            Sign in
          </Typography>

          <form
            onSubmit={handleSignIn}
            className={classes.form}
          >
            <TextField
              name='email'
              value={formState.values.email || ''}
              label='Email Address'
              margin='normal'
              variant='outlined'
              required
              onChange={handleChange}
              fullWidth
              autoFocus
              autoComplete='off'
            />
            <TextField
              type='password'
              name='password'
              value={formState.values.password || ''}
              label='Password'
              margin='normal'
              variant='outlined'
              required
              onChange={handleChange}
              fullWidth
              autoComplete='off'
            />
            <Button
              type='submit'
              fullWidth
              variant='contained'
              color='primary'
              className={classes.submit}
              disabled={
                !formState.values.password || !formState.values.email
              }
            >
              Sign In
            </Button>
          </form>
        </div>
      </Container>

      {
        isLoading && <SimpleBackdrop />
      }

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

export default SignInScreen;

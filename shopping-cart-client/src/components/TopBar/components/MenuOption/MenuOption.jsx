import React, { useState } from 'react';
import {
  useMediaQuery,
} from '@material-ui/core';
import { useTheme } from '@material-ui/core/styles';
import DesktopMenu from './DesktopMenu';
import MobileMenu from './MobileMenu';

const MenuOption = () => {
  const [anchorEl, setAnchorEl] = useState(null);
  const open = Boolean(anchorEl);
  const theme = useTheme();
  const isMobileView = useMediaQuery(theme.breakpoints.down('sm'));

  const handleMenu = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  return (
    <>
      {
        isMobileView ? (
          <MobileMenu
            open={open}
            anchorEl={anchorEl}
            handleMenu={handleMenu}
            handleClose={handleClose}
          />
        ) : <DesktopMenu />
      }
    </>
  );
};

export default MenuOption;

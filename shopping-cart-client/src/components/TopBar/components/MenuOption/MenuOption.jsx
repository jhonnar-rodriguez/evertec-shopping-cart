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

  return (
    <>
      {
        isMobileView ? (
          <MobileMenu
            open={open}
            setAnchorEl={setAnchorEl}
            anchorEl={anchorEl}
          />
        ) : <DesktopMenu />
      }
    </>
  );
};

export default MenuOption;

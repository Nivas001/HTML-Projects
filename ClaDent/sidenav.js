import React from 'react';
import './sidenav.css';

const SideNavbar = () => {
  return (
    <div className="side-navbar">
      <a href="#home">Home</a>
      <a href="#services">Services</a>
      <a href="#about">About</a>
      <a href="#contact">Contact</a>
    </div>
  );
};

export default SideNavbar;

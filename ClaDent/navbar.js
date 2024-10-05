import React, { useState, useRef, useEffect } from 'react';
import './navbar.css'; // Import the CSS file for styles
 import {  Link , Routes,Route} from 'react-router-dom'; 
import logo from './images/d.jpg'; // Assuming you have a logo image
import DoctorPage from './doctorpage';
import LabPage from './labpage';

const Navbar = () => {
  const [showDropdown, setShowDropdown] = useState(false);  // Dropdown visibility state
  const dropdownRef = useRef(null);  // Reference to dropdown element

  // Toggle dropdown when clicking the login icon
  const toggleDropdown = () => {
    setShowDropdown(prevState => !prevState);
  };

  // Close dropdown when clicking outside
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setShowDropdown(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);

  return (
   
    <nav className="navbar" >
      <div className="navbar-left">
        <img src={logo} alt="Clinic Logo" className="logo" />
        <span className="clinic-name">Dr. Nithya's Dental & Smile Design Clinic</span>
      </div>
      <div className="navbar-menu">
      <span className="login-icon" onClick={toggleDropdown}>Login</span>
          {showDropdown && (
            <div className="login-options" ref={dropdownRef}>
               <Link to="/doctor">Doctor</Link>
              <Link to="/lab">Lab</Link>
          
            </div>
          )}
          </div> 
          
          <Routes>
         {/*  <Route path="/" element={<Navigate to="/doctor" />} /> */}
        <Route path="/doctor" element={<DoctorPage />} />
        <Route path="/lab" element={<LabPage />} />
    </Routes>


    </nav>
   
  );
};

export default Navbar;

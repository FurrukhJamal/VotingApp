import React from 'react'
import NavLink from './NavLink'
import { Link } from '@inertiajs/react'
import ApplicationLogo from './ApplicationLogo'
import "../../css/navigationBar.css"

function NavigationBar({auth,}) {
  return (
    <div className='navbar'>
        <div className = "navLeftContainer">
            <NavLink href = {route("home")} active = {route().current("home")} className = {route().current("home") ?  'navLinkActive' : 'navLink'}>
                All Ideas
            </NavLink>
            
            <NavLink  className = {route().current("considering") ?  'navLinkActive' : 'navLink'}>
                Considering
            </NavLink>
            <NavLink className = {route().current("inprogress") ?  'navLinkActive' : 'navLink'}>
                In Progress
            </NavLink>
        </div>
        <div className = "navRightContainer">
            <NavLink className = "navLink">
                Implemented 
            </NavLink>
            <NavLink className = "navLink">
                Closed
            </NavLink>
        </div>
        
    </div>
    
  )
}

export default NavigationBar
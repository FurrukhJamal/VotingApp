import React from 'react'
import NavLink from './NavLink'
import { Link } from '@inertiajs/react'
import ApplicationLogo from './ApplicationLogo'
import "../../css/navigationBar.css"

function NavigationBar({ auth, }) {
    return (
        <div className='navbar'>
            <div className="navLeftContainer">
                <NavLink href={route("idea.index")} active={route().current("idea.index")} className={route().current("idea.index") ? 'navLinkActive' : 'navLink'}>
                    All Ideas
                </NavLink>

                <NavLink href={route("status.open")} active={route().current("status.open")} className={route().current("status.open") ? 'navLinkActive' : 'navLink'}>
                    Open
                </NavLink>

                <NavLink href={route("status.considering")} active={route().current("status.considering")} className={route().current("status.considering") ? 'navLinkActive' : 'navLink'}>
                    Considering
                </NavLink>
                <NavLink href={route("status.inProgress")} active={route().current("status.inProgress")} className={route().current("status.inProgress") ? 'navLinkActive' : 'navLink'}>
                    In Progress
                </NavLink>
            </div>
            <div className="navRightContainer">
                <NavLink href={route("status.implemented")} active={route().current("status.implemented")} className={route().current("status.implemented") ? 'navLinkActive' : 'navLink'}>
                    Implemented
                </NavLink>
                <NavLink href={route("status.closed")} active={route().current("status.closed")} className={route().current("status.closed") ? 'navLinkActive' : 'navLink'}>
                    Closed
                </NavLink>
            </div>

        </div>

    )
}

export default NavigationBar
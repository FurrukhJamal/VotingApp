import React from 'react'
import NavLink from './NavLink'
import { Link } from '@inertiajs/react'
import ApplicationLogo from './ApplicationLogo'
import "../../css/navigationBar.css"

function NavigationBar({ auth, statusCounts }) {
    return (
        <div className='navbar'>
            <div className="navLeftContainer">
                <NavLink href={route("idea.index")} active={route().current("idea.index")} className={route().current("idea.index") ? 'navLinkActive' : 'navLink'}>
                    All Ideas ({statusCounts.all_counts})
                </NavLink>

                <NavLink dusk="statusFilterOpen" href={route("status.open")} active={route().current("status.open")} className={route().current("status.open") ? 'navLinkActive' : 'navLink'}>
                    Open ({statusCounts.statusOpen})
                </NavLink>

                <NavLink dusk="statusFilterConsidering" href={route("status.considering")} active={route().current("status.considering")} className={route().current("status.considering") ? 'navLinkActive' : 'navLink'}>
                    Considering ({statusCounts.statusConsidering})
                </NavLink>
                <NavLink href={route("status.inProgress")} active={route().current("status.inProgress")} className={route().current("status.inProgress") ? 'navLinkActive' : 'navLink'}>
                    In Progress ({statusCounts.statusInProgress})
                </NavLink>
            </div>
            <div className="navRightContainer">
                <NavLink href={route("status.implemented")} active={route().current("status.implemented")} className={route().current("status.implemented") ? 'navLinkActive' : 'navLink'}>
                    Implemented ({statusCounts.statusImplemented})
                </NavLink>
                <NavLink href={route("status.closed")} active={route().current("status.closed")} className={route().current("status.closed") ? 'navLinkActive' : 'navLink'}>
                    Closed ({statusCounts.statusClosed})
                </NavLink>
            </div>

        </div>

    )
}

export default NavigationBar
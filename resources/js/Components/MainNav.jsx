import React from 'react'
import { Link } from '@inertiajs/react'
import ApplicationLogo from './ApplicationLogo'

function MainNav() {
  return (
    <header>
        <Link href = {route("home")}>
            <ApplicationLogo/>
        </Link> 
        <div className="navLogRegContainer">
            <div>
                <Link
                    href={route('login')}
                    className="link"
                >
                    Log in
                </Link>

                <Link
                    href={route('register')}
                    className="link"
                >
                    Register
                </Link>
                    
            </div>
            <Link href = "#">
                <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp" alt="avatar"/>
            </Link>
            
        </div>
    </header>

  )
}

export default MainNav
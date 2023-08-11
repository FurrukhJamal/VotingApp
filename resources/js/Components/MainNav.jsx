import React from 'react'
import { Link } from '@inertiajs/react'
import ApplicationLogo from './ApplicationLogo'
import "../../css/index.css"

function MainNav(user) {
    console.log("user in MainNav : ", user)
    return (
        <header>
            <Link href={route("idea.index")}>
                <ApplicationLogo />
            </Link>
            <div className="navLogRegContainer">
                <div>
                    {user ? (
                        <Link
                            href={route('logout')}
                            method="post"
                            className="link"
                            as="button"
                        >
                            Log Out
                        </Link>
                    ) : (
                        <>
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
                        </>
                    )
                    }
                </div>
                <Link href="#">
                    <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp" alt="avatar" />
                </Link>

            </div>
        </header>

    )
}

export default MainNav
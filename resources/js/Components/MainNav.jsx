import React from 'react'
import { Link } from '@inertiajs/react'
import ApplicationLogo from './ApplicationLogo'
import "../../css/index.css"
import NavLink from './NavLink'
import Dropdown from './Dropdown'


function MainNav(user, { avatar }) {
    console.log("user in MainNav : ", user)
    return (
        <header>
            <Link href={route("idea.index")}>
                <ApplicationLogo />
            </Link>

            <div className="flex justify-between ml-4 items-center w-full">
                <div className="p-2 text-sm">
                    <NavLink href={route('dashboard')} active={route().current('dashboard')}>
                        Dashboard
                    </NavLink>
                </div>
                <div className="navLogRegContainer">
                    <div className=" flex justify-end">
                        {user.id ? (

                            <div className=" text-gray-500 flex justify-end">
                                <Dropdown >
                                    <Dropdown.Trigger>
                                        <span className="inline-flex rounded-md ">
                                            <button
                                                type="button"
                                                className="inline-flex items-center px-3 py-2  text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-50 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {user.name}

                                                <svg
                                                    className="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fillRule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clipRule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </Dropdown.Trigger>

                                    <Dropdown.Content>
                                        <Dropdown.Link href={route('profile.edit')}>Profile</Dropdown.Link>
                                        <Dropdown.Link href={route('logout')} method="post" as="button">
                                            Log Out
                                        </Dropdown.Link>
                                    </Dropdown.Content>
                                </Dropdown>
                            </div>




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
                        <img src={avatar} alt="avatar" />
                    </Link>

                </div>
            </div>
        </header>

    )
}

export default MainNav



{/* <Link
                                    href={route('logout')}
                                    method="post"
                                    className="link"
                                    as="button"
                                >
                                    Log Out
                                </Link> */}
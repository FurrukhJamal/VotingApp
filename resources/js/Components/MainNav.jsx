import React, { useState } from 'react'
import { Link } from '@inertiajs/react'
import ApplicationLogo from './ApplicationLogo'
import "../../css/index.css"
import NavLink from './NavLink'
import Dropdown from './Dropdown'


function MainNav(props) {
    console.log("user in MainNav : ", props.user)
    console.log("avatar is, :", props.avatar)
    const [showNotifications, setShowNotifications] = useState(false)

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
                        {props.user?.id ? (
                            <div className='flex items-center'>
                                <div className=" text-gray-500 flex justify-end">
                                    <Dropdown >
                                        <Dropdown.Trigger>
                                            <span className="inline-flex rounded-md ">
                                                <button
                                                    type="button"
                                                    className="inline-flex items-center px-3 py-2  text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-50 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                                >
                                                    {props.user.name}

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

                                {/* Notification Bell */}
                                <div className='relative flex justify-center'>
                                    <button
                                        onClick={() => setShowNotifications(prev => !prev)}>
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24"
                                            strokeWidth={1.5}
                                            stroke="currentColor"
                                            className="w-8 h-8 text-gray-400">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                        </svg>
                                        <div className="-top-1 -right-1 border-2 flex items-center justify-center absolute rounded-full bg-red-500 text-white text-xs w-6 h-6">
                                            8
                                        </div>

                                    </button>

                                    {/* The Div that appears after clicking notification bell */}
                                    {showNotifications && (
                                        <div className='z-10  absolute top-14 w-64 rounded-xl bg-white'>
                                            <ul className='max-h-128 overflow-y-auto'>
                                                <li className=' py-4 p-0 rounded-xl hover:bg-gray-200 hover:cursor-pointer flex justify-center'>
                                                    <div className="flex">
                                                        <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&s=50" />
                                                        <div className="ml-4">
                                                            <div className='text-xs line-clamp-6'>
                                                                <span className='font-semibold'>UserName </span>
                                                                commented on <span className='font-semibold'>Idea title</span>
                                                                : Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque iusto et similique temporibus, ducimus explicabo minima velit totam, ratione hic quod? Amet tenetur possimus exercitationem voluptatibus iste laboriosam debitis. Pariatur.
                                                            </div>
                                                            <div className='text-xm text-gray-500 mt-3'>1 hour ago</div>
                                                        </div>
                                                    </div>

                                                </li>

                                                <li className=' py-4 p-0 rounded-xl hover:bg-gray-200 hover:cursor-pointer flex justify-center'>
                                                    <div className="flex">
                                                        <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&s=50" />
                                                        <div className="ml-4">
                                                            <div className='text-xs'>
                                                                <span className='font-semibold'>UserName </span>
                                                                commented on <span className='font-semibold'>Idea title</span>
                                                                : Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque iusto et similique temporibus, ducimus explicabo minima velit totam, ratione hic quod? Amet tenetur possimus exercitationem voluptatibus iste laboriosam debitis. Pariatur.
                                                            </div>
                                                            <div className='text-xm text-gray-500 mt-3'>1 hour ago</div>
                                                        </div>
                                                    </div>

                                                </li>

                                                <li className=' py-4 p-0 rounded-xl hover:bg-gray-200 hover:cursor-pointer flex justify-center'>
                                                    <div className="flex">
                                                        <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&s=50" />
                                                        <div className="ml-4">
                                                            <div className='text-xs'>
                                                                <span className='font-semibold'>UserName </span>
                                                                commented on <span className='font-semibold'>Idea title</span>
                                                                : Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque iusto et similique temporibus, ducimus explicabo minima velit totam, ratione hic quod? Amet tenetur possimus exercitationem voluptatibus iste laboriosam debitis. Pariatur.
                                                            </div>
                                                            <div className='text-xm text-gray-500 mt-3'>1 hour ago</div>
                                                        </div>
                                                    </div>

                                                </li>
                                                <li className='border-t-2 text-center py-4'>
                                                    <Link className='link'>
                                                        Mark All As Read
                                                    </Link>
                                                </li>
                                            </ul>
                                        </div>
                                    )}


                                </div>
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
                        <img src={props.avatar} alt="avatar" />
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
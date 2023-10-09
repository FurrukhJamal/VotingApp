import React, { useContext, useEffect, useState } from 'react'
import { Link, useForm } from '@inertiajs/react'
import ApplicationLogo from './ApplicationLogo'
import "../../css/index.css"
import NavLink from './NavLink'
import Dropdown from './Dropdown'
import dayjs from 'dayjs'
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime)

function MainNav(props) {
    console.log("user in MainNav : ", props.user)
    console.log("avatar is, :", props.avatar)
    const [showNotifications, setShowNotifications] = useState(false)
    // const [showUserNotifications, setShowUserNotifications] = useState(false)
    const [notifications, setNotifications] = useState([])
    const [hasUserCheckedHisNotifications, setHasUserCheckedHisNotifications] = useState(false)
    const [numberOfNotifications, setNumberOfNotifications] = useState(0)

    const { data, setData, post } = useForm({
        "user": props.user
    })

    useEffect(() => {
        //getting notifications
        console.log("useeffect of mainNAv")
        let timeoutId
        async function getNotifications() {
            let path = window.location.origin + "/api/getnotifications"
            let response = await fetch(path, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    "user": props.user
                })
            })
            let result = await response.json()
            return result
        }
        if (props.user) {
            timeoutId = setTimeout(async () => {
                let result = await getNotifications()
                console.log("RESULT NOTIFICATION IN MAIN NAV:", result)
                setNotifications(prev => ([...result.notifications]))
                setNumberOfNotifications(result.numberOfNotifications)

                if (result.user_hasNotifications == 0) { //checking for false condition
                    setHasUserCheckedHisNotifications(prev => !prev)
                }


            }, 1500)


        }

        return () => clearTimeout(timeoutId)

    }, [props.user])




    function handleBellIconClick() {
        //remove notification badge if it was displaying
        if (!hasUserCheckedHisNotifications) {
            setHasUserCheckedHisNotifications(true)
            // //send a post request to the server to inform notifications have been checked
            if (!hasUserCheckedHisNotifications) {
                post(route("notifications.checked"))
            }
            // post(route("notifications.checked"))
        }
        setShowNotifications(prev => !prev)
    }


    function markNotifactionsAsRead() {
        post(route("notifications.markread"))
    }


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
                                                    {props.user?.name}

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
                                        onClick={handleBellIconClick}>
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24"
                                            strokeWidth={1.5}
                                            stroke="currentColor"
                                            className="w-8 h-8 text-gray-400">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                        </svg>

                                        {(numberOfNotifications > 0 && !hasUserCheckedHisNotifications) ? (
                                            <div className="-top-1 -right-1 border-2 flex items-center justify-center absolute rounded-full bg-red-500 text-white text-xs w-6 h-6">
                                                {numberOfNotifications > 6 ? "6+" : numberOfNotifications}
                                            </div>
                                        ) : null}


                                    </button>

                                    {/* The Div that appears after clicking notification bell */}
                                    {showNotifications && (
                                        <div className='z-10  absolute top-14 w-76 rounded-xl bg-white'>
                                            <ul className='max-h-128 overflow-y-auto'>
                                                {notifications.map((notification, index) => {
                                                    return (
                                                        <li key={index} className=' py-4 p-0 rounded-xl hover:bg-gray-200 hover:cursor-pointer flex justify-center'>
                                                            <Link href={route("idea.show", notification.data.idea_slug)}>
                                                                <div className="flex">
                                                                    <img src={notification.data.user_avatar} />
                                                                    <div className="ml-4">
                                                                        <div className='text-xs line-clamp-6'>
                                                                            <span className='font-semibold'>{notification.data.user_name} </span>
                                                                            commented on <span className='font-semibold'>{notification.data.idea_title}</span>
                                                                            : {notification.data.comment_body}
                                                                        </div>
                                                                        <div className='text-sm text-gray-500 mt-3'>{dayjs(notification.created_at).fromNow()}</div>
                                                                    </div>
                                                                </div>
                                                            </Link>


                                                        </li>
                                                    )
                                                })}

                                                {notifications.length == 0 && (
                                                    <li className=' py-4 p-0 rounded-xl flex justify-center'>
                                                        No New Notifications
                                                    </li>
                                                )}

                                                {notifications.length > 0 && (
                                                    <li className='border-t-2 text-center py-4'>
                                                        <Link
                                                            className='link hover:bg-slate-800'
                                                            as="button"
                                                            onClick={markNotifactionsAsRead}>
                                                            Mark All As Read
                                                        </Link>
                                                    </li>
                                                )}


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
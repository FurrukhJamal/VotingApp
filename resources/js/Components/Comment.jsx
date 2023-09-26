import React from 'react'
import { Link } from '@inertiajs/react'
import Dropdown from './Dropdown'
import PrimaryButton from './PrimaryButton'
import dayjs from 'dayjs'
import relativeTime from "dayjs/plugin/relativeTime"

dayjs.extend(relativeTime)

function Comment({ isAdmin, idea, comment }) {
    return (

        <div className={`w-full bg-white ${isAdmin ? "border-2 border-blue-200" : ""} rounded-xl flex`}>

            <div className="flex flex-1 px-4 py-6">
                <div className="flex-none">
                    <Link className="flex-none" href={route("profile.edit")}>

                        <img
                            src={comment.user.userAvatar}
                            alt="avatar"
                            className='w-14 h-14 rounded-xl' />
                    </Link>
                    {isAdmin ? (
                        <h4 className="text-blue-600 text-sm font-bold mt-2 w-14 text-center">Admin Name</h4>
                    ) : (
                        <h4 className="text-gray-500 text-sm  mt-2 w-14 text-center">{comment.user.name}</h4>
                    )}

                </div>

                <div className="mx-4 w-full">
                    {
                        isAdmin && (
                            <h1 className='text-xl font-semibold'>A random title </h1>
                        )
                    }
                    {/* <Link href="#" className="hover:underline">
                        <h1 className='text-xl font-semibold'>A random title </h1>
                    </Link> */}
                    <div className='text-gray-600 mt-3 '>
                        <p>{comment.body}</p>
                    </div>

                    <div className="flex mt-6 items-center justify-between">
                        <div className="flex items-center text-gray-400 text-xs font-semibold space-x-2">
                            <div className={`font-bold ${isAdmin ? "text-blue-600" : "text-gray-800"}`}>Jon Doe</div>
                            <div>&bull;</div>
                            <div>{dayjs(comment.created_at).fromNow()}</div>

                        </div>

                        <div className="flex items-center space-x-2">

                            <Dropdown>
                                <Dropdown.Trigger>
                                    <PrimaryButton className='rounded-full h-7 bg-gray-400 transition duration-150 ease-in'>...</PrimaryButton>
                                </Dropdown.Trigger>
                                <Dropdown.Content className="shahdow-dialogue" align="left" width="w-44">
                                    <Link className="text-center w-full justify-center" href="" as="button">Mark as spam</Link>
                                    <Link className="text-center w-full justify-center" href="" as="button">Delete Post</Link>

                                </Dropdown.Content>
                            </Dropdown>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    )

}

export default Comment
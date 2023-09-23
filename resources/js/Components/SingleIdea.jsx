import React from 'react'
import PrimaryButton from './PrimaryButton'
import Dropdown from './Dropdown'
import { Link } from '@inertiajs/react'
import dayjs from 'dayjs'
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime)

function SingleIdea({ isAdmin, auth, idea, setEditIdeaButtonActivated, setDeleteIdeaActivated }) {
    console.log("Auth in singleIdea component", auth)

    function handleIdeaEdit(e) {
        e.preventDefault()
        setEditIdeaButtonActivated(prev => !prev)
    }

    function handleDeleteIdea(e) {
        e.preventDefault()
        setDeleteIdeaActivated(prev => !prev)
    }

    return (
        <div className="space-y-4 my-4">
            <div className="bg-white rounded-xl flex">

                <div className="flex flex-1 px-4 py-6">
                    <div className="flex-none">
                        <Link className="flex-none" href={route("profile.edit")}>

                            <img
                                src={idea.profileLink}
                                alt="avatar"
                                className='w-14 h-14 rounded-xl' />
                        </Link>
                    </div>

                    <div className="mx-4 w-full">
                        <Link href="#" className="hover:underline">
                            <h1 className='text-xl font-semibold'>{idea.title}</h1>
                        </Link>
                        <div className='text-gray-600 mt-3'>
                            <p>{idea.description}</p>
                        </div>

                        <div className="flex mt-6 items-center justify-between">
                            <div className="flex items-center text-gray-400 text-xs font-semibold space-x-2">
                                <div className="font-bold text-gray-800">{idea.user.name}</div>
                                <div>&bull;</div>
                                <div>{dayjs(idea.created_at).fromNow()}</div>
                                <div>&bull;</div>
                                <div>{idea.category.name}</div>
                                <div>&bull;</div>
                                <div className="text-gray-900">3 comments</div>
                                <div>&bull;</div>
                            </div>

                            <div className="flex items-center space-x-2">
                                <div className={`flex justify-center ${idea.statusClass} text-xxs items-center font-bold uppercase rounded-full h-7 text-center py-2 px-4`}>
                                    {idea.status.name}
                                </div>
                                {auth.user && (
                                    <div dusk="ideaFunctions">
                                        <Dropdown >
                                            <Dropdown.Trigger>
                                                <PrimaryButton className='rounded-full h-7 bg-gray-400 transition duration-150 ease-in'>...</PrimaryButton>
                                            </Dropdown.Trigger>
                                            <Dropdown.Content className="shahdow-dialogue" align="left" width="w-44">
                                                {idea.userCanEdit && (
                                                    <div dusk="editIdeaButton">
                                                        <Link
                                                            className="text-center w-full justify-center hover:bg-green-200"
                                                            href=""
                                                            as="button"
                                                            onClick={handleIdeaEdit}>
                                                            Edit Idea
                                                        </Link>
                                                    </div>

                                                )}
                                                {(idea.userCanEdit || isAdmin) ? (
                                                    <Link
                                                        className="text-center w-full justify-center  hover:bg-green-200"
                                                        href=""
                                                        as="button"
                                                        onClick={handleDeleteIdea}>
                                                        Delete Idea
                                                    </Link>
                                                ) : null}

                                                <Link
                                                    className="text-center w-full justify-center  hover:bg-green-200"
                                                    href=""
                                                    as="button"
                                                    onClick={handleDeleteIdea}>
                                                    Mark as Spam
                                                </Link>




                                            </Dropdown.Content>
                                        </Dropdown>
                                    </div>

                                )}


                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    )
}

export default SingleIdea
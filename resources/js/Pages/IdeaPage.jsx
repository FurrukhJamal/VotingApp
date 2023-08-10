import React from 'react'
import "../../css/index.css"
import MainLayOut from '@/Layouts/MainLayOut'
import { Link } from '@inertiajs/react'
import NavigationBar from '@/Components/NavigationBar'
import SingleIdea from '@/Components/SingleIdea'
import PrimaryButton from '@/Components/PrimaryButton'
import Dropdown from '@/Components/Dropdown'
import Comment from '@/Components/Comment'
import "../../css/app.css"

function IdeaPage() {
    return (
        <>

            <MainLayOut>
                <NavigationBar></NavigationBar>
                <div className="mt-3 hover:underline items-center flex">

                    <Link className="flex" href="/" as="button" >
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" className="w-6 h-6">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>

                        </span>
                        Go Back
                    </Link>
                </div>
                <SingleIdea></SingleIdea>
                {/* Buttons */}
                <div className="items-center flex mt-3 w-full justify-between">
                    <div className="flex w-2/5">
                        <PrimaryButton className=" mr-4 bg-myBlue rounded-xl w-32 justify-center">Reply</PrimaryButton>
                        <Dropdown className="w-full bg-blue-200 rounded-xl">
                            <Dropdown.Trigger>
                                <span className="inline-flex rounded-xl w-full justify-center">
                                    <button
                                        type="button"
                                        className="w-full justify-center flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                    >
                                        Set Status

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
                                <Dropdown.Link className="text-center" href={route('profile.edit')}>Status 1</Dropdown.Link>
                                <Dropdown.Link className="text-center" href={route('logout')} method="post" as="button">
                                    Status 2
                                </Dropdown.Link>
                            </Dropdown.Content>
                        </Dropdown>
                    </div>
                    {/* right side button */}
                    <div className="w-1/3 flex justify-between items-center">
                        <PrimaryButton className="w-2/6 bg-fuchsia-200">12 Votes</PrimaryButton>
                        <PrimaryButton className=" w-2/5 rounded-2xl justify-center py-3 bg-gray-300">Vote</PrimaryButton>
                    </div>
                    {/* end of right side buttons */}
                </div>
                {/* End of Buttons */}

                {/* comments container */}
                <div className="relative">
                    <div className="mt-8 ml-22 space-y-6 commentLineClass" >
                        <div className="commentContainer"><Comment></Comment></div>
                        <div className="is-admin commentContainer"><Comment admin={true} /></div>
                        <div className="commentContainer relative"><Comment /></div>

                    </div>
                </div>

                {/* end of comments */}
            </MainLayOut>
        </>
    )
}

export default IdeaPage
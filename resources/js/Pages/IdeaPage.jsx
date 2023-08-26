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
import ButtonWithADailogue from '@/Components/ButtonWithADailogue'
import SetStatusDropdown from '@/Components/SetStatusDropdown'

function IdeaPage({ auth, idea, categories }) {
    console.log("A single idea in IdeaPage: ", idea)
    console.log("auth in single idea page: ", auth)
    console.log("categories in single Page: ", categories)
    return (
        <>

            <MainLayOut user={auth.user} categories={categories}>
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
                <SingleIdea {...idea}></SingleIdea>
                {/* Buttons */}
                <div className="items-center flex mt-3 w-full justify-between">
                    <div className="flex w-2/5">
                        <ButtonWithADailogue></ButtonWithADailogue>
                        <SetStatusDropdown />
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
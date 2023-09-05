import React from 'react'
import "../../css/index.css"
import MainLayOut from '@/Layouts/MainLayOut'
import { Link, router } from '@inertiajs/react'
import NavigationBar from '@/Components/NavigationBar'
import SingleIdea from '@/Components/SingleIdea'
import PrimaryButton from '@/Components/PrimaryButton'
import Dropdown from '@/Components/Dropdown'
import Comment from '@/Components/Comment'
import "../../css/app.css"
import ButtonWithADailogue from '@/Components/ButtonWithADailogue'
import SetStatusDropdown from '@/Components/SetStatusDropdown'

function IdeaPage({ auth, idea, categories, avatar }) {
    console.log("A single idea in IdeaPage: ", idea)
    console.log("auth in single idea page: ", auth)
    console.log("categories in single Page: ", categories)

    async function handleVoteSubmit(e, idea) {
        console.log("vote button clicked")
        console.log("when vote clicked in single idea page, idea object is:", idea)
        if (!auth.user) {
            router.get(route("login"))
        }
        else {
            if (idea.isVotedByUser) {
                //remove the vote
                let path = window.location.origin + "/api"
                let response = await fetch(`${path}/deletevote`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",

                    },
                    body: JSON.stringify({
                        "user_id": auth.user.id,
                        "idea_id": idea.id,
                    })
                })

                let result = await response.json()
                console.log(result)
                if (result.success) {
                    router.reload()
                }
                else if (result.error) {
                    router.reload()
                }

            }
            else {
                //add the vote
                console.log("route for home: ", window.location.origin)
                let path = window.location.origin + "/api"
                let response = await fetch(`${path}/vote`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",

                    },
                    body: JSON.stringify({
                        "user_id": auth.user.id,
                        "idea_id": idea.id,
                    })
                })

                let result = await response.json()
                console.log(result)
                if (result.success) {
                    router.reload()
                }
                else if (result.error) {
                    router.reload()
                }

            }


        }
    }


    function goBack(e) {
        e.preventDefault()
        //go to the exact filter selected page, not just the idea.index route
        window.history.back()
    }

    return (
        <>

            <MainLayOut avatar={avatar} user={auth.user} categories={categories}>
                <NavigationBar></NavigationBar>
                <div className="mt-3 hover:underline items-center flex">

                    <Link dusk="goBackLink" className="flex" onClick={(e) => goBack(e)} href="/" >
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
                        <div dusk="ideaPageVoteCount" className={`w-3/6 flex justify-center p-2 ${idea.isVotedByUser && "bg-blue-600 text-white"}`}>{idea.votes_count} Votes</div>
                        <PrimaryButton
                            onClick={(e) => handleVoteSubmit(e, idea)}
                            dusk="IdeaPageVoteButton"

                            className={`${idea.isVotedByUser ? " bg-blue-600" : "bg-gray-800"} w-2/5 rounded-2xl justify-center py-3 bg-gray-300`}>
                            {idea.isVotedByUser ? "Voted" : "Vote"}
                        </PrimaryButton>
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
            </MainLayOut >
        </>
    )
}

export default IdeaPage
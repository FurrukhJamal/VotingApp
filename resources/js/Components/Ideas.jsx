import React, { useEffect } from 'react'
import PrimaryButton from './PrimaryButton'
import { Link, router } from '@inertiajs/react'
import Dropdown from './Dropdown'
import dayjs from 'dayjs'
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime)

function Ideas({ ideas, user }) {
    console.log("ideas in ideas component:", ideas)
    console.log("ideas.links in ideas component", ideas.links)
    const data = ideas.data

    function handleIdeaClick(idea) {
        console.log("the idea clicked is :", idea)
        router.get(route("idea.show", idea))
    }

    function stopPropagation(e) {
        console.log("stop propagation clicked")
        e.stopPropagation()
    }

    async function handleSubmitVote(e, idea) {
        e.stopPropagation()
        console.log("vote button clicked")
        if (!user) {
            router.get(route("login"))
        }
        else {
            console.log("Voted for idea: ", idea)
            if (idea.isVotedByUser) {
                //remove vote
                let path = window.location.origin + "/api"
                let response = await fetch(`${path}/deletevote`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",

                    },
                    body: JSON.stringify({
                        "user_id": user.id,
                        "idea_id": idea.id,
                        // "user": idea.user
                    })
                })

                let result = await response.json()
                console.log(result)
                if (result.success) {
                    router.reload({ only: ["ideas"] })
                }
                else if (result.error) {
                    router.reload()
                }
            }
            else {
                //add a vote
                let path = window.location.origin + "/api"
                let response = await fetch(`${path}/vote`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",

                    },
                    body: JSON.stringify({
                        "user_id": user.id,
                        "idea_id": idea.id,
                        // "user": idea.user
                    })
                })

                let result = await response.json()
                console.log(result)
                if (result.success) {
                    router.reload({ only: ["ideas"] })
                }
                else if (result.error) {
                    router.reload()
                }
            }

        }
    }

    return (
        <>
            {
                ideas.data.length > 0 ? (
                    ideas.data.map((idea, index, data) => {
                        return (
                            /* start of idea container */
                            <div {...(index == 0 ? { dusk: "ideaOnTopOfPage" } : "")} key={index} onClick={() => handleIdeaClick(idea)} className="space-y-4 my-4">
                                {/* <Link href={route("idea.show", idea)}> */}

                                <div className="bg-white cursor-pointer hover:shadow-card transition duration-150 ease-in rounded-xl flex">
                                    <div className="border-r border-gray-100 px-5 py-8">
                                        <div className='text-center'>
                                            <div dusk="votesCount" className={`font-semibold text-2xl ${idea.isVotedByUser && "text-blue-600"}`}>
                                                {idea.votes_count}
                                            </div>
                                            <div className={idea.isVotedByUser ? "text-blue-600" : "text-gray-500"} >
                                                Votes
                                            </div>
                                            <div className="mt-8">
                                                <PrimaryButton
                                                    onClick={(e) => handleSubmitVote(e, idea)}
                                                    dusk="VoteButton"

                                                    className={`w-20 ${idea.isVotedByUser ? "bg-blue-400 focus:bg-blue-400" : "bg-gray-800"} font-bold text-xs uppercase`}>
                                                    {idea.isVotedByUser ? "Voted" : "Vote"}
                                                </PrimaryButton>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="flex px-2 py-6">
                                        <div className="flex-none">
                                            <Link onClick={stopPropagation} className="flex-none" href={route("profile.edit")}>

                                                <img
                                                    src={idea.profileLink}
                                                    alt="avatar"
                                                    className='w-14 h-14 rounded-xl' />
                                            </Link>
                                        </div>

                                        <div className="mx-4">
                                            <Link dusk="IdeaTitle" href={`/ideas/${idea.slug}`} className="hover:underline">
                                                <h1 {...(index == 0 ? { dusk: "titleOnTopOfPage" } : "")} className='text-xl font-semibold'>{idea.title}</h1>
                                            </Link>
                                            <div className='text-gray-600 mt-3 line-clamp-3'>
                                                <p {...(index == 0 ? { dusk: "descriptionOnTopOfPage" } : "")}>{idea.description}</p>
                                            </div>

                                            <div className="flex mt-6 items-center justify-between">
                                                <div className="flex items-center text-gray-400 text-xs font-semibold space-x-2">
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
                                                    <Dropdown onClick={stopPropagation}>
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
                                {/* </Link> */}
                            </div>
                            /* end of ideas container */
                        )
                    })
                ) : (
                    <div className='font-bold mt-3 justify-center flex'>No ideas to display</div>
                )

            }




        </>
    )
}

export default Ideas
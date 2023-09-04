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
                let response = await fetch("api/deletevote", {
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
                let response = await fetch("api/vote", {
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
                ideas.data.map((idea, index) => {
                    return (
                        /* start of idea container */
                        <div key={index} onClick={() => handleIdeaClick(idea)} className="space-y-4 my-4">
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
                                            <h1 className='text-xl font-semibold'>{idea.title}</h1>
                                        </Link>
                                        <div className='text-gray-600 mt-3 line-clamp-3'>
                                            <p>{idea.description}</p>
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
            }


            {/* start of 2nd indeas container */}
            <div className="space-y-4 my-4">
                <div className="bg-white cursor-pointer hover:shadow-card transition duration-150 ease-in rounded-xl flex">
                    <div className="border-r border-gray-100 px-5 py-8">
                        <div className='text-center'>
                            <div className='text-myBlue font-semibold text-2xl'>
                                12
                            </div>
                            <div className="text-myBlue">
                                Votes
                            </div>
                            <div className="mt-8">
                                <PrimaryButton className="w-20 bg-myBlue font-bold text-xs uppercase">Vote</PrimaryButton>
                            </div>
                        </div>
                    </div>

                    <div className="flex px-2 py-6">
                        <Link className="flex-none" href={route("profile.edit")}>

                            <img
                                src="https://source.unsplash.com/200x200/?face&crop=face&v=1"
                                alt="avatar"
                                className='w-14 h-14 rounded-xl' />
                        </Link>
                        <div className="mx-4">
                            <Link href="#" className="hover:underline">
                                <h1 className='text-xl font-semibold'>Another random title </h1>
                            </Link>
                            <div className='text-gray-600 mt-3 line-clamp-3'>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore nemo suscipit fugit quibusdam perferendis veritatis qui ad! Aspernatur est iusto praesentium et? Doloribus vitae libero adipisci quis magni, veritatis ea.</p>
                            </div>

                            <div className="flex mt-6 items-center justify-between">
                                <div className="flex items-center text-gray-400 text-xs font-semibold space-x-2">
                                    <div>10 hours ago</div>
                                    <div>&bull;</div>
                                    <div>Category 1</div>
                                    <div>&bull;</div>
                                    <div className="text-gray-900">3 comments</div>
                                    <div>&bull;</div>
                                </div>

                                <div className="flex items-center space-x-2">
                                    <div className="flex justify-center bg-myyellow text-xxs items-center font-bold uppercase rounded-full w-fit h-7 text-center py-2 px-4">
                                        In Process
                                    </div>
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
            </div>
            {/* end of second ideas container */}

        </>
    )
}

export default Ideas
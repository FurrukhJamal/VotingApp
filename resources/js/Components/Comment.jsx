import React, { useEffect } from 'react'
import { Link } from '@inertiajs/react'
import Dropdown from './Dropdown'
import PrimaryButton from './PrimaryButton'
import dayjs from 'dayjs'
import relativeTime from "dayjs/plugin/relativeTime"

dayjs.extend(relativeTime)

function Comment({ setAdminPostBadgeStyleVariable, setResourceToDelete, setDeleteResourceActivated, setCommentToEditId, setCommentToEdit, isAdmin, idea, comment, setEditCommentButtonActivated }) {

    // useEffect(() => {
    //     //setting the appropriate admin badge colors for status change comments 
    //     if (comment.status_update_comment == "Open") {
    //         setAdminPostBadgeStyleVariable({
    //             "--background-color-option": "#e5e7eb"  //gray-200
    //         })
    //     }
    //     else if (comment.status_update_comment == "Considering") {
    //         setAdminPostBadgeStyleVariable({
    //             "--background-color-option": "#eab308"  //yellow-500
    //         })
    //     }
    //     else if (comment.status_update_comment == "In Progress") {
    //         setAdminPostBadgeStyleVariable({
    //             "--background-color-option": "#0284c7"  //sky-600
    //         })
    //     }
    //     else if (comment.status_update_comment == "Implemented") {
    //         setAdminPostBadgeStyleVariable({
    //             "--background-color-option": "#bbf7d0"  //green-200
    //         })
    //     }
    //     else if (comment.status_update_comment == "Closed") {
    //         setAdminPostBadgeStyleVariable({
    //             "--background-color-option": "#ef4444"  //red-500
    //         })
    //     }

    // }, [])



    function handleEditCommentClicked(e) {
        e.preventDefault()
        //send what comment to edit a component level up
        console.log("comment in COMMENT.jsx", comment)
        setCommentToEdit(prev => ({ ...comment }))
        setCommentToEditId(comment.id)

        setEditCommentButtonActivated(true)
    }

    function handleDeleteComment(e) {
        e.preventDefault()
        setDeleteResourceActivated(prev => !prev)
        setResourceToDelete("comment")
        setCommentToEditId(comment.id)
    }
    return (

        <div className={`w-full bg-white ${comment.ifAuthorIsAdmin ? "border-2 border-blue-200" : ""} rounded-xl flex`}>

            <div className="flex flex-1 px-4 py-6 transition duration-500 ease-in">
                <div className="flex-none">
                    <Link className="flex-none" href={route("profile.edit")}>

                        <img
                            src={comment.user.userAvatar}
                            alt="avatar"
                            className='w-14 h-14 rounded-xl' />
                    </Link>
                    {comment.ifAuthorIsAdmin ? (
                        <h4 className="text-blue-600 text-sm font-bold mt-2 w-14 text-center">{comment.user.name}</h4>
                    ) : (
                        <h4 className="text-gray-500 text-sm  mt-2 w-14 text-center">{comment.user.name}</h4>
                    )}

                </div>

                <div className="mx-4 w-full">
                    {
                        (comment?.status_update_comment == 1) && (
                            <h1 className='text-xl font-semibold'>Status Updated</h1>
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
                            <div className={`font-bold ${comment.ifAuthorIsAdmin ? "text-blue-600" : "text-gray-800"}`}>{comment.user.name}</div>
                            <div>&bull;</div>
                            {(comment.user.id == idea.user.id) && (
                                <>
                                    <div dusk="authorsComment" className="rounded-full bg-gray-100 px-3 py-1 border">OP</div>
                                    <div>&bull;</div>
                                </>
                            )}

                            <div>{dayjs(comment.created_at).fromNow()}</div>

                        </div>

                        <div className="flex items-center space-x-2">
                            {(comment.editableByUser || isAdmin) && (
                                <div dusk="editCommentSection">
                                    <Dropdown>
                                        <Dropdown.Trigger>
                                            <PrimaryButton className='rounded-full h-7 bg-gray-400 transition duration-150 ease-in'>...</PrimaryButton>
                                        </Dropdown.Trigger>
                                        <Dropdown.Content className="shahdow-dialogue" align="left" width="w-44">
                                            {comment.editableByUser && (
                                                <div dusk="editCommentButton">
                                                    <Link
                                                        className="text-center w-full justify-center"
                                                        href="" as="button"
                                                        onClick={handleEditCommentClicked}>
                                                        Edit Comment
                                                    </Link>
                                                </div>
                                            )}

                                            <div dusk="deleteCommentButton">
                                                <Link
                                                    className="text-center w-full justify-center"
                                                    href=""
                                                    as="button"
                                                    onClick={handleDeleteComment}>
                                                    Delete Comment
                                                </Link>
                                            </div>

                                        </Dropdown.Content>
                                    </Dropdown>
                                </div>
                            )}


                        </div>
                    </div>
                </div>
            </div>

        </div >

    )

}

export default Comment
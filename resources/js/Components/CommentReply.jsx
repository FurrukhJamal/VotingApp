import React, { useEffect, useRef, useState } from 'react'
import PrimaryButton from './PrimaryButton'
import { Link, useForm, usePage } from '@inertiajs/react'

function CommentReply({ idea, user }) {
    const [togglePostComment, setTogglePostComment] = useState(false)
    const commentSection = useRef()
    const { data, setData, errors, processing, post, reset } = useForm({
        "comment": "",
        "idea": idea
    })

    useEffect(() => {
        //focusing the coment text area
        if (togglePostComment) {
            commentSection.current.focus()
        }
    }, [togglePostComment])


    function toggleCommentBox() {
        setTogglePostComment(prev => !prev)
    }


    function handleCommentSubmit(e) {
        e.preventDefault()
        post(route("comment.store"), {
            onSuccess: () => {
                reset()
                setTogglePostComment(false)
            },

        })

    }

    return (
        <div className="relative">
            <div dusk="commentReplyButton">
                <PrimaryButton
                    onClick={toggleCommentBox}
                    className=" mr-4 bg-myBlue rounded-xl w-32 justify-center">
                    Reply
                </PrimaryButton>
            </div>
            {(togglePostComment && user) && (
                <div className="absolute bg-white w-3full h-52 px-4 py-4 mt-2 z-10 rounded-xl shadow-card">
                    <form onSubmit={handleCommentSubmit}>
                        <textarea
                            ref={commentSection}
                            dusk="commentSection"
                            placeholder="Share your thoughts"
                            className="bg-gray-200 w-full resize-none border-none rounded-xl"
                            rows='4'
                            value={data.comment}
                            onChange={(e) => setData("comment", e.target.value)}
                        >

                        </textarea>
                        {errors.comment && (
                            <div className="flex text-center text-xs text-red-500 mt-1 ml-1">
                                {errors.comment}
                            </div>)}
                        <div className="flex w-full mt-2">
                            <div dusk="submitCommentButton" className="w-3/5 flex justify-between">
                                <PrimaryButton type="submit" {...processing && ({ disabled: true })} className="w-full mr-4 flex border border-blue-200 hover:border-blue-400 transition duration-150 ease-in rounded-xl items-center justify-center  text-xs bg-blue-200">
                                    Submit
                                </PrimaryButton>
                            </div>
                            <button type="button" className="flex border border-gray-200 hover:border-gray-400 transition duration-150 ease-in rounded-xl items-center justify-center w-2/6 text-xs bg-gray-200">
                                <svg className="w-4 h-4 text-gray-500  transform -rotate-45" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" >
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                </svg>

                                <span className="ml-1">Attach</span>
                            </button>
                        </div>
                    </form>
                </div>

            )}

            {(togglePostComment && !user) && (
                <div className="absolute text-center bg-white w-3full px-4 py-4 mt-2 z-10 rounded-xl shadow-card">
                    <p className="font-normal">Please Log in to Post Your Comment</p>
                    <div className='mt-3 w-full flex justify-center'>
                        <div className="flex justify-around w-4/5">
                            <Link
                                className='bg-blue-600 text-white p-2 w-2/5 rounded-xl'
                                as="button"
                                href={route("login")}>
                                Log In
                            </Link>
                            <Link
                                className='bg-gray-200 p-2 w-2/5 rounded-xl'
                                as="button"
                                href={route("register")}>
                                Register
                            </Link>
                        </div>
                    </div>

                </div>

            )}


        </div>
    )
}

export default CommentReply
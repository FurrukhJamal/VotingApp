import { useForm } from '@inertiajs/react'
import React, { useEffect, useState } from 'react'
import CustomModal from './CustomModal'
import PrimaryButton from '../PrimaryButton'

function EditCommentModal({ commentId, comment, setEditCommentButtonActivated, editCommentButtonActivated }) {
    const [com, setCom] = useState("")
    const [comid, setComId] = useState("")
    const { data, setData, post, processing, errors } = useForm({
        Comment: "",
        commentId: ""
    })

    useEffect(() => {
        setCom(comment.body)
        setComId(commentId)
        setData("commentId", commentId)
    }, [commentId])



    console.log("commentID in EDITCOMMENTMODAL is", commentId)

    function handleSubmit(e) {
        e.preventDefault()

        setData("Comment", com)

        post("/updatecomment", {
            onSuccess: () => {
                setEditCommentButtonActivated(false)
            }
        })
    }

    function handleEditComment(e) {
        setCom(e.target.value)
        setData("Comment", e.target.value)
    }

    return (
        <CustomModal onClose={() => setEditCommentButtonActivated(false)} show={editCommentButtonActivated}>
            <div className='flex w-full p-4'>
                <form onSubmit={handleSubmit} className="w-full">
                    <div className="p-4 flex flex-col text-center justify-center w-full">
                        <p className="text-bold">Update Comment</p>

                        {/* Text Area */}
                        <div className="mt-4 items-center flex w-full rounded-xl ">
                            <textarea dusk="editCommentModalTextArea"
                                placeholder="Describe Your Idea"
                                className="bg-gray-200 w-full resize-none border-none rounded-xl"
                                rows='4'
                                value={com}
                                onChange={handleEditComment}
                                name="Comment">
                            </textarea>
                            {errors.comment && (<div className="flex w-full justify-center text-red-500">{errors.description}</div>)}

                        </div>
                        <div className='flex justify-center'>
                            <div dusk="commentUpdateButton" className="w-full">
                                <PrimaryButton
                                    name="submit"
                                    {...processing && { disabled: true }}
                                    type="submit"
                                    className="flex border mt-4 border-blue-200 hover:border-blue-400 transition duration-150 ease-in rounded-xl items-center h-11 justify-center w-1/2 text-xs bg-blue-600">
                                    Update
                                </PrimaryButton>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        </CustomModal>
    )
}

export default EditCommentModal
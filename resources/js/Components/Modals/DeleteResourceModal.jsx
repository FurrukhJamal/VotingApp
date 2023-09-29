import React, { useEffect } from 'react'
import CustomModal from './CustomModal'
import PrimaryButton from '../PrimaryButton'
import { router, useForm } from '@inertiajs/react'

function DeleteResourceModal({ commentId, user, idea, deleteResourceActivated, setDeleteResourceActivated }) {


    // const { data, setData, post, errors, processing } = useForm({
    //     "idea": idea,
    //     // "comment" : commentId
    // })
    function handleDeleteSubmit(e) {
        e.preventDefault()
        if (idea) {
            router.post(route("idea.destroy", idea))
        }
        else if (commentId) {
            router.post(route("comment.destroy", commentId), {}, {
                onSuccess: () => {
                    console.log("comment deleted")
                    setDeleteResourceActivated(false)
                }
            })
        }

    }

    function handleCancelDelete(e) {
        e.preventDefault()
        setDeleteResourceActivated(false)
    }

    return (
        <CustomModal onClose={() => setDeleteResourceActivated(false)} show={deleteResourceActivated}>
            <div className='flex w-full p-4' dusk="deleteResourceConfirmation">
                <div className=" w-full">
                    <p>Are you sure you want to delete the {idea ? "idea" : "comment"}?</p>

                    <div className="flex mt-4 w-full justify-end">
                        <div className="flex w-1/2 justify-end">
                            <PrimaryButton
                                className='w-1/3 flex text-center justify-center bg-gray-400  hover:bg-gray-400  focus:bg-gray-400 '
                                onClick={handleCancelDelete}>
                                Cancel
                            </PrimaryButton>
                            <div className="ml-5 w-1/3" dusk="deleteResourceConfirmationButton">
                                <PrimaryButton
                                    className='w-full flex justify-center text-center bg-blue-500 hover:bg-blue-500'
                                    type="submit"
                                    onClick={handleDeleteSubmit}>
                                    Delete
                                </PrimaryButton>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </CustomModal>
    )
}

export default DeleteResourceModal
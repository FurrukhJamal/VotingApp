import React from 'react'
import CustomModal from './CustomModal'
import PrimaryButton from '../PrimaryButton'
import { router, useForm } from '@inertiajs/react'

function DeleteIdeaModal({ user, idea, deleteIdeaActivated, setDeleteIdeaActivated }) {
    const { data, setData, post, errors, processing } = useForm({
        "idea": idea
    })
    function handleDeleteSubmit(e) {
        e.preventDefault()
        post(route("idea.destroy", idea))
    }

    function handleCancelDelete(e) {
        e.preventDefault()
        setDeleteIdeaActivated(false)
    }

    return (
        <CustomModal onClose={() => setDeleteIdeaActivated(false)} show={deleteIdeaActivated}>
            <div className='flex w-full p-4'>
                <div className=" w-full">
                    <p>Are you sure you want to delete the idea?</p>

                    <div className="flex mt-4 w-full justify-end">
                        <div className="flex w-1/2 justify-end">
                            <PrimaryButton
                                className='w-1/3 flex text-center justify-center bg-gray-400  hover:bg-gray-400  focus:bg-gray-400 '
                                onClick={handleCancelDelete}>
                                Cancel
                            </PrimaryButton>
                            <PrimaryButton
                                className='ml-5 w-1/3 flex justify-center text-center bg-blue-500 hover:bg-blue-500'
                                type="submit"
                                onClick={handleDeleteSubmit}>

                                Delete
                            </PrimaryButton>
                        </div>

                    </div>
                </div>
            </div>

        </CustomModal>
    )
}

export default DeleteIdeaModal
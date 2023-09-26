import React, { useState } from 'react'
import CustomModal from './CustomModal'
import TextInput from '../TextInput'
import PrimaryButton from '../PrimaryButton'
import { useForm } from '@inertiajs/react'

function EditIdeaModal({ setEditIdeaButtonActivated, editIdeaButtonActivated, idea }) {
    // const [processing, setProcessing] = useState(false)
    // const [title, setTitle] = useState(idea.title)
    // const [description, setDescription] = useState(idea.description)

    const { post, data, setData, errors, processing } = useForm({
        user: idea.user,
        title: idea.title,
        description: idea.description,
        ideaUpdate: true,
        ideaId: idea.id
    })

    async function handleSubmit(e) {
        e.preventDefault()
        post("/updateidea", {
            onSuccess: () => setEditIdeaButtonActivated(false)
        })
    }

    return (
        <CustomModal onClose={() => setEditIdeaButtonActivated(false)} show={editIdeaButtonActivated}>
            <div className='flex w-full p-4'>
                <form onSubmit={handleSubmit} className="w-full">
                    <div className="p-4 flex text-center justify-center w-full">
                        <p>You have one hour from creation of idea to edit it</p>
                    </div>
                    <TextInput
                        type="text"
                        name="title"
                        value={data.title}
                        className="mt-1 block w-full rounded-xl bg-gray-200"
                        placeholder="Add an Idea"
                        onChange={(e) => setData("title", e.target.value)}
                    />
                    {errors.title && (<div className="flex w-full justify-center text-red-500">{errors.title}</div>)}
                    {/* Text Area */}
                    <div className="mt-4 items-center flex w-full rounded-xl ">
                        <textarea
                            placeholder="Describe Your Idea"
                            className="bg-gray-200 w-full resize-none border-none rounded-xl"
                            rows='4'
                            value={data.description}
                            onChange={(e) => setData("description", e.target.value)}
                            name="description">
                        </textarea>
                        {errors.description && (<div className="flex w-full justify-center text-red-500">{errors.description}</div>)}

                    </div>
                    <div className='flex justify-center'>
                        <PrimaryButton
                            name="submit"
                            {...processing && { disabled: true }}
                            type="submit"
                            className="flex border mt-4 border-blue-200 hover:border-blue-400 transition duration-150 ease-in rounded-xl items-center h-11 justify-center w-1/2 text-xs bg-blue-600">
                            Update
                        </PrimaryButton>
                    </div>

                </form>
            </div>

        </CustomModal>
    )
}

export default EditIdeaModal
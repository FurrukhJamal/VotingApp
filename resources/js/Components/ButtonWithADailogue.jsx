import React, { useState } from 'react'
import PrimaryButton from './PrimaryButton'

function ButtonWithADailogue() {
    const [togglePostComment, setTogglePostComment] = useState(false)

    function toggleCommentBox() {
        setTogglePostComment(prev => !prev)
    }

    return (
        <div className="relative">
            <PrimaryButton onClick={toggleCommentBox} className=" mr-4 bg-myBlue rounded-xl w-32 justify-center">Reply</PrimaryButton>
            {togglePostComment && (
                <div className="absolute bg-white w-3full h-52 px-4 py-4 mt-2 z-10 rounded-xl shadow-card">
                    <form action="">
                        <textarea placeholder="Share your thoughts" className="bg-gray-200 w-full resize-none border-none rounded-xl" rows='4'>

                        </textarea>
                        <div className="flex w-full mt-2">
                            <PrimaryButton type="button" className="mr-4 flex border border-blue-200 hover:border-blue-400 transition duration-150 ease-in rounded-xl items-center justify-center w-3/5 text-xs bg-blue-200">
                                Submit
                            </PrimaryButton>
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




        </div>
    )
}

export default ButtonWithADailogue
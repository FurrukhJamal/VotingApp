import React from 'react'
import PrimaryButton from './PrimaryButton'
import { router } from '@inertiajs/react'

function LoginButtonToAddIdea() {
    return (
        <div className="flex justify-center mt-16 rounded-xl w-full bg-white sticky top-1/3">
            <div className="text-center px-6 py-2 pt-6 pb-6">
                <h3 className="font-semibold text-base">Add an idea</h3>
                <p className="text-xs mt-4">Log In To Add An Idea</p>
                <div className="w-full flex justify-center mt-4">
                    <PrimaryButton type="button"
                        className="flex border border-blue-200 hover:border-blue-400 
                                    transition duration-150 ease-in rounded-xl items-center h-11 
                                    justify-center w-full text-xs bg-blue-200"
                        onClick={() => router.get(route("login"))}>
                        Log In
                    </PrimaryButton>
                </div>

            </div>
        </div>
    )
}

export default LoginButtonToAddIdea
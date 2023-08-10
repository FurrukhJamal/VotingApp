import React from 'react'
import PrimaryButton from './PrimaryButton'
import Dropdown from './Dropdown'
import { Link } from '@inertiajs/react'

function SingleIdea() {
    return (
        <div className="space-y-4 my-4">
            <div className="bg-white rounded-xl flex">

                <div className="flex flex-1 px-4 py-6">
                    <div className="flex-none">
                        <Link className="flex-none" href={route("profile.edit")}>

                            <img
                                src="https://source.unsplash.com/200x200/?face&crop=face&v=1"
                                alt="avatar"
                                className='w-14 h-14 rounded-xl' />
                        </Link>
                    </div>

                    <div className="mx-4 w-full">
                        <Link href="#" className="hover:underline">
                            <h1 className='text-xl font-semibold'>A random title </h1>
                        </Link>
                        <div className='text-gray-600 mt-3'>
                            <p>sfjsdlfkjl Lorem ipsum dolor sit amet consectetur, adipisicing elit. Adipisci qui ratione totam iure, id unde molestias nulla, quod, perspiciatis eaque sequi enim iste voluptate labore magnam officiis? Debitis, unde sint? sdlkfsdfkjsdlfk dflskdjfldkjf sdlkfjsldfkl </p>
                        </div>

                        <div className="flex mt-6 items-center justify-between">
                            <div className="flex items-center text-gray-400 text-xs font-semibold space-x-2">
                                <div className="font-bold text-gray-800">Jon Doe</div>
                                <div>&bull;</div>
                                <div>10 hours ago</div>
                                <div>&bull;</div>
                                <div>Category 1</div>
                                <div>&bull;</div>
                                <div className="text-gray-900">3 comments</div>
                                <div>&bull;</div>
                            </div>

                            <div className="flex items-center space-x-2">
                                <div className="flex justify-center bg-gray-200 text-xxs items-center font-bold uppercase rounded-full w-28 h-7 text-center py-2 px-4">
                                    Open
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

    )
}

export default SingleIdea
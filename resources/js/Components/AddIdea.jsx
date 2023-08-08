import React from 'react'
import styles from "../../css/AddIdea.module.css"
import { useForm } from '@inertiajs/react'
import TextInput from './TextInput';
import Dropdown from './Dropdown';
import PrimaryButton from './PrimaryButton';

function AddIdea() {
  const{post, setData, data, errors, processing, reset} = useForm({
    idea : "",
    category : "",
    description : ""
  })
  
  function submit(event){
    event.preventDefault()
    console.log("idea submitted")
  }
  
  return (
        <div className = {styles.container}>
            <div className="text-center px-6 py-2 pt-6">
                <h3 className = "font-semibold text-base">Add an idea</h3>
                <p className = "text-xs mt-4">Let us know what you like</p>

                <form onSubmit={submit}>
                    <TextInput
                        type="text"
                        name="idea"
                        value={data.idea}
                        className="mt-1 block w-full rounded-xl bg-gray-200"
                        placeholder="Add an Idea"
                        onChange={(e) => setData('idea', e.target.value)}
                    />
                    <div className="mt-4 w-full flex">
                        <Dropdown className='w-full'>
                            <Dropdown.Trigger>
                                <span className="inline-flex rounded-xl w-full">
                                    <button
                                        type="button"
                                        className="w-full justify-between flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-200 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                    >
                                        Category

                                        <svg
                                            className="ml-2 -mr-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fillRule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clipRule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </Dropdown.Trigger>
                            <Dropdown.Content>
                                <Dropdown.Link onClick = {()=>console.log("clicked")} className = "text-center" as="button" href={route('profile.edit')}>Category 1</Dropdown.Link>
                                <Dropdown.Link className = "text-center" href={route('logout')} as="button">
                                    Category 2
                                </Dropdown.Link>
                            </Dropdown.Content>
                        </Dropdown>

                    </div>

                    {/* Text Area */}    
                    <div className="mt-4 items-center flex w-full rounded-xl ">
                        <textarea placeholder = "Describe your idea" className = "bg-gray-200 w-full resize-none border-none rounded-xl" rows = '4'>

                        </textarea>
                    </div>

                    {/* buttons */}
                    <div className="flex items-center justify-bwtween space-x-3 mt-4">
                        <button type = "button" className = "flex border border-gray-200 hover:border-gray-400 transition duration-150 ease-in rounded-xl items-center h-11 justify-center w-1/2 text-xs bg-gray-200">
                            <svg className = "text-gray-500 w-4 transform -rotate-45" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                            </svg>

                            <span className = "ml-1">Attach</span>
                        </button>

                        <PrimaryButton type = "submit" className = "flex border border-blue-200 hover:border-blue-400 transition duration-150 ease-in rounded-xl items-center h-11 justify-center w-1/2 text-xs bg-blue-200">
                            Submit
                        </PrimaryButton>
                    </div>
                    

                </form>
            </div>
        </div>
  )
}

export default AddIdea
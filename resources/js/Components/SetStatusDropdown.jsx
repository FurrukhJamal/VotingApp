import React, { useContext, useEffect, useState } from 'react'
import Dropdown from './Dropdown'
import InputLabel from './InputLabel'
import PrimaryButton from './PrimaryButton'
import Checkbox from './Checkbox'
import { router, useForm } from '@inertiajs/react'

function SetStatusDropdown({ idea }) {
    const [radioSelectedValue, setRadioSelectedValue] = useState("")
    const [defaultChecked, setdefaultChecked] = useState(idea.status.name)

    /*to solve the bug of dropdown not closing when a radio button is selected
    the Dropdown.Trigger should have the prop shouldCloseOnSelection = false*/
    const [displayStatusDropdownFlag, setDisplayStatusDropdownFlag] = useState(false)

    const { post, patch, setData, data, errors, processing, reset } = useForm({
        status: idea.status.name,
        ideaId: idea.id,
        statusUpdateComment: "",
        notifyAllVoters: false
    })


    function handleSubmit(e) {
        e.preventDefault()
        console.log("status changed", e.target.value)

        // router.post("/setStatus", { statusToBeUpdates: defaultChecked, ideaId: idea.id })
        patch("/setstatus", {
            onSuccess: () => {
                console.log("updated")
                //to close the set status display when status is changed
                setDisplayStatusDropdownFlag(true)
            }
        }
        )
    }


    function handleRadioSeletion(e) {
        e.stopPropagation()
        const radioValue = e.currentTarget.value
        const firsrLetter = radioValue.charAt(0)
        const remaining = radioValue.slice(1)
        const selectedValue = firsrLetter.toUpperCase() + remaining.toLowerCase()
        console.log("selected radio value: ", selectedValue)
        // setRadioSelectedValue(selectedValue)

        if (selectedValue == "Open") {
            console.log("changing select")
            // setdefaultChecked("Open")
            setData("status", "Open")
        }
        else if (selectedValue == "Considering") {
            console.log("changing select")
            // setdefaultChecked("Considering")
            setData("status", "Considering")
        }
        else if (selectedValue == "Inprogress") {
            // setdefaultChecked("In Progress")
            setData("status", "In Progress")
        }
        else if (selectedValue == "Implemented") {
            // setdefaultChecked("Implemented")
            setData("status", "Implemented")
        }
        else if (selectedValue == "Closed") {
            // setdefaultChecked("Closed")
            setData("status", "Closed")
        }
        // setData("status", defaultChecked)

    }


    function handleCheckBox(e) {
        setData("notifyAllVoters", e.target.checked)
    }

    useEffect(() => {
        //to fix back the status dropdown from not closing when a selection is made again
        if (displayStatusDropdownFlag) {
            setDisplayStatusDropdownFlag(false)
        }
    }, [displayStatusDropdownFlag])


    return (
        <>
            <Dropdown className="w-full bg-blue-200 rounded-xl">
                <Dropdown.Trigger shouldCloseOnSelection={displayStatusDropdownFlag} >
                    <span className="inline-flex rounded-xl w-full justify-center">
                        <button
                            dusk="adminSetStatusButton"
                            type="button"
                            className="w-full justify-center flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            Set Status

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
                <Dropdown.Content hasLinks={false} width="w-2full" align="left">
                    <form onSubmit={handleSubmit}>

                        <div className="mt-4 ml-4 w-full flex items-center">
                            <input
                                type="radio"
                                id="open"
                                name="statusOpen"
                                value="OPEN"
                                checked={data.status === "Open"}
                                onChange={handleRadioSeletion} />
                            <InputLabel className='font-semibold ml-4' htmlFor="open">Open</InputLabel>
                        </div>

                        <div className="mt-4 ml-4 w-full flex items-center">
                            <input
                                type="radio"
                                id="considering"
                                name="statusConsidering"
                                value="CONSIDERING"
                                checked={data.status === "Considering"}
                                onChange={handleRadioSeletion} />
                            <InputLabel className='font-semibold ml-4' htmlFor="considering">Considering</InputLabel>
                        </div>

                        <div className="mt-4 ml-4 w-full flex items-center">
                            <input
                                type="radio"
                                id="inProgress"
                                name="statusInProgress"
                                value="INPROGRESS"
                                checked={data.status === "In Progress"}
                                onChange={handleRadioSeletion} />
                            <InputLabel className='font-semibold ml-4' htmlFor="inProgress">In Progress</InputLabel>
                        </div>

                        <div className="mt-4 ml-4 w-full flex items-center">
                            <input
                                type="radio"
                                id="implemented"
                                name="statusImplemented"
                                value="IMPLEMENTED"
                                checked={data.status === "Implemented"}
                                onChange={handleRadioSeletion} />
                            <InputLabel className='font-semibold ml-4' htmlFor="implemented">Implemented</InputLabel>
                        </div>

                        <div className="mt-4 ml-4 w-full flex items-center">
                            <input
                                type="radio"
                                id="closed"
                                name="statusClosed"
                                value="CLOSED"
                                checked={data.status === "Closed"}
                                onChange={handleRadioSeletion} />
                            <InputLabel className='font-semibold ml-4' htmlFor="closed">Closed</InputLabel>
                        </div>




                        <div className="mt-4 w-full flex justify-center">
                            <textarea
                                placeholder="Status Update Comment(Optional)"
                                className="w-full mx-3 bg-gray-200 resize-none border-none rounded-xl"
                                rows='4'
                                value={data.statusUpdateComment}
                                onChange={(e) => setData("statusUpdateComment", e.target.value)}>

                            </textarea>
                        </div>

                        <div className="my-4 w-full flex justify-between mx-2">
                            <button type="button" className="h-8 flex border border-gray-200 hover:border-gray-400 transition duration-150 ease-in rounded-xl items-center justify-center w-5/12 text-xs bg-gray-200">
                                <svg className="w-4 h-4 text-gray-500  transform -rotate-45" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" >
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                </svg>

                                <span className="ml-1">Attach</span>
                            </button>
                            <PrimaryButton {...processing && ({ disabled: true })} type="submit" className="mr-4 flex border border-blue-200 hover:border-blue-400 transition duration-150 ease-in rounded-xl items-center justify-center w-5/12 text-xs bg-blue-200">
                                Submit
                            </PrimaryButton>
                        </div>
                        {errors.status && (
                            <div className="flex text-center justify-center text-red-500">
                                {errors.status}
                            </div>)}
                    </form>
                    <div className="my-4 mx-3 w-full flex flex-start items-center">
                        <Checkbox
                            name="notify"
                            id="notify"
                            value={data.notifyAllVoters}
                            onChange={handleCheckBox} />
                        <InputLabel className="ml-2" htmlFor="notify">Notify all voters</InputLabel>
                    </div>

                </Dropdown.Content>
            </Dropdown>
        </>
    )
}

export default SetStatusDropdown
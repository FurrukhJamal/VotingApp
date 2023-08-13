import { Link } from '@inertiajs/react'
import React, { useEffect, useState } from 'react'


function Pagination({ prev_page_url, next_page_url }) {
    console.log("prev_page_url:", prev_page_url)
    const [isFirstPage, setisFirstPage] = useState(true)
    const [isLastPage, setIsLastPage] = useState(false)

    useEffect(() => {
        if (prev_page_url) {
            setisFirstPage(false)
        }
        if (next_page_url === null) {
            setIsLastPage(true)
        }
    }, [])

    console.log("isFirstPage: ", isFirstPage)
    return (
        <div className='w-full flex justify-end'>
            <div className="w-2/5 flex justify-between ">

                <Link
                    className={`w-40 py-3 px-4 ${isFirstPage ? "bg-gray-50 border-2 border-green-400" : "bg-gray-200 hover:bg-gray-400 border-green-400"}  transition transition-duration-150 ease-in  flex justify-center items-center rounded-xl`}
                    as="button" href={prev_page_url}
                    disabled={isFirstPage}
                >
                    <span className="inline-flex items-center">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" className="color-gray w-6 h-6 ">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                        <p className="capitalize text-center text-lg">previous</p>
                    </span>
                </Link>

                <Link
                    className={`w-40 ml-2 py-3 px-4 ${isLastPage ? "bg-gray-50 border-2 border-green-400" : "bg-gray-200 hover:bg-gray-400 border-green-400"}  transition transition-duration-150 ease-in  flex justify-center items-center rounded-xl`}
                    as="button" href={next_page_url}
                    disabled={isLastPage}
                >
                    <span className="inline-flex items-center">
                        <p className="capitalize text-center text-lg">Next</p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </span>
                </Link>



            </div>
        </div >
    )
}

export default Pagination
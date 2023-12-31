import { Link, usePage } from '@inertiajs/react'
import React, { useEffect, useState } from 'react'


function Pagination({ prev_page_url, next_page_url }) {
    console.log("prev_page_url:", prev_page_url)
    const [isFirstPage, setisFirstPage] = useState(true)
    const [isLastPage, setIsLastPage] = useState(false)
    // to fix pagination for when categories are selected from all ideas status
    const [ifPathHasParam, setPathHasParam] = useState(false)
    const [customNextPageUrl, setcustomNextPageUrl] = useState("")
    const [customPreviousPageUrl, setcustomPreviousPageUrl] = useState("")

    //for when Top Votes and other categories are selected from Other Filters
    const { fullUrl, queryParams } = usePage().props

    useEffect(() => console.log("PAGINATION.jsx IS RENDERED"))


    useEffect(() => {
        if (prev_page_url) {
            setisFirstPage(false)
        }
        if (next_page_url === null) {
            setIsLastPage(true)
        }

        // to fix pagination for when categories are selected from all ideas status
        let searchQueryParam = window.location.search


        if (queryParams?.category && queryParams.user) {
            setPathHasParam(true)
            if (next_page_url) {
                let pageNumber = next_page_url.slice(next_page_url.lastIndexOf("=")).slice(1)
                if (queryParams.category) {
                    setcustomNextPageUrl(fullUrl + "?user=true" + `&category=${queryParams.category}&page=${pageNumber}`)
                }
                else {
                    setcustomNextPageUrl(fullUrl + `?user=true&page=${pageNumber}`)
                }

            }

            if (prev_page_url) {
                // getting just "=2" or "=1" part 
                let pageNumber = prev_page_url.slice(prev_page_url.lastIndexOf("=")).slice(1)
                //making the link as "localhost/?user=true&page=1"
                if (queryParams.category) {
                    setcustomPreviousPageUrl(fullUrl + "?user=true" + `&category=${queryParams.category}&page=${pageNumber}`)
                }
                else {
                    setcustomPreviousPageUrl(fullUrl + `?user=true&page=${pageNumber}`)

                }
            }
        }
        else if (queryParams.user) {
            setPathHasParam(true)
            if (next_page_url) {
                let pageNumber = next_page_url.slice(next_page_url.lastIndexOf("=")).slice(1)
                setcustomNextPageUrl(fullUrl + "?user=true" + `&page=${pageNumber}`)
            }

            if (prev_page_url) {
                let pageNumber = prev_page_url.slice(prev_page_url.lastIndexOf("=")).slice(1)
                //making the link as "localhost/?user=true&page=1"
                setcustomPreviousPageUrl(fullUrl + "?user=true" + `&page=${pageNumber}`)
            }
        }
        else if (queryParams?.otherfilters && queryParams?.category) {
            console.log("OTHER FILTERS present", queryParams.otherfilters, "category : ", queryParams.category)
            setPathHasParam(true)
            if (next_page_url) {
                let pageNumber = next_page_url.slice(next_page_url.lastIndexOf("=")).slice(1)
                setcustomNextPageUrl(fullUrl + `?otherfilters=${queryParams.otherfilters}${queryParams.category ? `&category=${queryParams.category}` : ``}&page=${pageNumber}`)
            }
            if (prev_page_url) {
                // getting just "=2" or "=1" part 
                let pageNumber = prev_page_url.slice(prev_page_url.lastIndexOf("=")).slice(1)
                //making the link as "localhost/?category=1&page=1"
                setcustomPreviousPageUrl(fullUrl + `?otherfilters=${queryParams.otherfilters}&category=${queryParams.category}&page=${pageNumber}`)
            }
        }

        else if (queryParams?.otherfilters || queryParams?.category) {
            console.log("OTHER FILTERS present", queryParams.otherfilters, "category : ", queryParams.category)
            setPathHasParam(true)
            if (next_page_url) {
                let pageNumber = next_page_url.slice(next_page_url.lastIndexOf("=")).slice(1)
                if (queryParams.category) {
                    setcustomNextPageUrl(fullUrl + `${queryParams.category ? `?category=${queryParams.category}` : ``}&page=${pageNumber}`)
                }
                else {
                    setcustomNextPageUrl(fullUrl + `${queryParams.otherfilters == "topvoted" ? `?otherfilters=${queryParams.otherfilters}` : ``}&page=${pageNumber}`)
                }

            }
            if (prev_page_url) {
                // getting just "=2" or "=1" part 
                let pageNumber = prev_page_url.slice(prev_page_url.lastIndexOf("=")).slice(1)
                //making the link as "localhost/?category=1&page=1"
                // setcustomPreviousPageUrl(fullUrl + `?otherfilters=${queryParams.otherfilters}&page=${pageNumber}`)
                if (queryParams.category) {
                    setcustomPreviousPageUrl(fullUrl + `${queryParams.category ? `?category=${queryParams.category}` : ``}&page=${pageNumber}`)
                }
                else {
                    setcustomPreviousPageUrl(fullUrl + `${queryParams.otherfilters == "topvoted" ? `?otherfilters=${queryParams.otherfilters}` : ``}&page=${pageNumber}`)
                }
            }
        }
        else if (queryParams?.search_query) {
            console.log("fullURL in search:", fullUrl)
            setPathHasParam(true)
            if (next_page_url) {
                let pageNumber = next_page_url.slice(next_page_url.lastIndexOf("=")).slice(1)
                setcustomNextPageUrl(fullUrl + `?search_query=${queryParams.search_query}&page=${pageNumber}`)
            }
            if (prev_page_url) {
                // getting just "=2" or "=1" part 
                let pageNumber = prev_page_url.slice(prev_page_url.lastIndexOf("=")).slice(1)
                //making the link as "localhost/?category=1&page=1"
                setcustomPreviousPageUrl(fullUrl + `?search_query=${queryParams.search_query}&page=${pageNumber}`)
            }
        }

    }, [])

    console.log("isFirstPage: ", isFirstPage)
    return (
        <div className='w-full flex justify-end'>
            <div className="w-2/5 flex justify-between ">

                <Link
                    className={`w-40 py-3 px-4 ${isFirstPage ? "bg-gray-50 border-2 border-green-400" : "bg-gray-200 hover:bg-gray-400 border-green-400"}  transition transition-duration-150 ease-in  flex justify-center items-center rounded-xl`}
                    as="button" href={ifPathHasParam ? customPreviousPageUrl : prev_page_url}
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
                    as="button" href={ifPathHasParam ? customNextPageUrl : next_page_url}
                    disabled={isLastPage}
                >
                    <span className="inline-flex items-center">
                        <p dusk="paginationNextButton" className="capitalize text-center text-lg">Next</p>
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
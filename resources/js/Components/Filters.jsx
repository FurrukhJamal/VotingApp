import React, { useContext, useEffect, useState } from 'react'
import "../../css/filters.css"
import Dropdown from './Dropdown'
import TextInput from "./TextInput"
import searchIcon from "../../images/search-interface-symbol.png"
import { router, useForm, usePage } from '@inertiajs/react'
import { AppContext } from '@/Pages/HomePage'

function Filters({ categories }) {
  const { selectedCategory, topVotedSelected, userIdeaSelected } = useContext(AppContext)
  const { fullUrl, queryParams, auth } = usePage().props

  const { data, setData, processing, } = useForm({
    "search_query": ""
  })

  function handleCategorySelect(e, category) {
    e.preventDefault()
    console.log("hitting", route().current())
    if (route().current() == "search") {

      router.get(route("idea.index", { "category": category.id }))
    }
    else {
      router.get(route(route().current(), { "category": category.id }))

    }
  }

  function handleTopVoted(e) {
    e.preventDefault()
    console.log("fullUrl", fullUrl)
    console.log("queryParams: ", queryParams)
    if (route().current() == "search") {

      router.get(route("idea.index", { "otherfilters": "topvoted" }))
    }

    else if (queryParams?.category) {
      let path = fullUrl + `?category=${queryParams.category}` + "&otherfilters=topvoted"
      router.get(path)
    }
    else if (queryParams?.otherfilters == "topvoted") {
      let path = fullUrl + "?otherfilters=topvoted"
      router.get(path)
    }
    else {
      let path = fullUrl + "?otherfilters=topvoted"
      router.get(path)
    }

  }

  function displayUserIdeas(e) {
    e.preventDefault()
    let path = ""

    if (!auth.user) {
      console.log("unauth hitting")
      router.visit(route("login"))

    }
    else {
      if (route().current() == "search") {
        router.get(route("idea.index", { "user": "true" }))
      }
      else {
        if (queryParams?.category) {
          path = fullUrl + `?category=${queryParams.category}` + "&user=true"
        }
        else {
          path = fullUrl + "?user=true"
        }

        router.get(path)
      }


    }

  }


  function handleResetOtherFilters(e) {
    e.preventDefault()
    // console.log("fullURL in Filter", fullUrl)
    let path = fullUrl
    if (queryParams?.category) {
      path += `?category=${queryParams.category}`
    }

    router.get(path)
  }


  function handleSearchSubmit(e) {
    e.preventDefault()
    router.visit(route("search"), {
      method: "get",
      data: { "search_query": data.search_query }
    })
  }


  return (
    <div className='Filtercontainer'>
      <div className="filterButtons">
        <Dropdown>
          <Dropdown.Trigger >
            <span className="inline-flex rounded-xl bg-red-500">
              <button
                dusk="categoriesButton"
                type="button"
                className="inline-flex items-center w-44 justify-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
              >
                {selectedCategory ? selectedCategory : "Category"}

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
            {categories.map((category) => (
              <Dropdown.Link
                {...(category.id == 1 ? { dusk: "Category1Button" } : null)}
                key={category.id}
                className="text-center"
                href={route('idea.index')}
                method="post"
                as="button"
                onClick={(e) => handleCategorySelect(e, category)}>
                {category.name}
              </Dropdown.Link>
            ))}

          </Dropdown.Content>
        </Dropdown>

        {/* second filter button */}
        <Dropdown>
          <Dropdown.Trigger>
            <span className="inline-flex rounded-xl">
              <button
                type="button"
                className="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
              >
                {topVotedSelected && "Top Voted"}
                {(!userIdeaSelected && !topVotedSelected) && "Other Filters"}
                {userIdeaSelected && "My Ideas"}


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
            <Dropdown.Link
              className="text-center"
              href={route('idea.index')}
              onClick={(e) => handleTopVoted(e)}>
              Top Voted
            </Dropdown.Link>
            <Dropdown.Link
              className="text-center"
              href={route('idea.index')}
              as="button"
              onClick={(e) => displayUserIdeas(e)}>
              My Ideas
            </Dropdown.Link>
            <Dropdown.Link
              className="text-center"
              href={route('idea.index')}
              onClick={(e) => handleResetOtherFilters(e)}>
              All
            </Dropdown.Link>
          </Dropdown.Content>
        </Dropdown>
      </div>

      {/* Search Box */}
      <div className="searchBox relative h-4">
        <form onSubmit={handleSearchSubmit}>
          <TextInput
            type="search"

            placeholder="Search Here"
            className="placeholder-gray-700 w-full pl-10 border-none h-8"
            onChange={(e) => setData("search_query", e.target.value)}
            value={data.search_query} />
        </form>
        <div className="absolute top-2 left-2 w-5 h-6">
          <img src={searchIcon} alt="search icon" />
        </div>
      </div>

    </div>
  )
}


export default Filters
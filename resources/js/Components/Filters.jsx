import React, { useContext, useEffect, useState } from 'react'
import "../../css/filters.css"
import Dropdown from './Dropdown'
import TextInput from "./TextInput"
import searchIcon from "../../images/search-interface-symbol.png"
import { router } from '@inertiajs/react'
import { AppContext } from '@/Pages/HomePage'

function Filters({ categories }) {
  const { selectedCategory } = useContext(AppContext)

  function handleCategorySelect(e, category) {
    e.preventDefault()
    router.get(route(route().current(), { "category": category.id }))
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
                Other Filters

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
            <Dropdown.Link className="text-center" href={route('profile.edit')}>Category 1</Dropdown.Link>
            <Dropdown.Link href={route('logout')} method="post" as="button">
              Category 2
            </Dropdown.Link>
          </Dropdown.Content>
        </Dropdown>
      </div>

      {/* Search Box */}
      <div className="searchBox relative h-4">
        <TextInput name="searchBox" placeholder="Search Here" className="placeholder-gray-700 w-full pl-10 border-none h-8" />
        <div className="absolute top-2 left-2 w-5 h-6">
          <img src={searchIcon} alt="search icon" />
        </div>
      </div>

    </div>
  )
}


export default Filters
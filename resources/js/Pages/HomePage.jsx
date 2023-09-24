import React, { createContext, useContext, useEffect, useState } from 'react'
import Home from '@/Layouts/Home'
import "../../css/index.css"
import { Head, router, usePage } from '@inertiajs/react'

export const AppContext = createContext()


function HomePage({ auth, ideas, categories, avatar, statusCounts, isAdmin }) {
  const [selectedCategory, setSelectedCategory] = useState("")
  const { queryParams } = usePage().props
  const [topVotedSelected, setTopVotedSelected] = useState(false)
  const [userIdeaSelected, setUserIdeaSelected] = useState(false)

  console.log("ideas are: ", ideas)
  // console.log("auth in Homepage.jsx", auth)
  // console.log("categories in HomePage: ", categories)
  // console.log("avatar in HomePage: ", avatar)
  console.log("isAdmin in HomePage.jsx", isAdmin)

  useEffect(() => {
    let searchParam = window.location.search
    console.log("window.location.search : ", window.location.search)
    //if a get parameter named category is there in the url
    // if (searchParam.match(/\?category=[0-9]/)) {

    //   let categoryId = searchParam.slice(searchParam.lastIndexOf("=")).slice(1)
    //   let categorySelected = categories.filter((category) => category.id == categoryId)
    //   setSelectedCategory(categorySelected[0].name)
    // }
    if (queryParams?.category) {
      let selectedCat = categories.filter((category) => category.id == queryParams.category)
      console.log("in HOMEPAGE.JS selected category is:", selectedCat)
      setSelectedCategory(selectedCat[0].name)
    }
    else if (window.location.pathname.slice(window.location.pathname.lastIndexOf("/")).slice(1).match(/[0-9]/)) {
      // case where the URL ends with /statusValue/a number => getting the number and sending the category name down the component
      let numInPath = window.location.pathname.slice(window.location.pathname.lastIndexOf("/")).slice(1)
      let categorySelected = categories.filter((category) => category.id == numInPath)
      setSelectedCategory(categorySelected[0].name)
    }
  }, [])

  //for checking if top Voted filter is selected and display accordingly
  useEffect(() => {
    if (queryParams?.otherfilters == "topvoted") {
      setTopVotedSelected(true)
    }
    else if (queryParams?.user == "true") {
      setUserIdeaSelected(true)
    }
  }, [])

  return (
    <AppContext.Provider value={{ userIdeaSelected, selectedCategory, setSelectedCategory, topVotedSelected }}>
      <Head title="Voting App" />
      <Home {...auth} ideas={ideas} categories={categories} avatar={avatar} statusCounts={statusCounts} isAdmin={isAdmin} />
    </AppContext.Provider>
  )
}

export default HomePage
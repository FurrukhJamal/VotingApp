import React, { createContext, useEffect } from 'react'
import Home from '@/Layouts/Home'
import "../../css/index.css"
import { Head, router } from '@inertiajs/react'

export const AppContext = createContext()


function HomePage({ auth, ideas, categories, avatar }) {
  console.log("ideas are: ", ideas)
  // console.log("auth in Homepage.jsx", auth)
  // console.log("categories in HomePage: ", categories)
  // console.log("avatar in HomePage: ", avatar)
  useEffect(() => {
    console.log("HomePage loaded")
  }, [ideas])

  return (
    <>
      <Head title="Voting App" />
      <Home {...auth} ideas={ideas} categories={categories} avatar={avatar} />
    </>
  )
}

export default HomePage
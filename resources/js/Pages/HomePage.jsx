import React, { createContext } from 'react'
import Home from '@/Layouts/Home'
import "../../css/index.css"
import { Head } from '@inertiajs/react'

export const AppContext = createContext()


function HomePage({ auth, ideas, categories }) {
  console.log("ideas are: ", ideas)
  console.log("auth in Homepage.jsx", auth)
  console.log("categories in HomePage: ", categories)
  return (
    <>
      <Head title="Voting App" />
      <Home {...auth} ideas={ideas} categories={categories} />
    </>
  )
}

export default HomePage
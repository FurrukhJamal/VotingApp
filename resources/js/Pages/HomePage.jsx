import React, { createContext } from 'react'
import Home from '@/Layouts/Home'
import "../../css/index.css"
import { Head } from '@inertiajs/react'

export const AppContext = createContext()


function HomePage({ auth, ideas }) {
  console.log("ideas are: ", ideas)
  console.log("auth in Homepage.jsx", auth)
  return (
    <AppContext.Provider value={{ auth }}>
      <Head title="Voting App" />
      <Home {...auth} ideas={ideas} />
    </AppContext.Provider>
  )
}

export default HomePage
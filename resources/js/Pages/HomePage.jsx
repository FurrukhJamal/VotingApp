import React from 'react'
import Home from '@/Layouts/Home'
import "../../css/index.css"
import { Head } from '@inertiajs/react'


function HomePage({ auth, ideas }) {
  console.log("ideas are: ", ideas)
  console.log("auth in Homepage.jsx", auth)
  return (
    <>
      <Head title="Voting App" />
      <Home {...auth} ideas={ideas} />
    </>
  )
}

export default HomePage
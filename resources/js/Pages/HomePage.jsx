import React from 'react'
import Home from '@/Layouts/Home'
import "../../css/index.css"


function HomePage({ auth, ideas }) {
  console.log("ideas are: ", ideas)
  return (
    <Home {...auth} ideas={ideas} />
  )
}

export default HomePage
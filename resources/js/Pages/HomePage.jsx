import React from 'react'
import Home from '@/Layouts/Home'
import "../../css/index.css"


function HomePage({ auth }) {
  console.log("auth is :", auth)
  return (
    <Home {...auth} />
  )
}

export default HomePage
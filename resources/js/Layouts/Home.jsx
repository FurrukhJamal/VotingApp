import React, { useEffect, useState } from 'react'
import Guest from './GuestLayout'
import MainNav from '@/Components/MainNav'
import AddIdea from '@/Components/AddIdea'
import NavigationBar from '@/Components/NavigationBar'
import Filters from '@/Components/Filters'
import Ideas from '@/Components/Ideas'
import MainLayOut from './MainLayOut'
import Pagination from '@/Components/Pagination'
import { router, usePage } from '@inertiajs/react'
import NotificationMessage from '@/Components/NotificationMessage'

function Home({ isAdmin, user, ideas, categories, avatar, statusCounts }) {
  console.log("ideas in Home:", ideas)
  // console.log("user in Home.jsx", user)
  // console.log("categories in Home:", categories)
  // console.log("avatar in home:", avatar)
  const [isPathGetSpam, setIsPathGetSpam] = useState(false)




  useEffect(() => {
    //to hide category and other filter when on spam section
    if (window.location.pathname == "/getspam") {
      setIsPathGetSpam(true)
    }
    console.log("window.location.pathname", window.location.pathname)
  }, [])

  useEffect(() => console.log("HOME.jsx IS RENDERED"))


  return (
    <>
      <MainLayOut user={user} categories={categories} avatar={avatar}>
        <NavigationBar statusCounts={statusCounts} />
        {!isPathGetSpam && (<Filters isAdmin={isAdmin} categories={categories} />)}
        <Ideas isAdmin={isAdmin} ideas={ideas} user={user} />
        {ideas.data.length > 0 ? (<Pagination {...ideas} />) : null}


      </MainLayOut>
    </>

  )
}

export default Home
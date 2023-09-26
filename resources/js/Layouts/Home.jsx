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
  const [showNotification, setShowNotification] = useState(false)
  const { flash } = usePage().props

  useEffect(() => {
    if (flash?.notificationMessage) {
      console.log("FLASH MESSAGE IS THERE IN HOME")
      setShowNotification(true)
      //then remove the notification message after appx 2 secs
      setTimeout(() => {
        console.log("SETTIMEOUT AFTER 4 SECS HITTING")
        setShowNotification(false)
      }, 4000)
    }
  }, [flash])

  useEffect(() => {
    //to hide category and other filter when on spam section
    if (window.location.pathname == "/getspam") {
      setIsPathGetSpam(true)
    }
    console.log("window.location.pathname", window.location.pathname)
  }, [])


  return (
    <>
      <MainLayOut user={user} categories={categories} avatar={avatar}>
        <NavigationBar statusCounts={statusCounts} />
        {!isPathGetSpam && (<Filters isAdmin={isAdmin} categories={categories} />)}
        <Ideas isAdmin={isAdmin} ideas={ideas} user={user} />
        {ideas.data.length > 0 ? (<Pagination {...ideas} />) : null}

        {/* Notification message div */}
        {showNotification && (
          <NotificationMessage
            message={flash.notificationMessage}
            hideNotification={() => {
              console.log("I SHOULD NOT SEE THIS IN HOME PAGE")
              setShowNotification(prev => !prev)
            }} />
        )}
      </MainLayOut>
    </>

  )
}

export default Home
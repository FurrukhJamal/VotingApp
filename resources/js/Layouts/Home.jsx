import React from 'react'
import Guest from './GuestLayout'
import MainNav from '@/Components/MainNav'
import AddIdea from '@/Components/AddIdea'
import NavigationBar from '@/Components/NavigationBar'
import Filters from '@/Components/Filters'
import Ideas from '@/Components/Ideas'
import MainLayOut from './MainLayOut'

function Home({ user }) {
  console.log("user in Home:", user)
  return (
    <>
      <MainLayOut>
        <NavigationBar />
        <Filters></Filters>
        <Ideas></Ideas>
      </MainLayOut>
    </>

  )
}

export default Home
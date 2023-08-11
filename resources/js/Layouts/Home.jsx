import React from 'react'
import Guest from './GuestLayout'
import MainNav from '@/Components/MainNav'
import AddIdea from '@/Components/AddIdea'
import NavigationBar from '@/Components/NavigationBar'
import Filters from '@/Components/Filters'
import Ideas from '@/Components/Ideas'
import MainLayOut from './MainLayOut'

function Home({ user, ideas }) {
  console.log("ideas in Home:", ideas)
  return (
    <>
      <MainLayOut>
        <NavigationBar />
        <Filters></Filters>
        <Ideas ideas={ideas} />
      </MainLayOut>
    </>

  )
}

export default Home
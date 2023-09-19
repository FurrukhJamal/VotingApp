import React, { useEffect } from 'react'
import Guest from './GuestLayout'
import MainNav from '@/Components/MainNav'
import AddIdea from '@/Components/AddIdea'
import NavigationBar from '@/Components/NavigationBar'
import Filters from '@/Components/Filters'
import Ideas from '@/Components/Ideas'
import MainLayOut from './MainLayOut'
import Pagination from '@/Components/Pagination'
import { router } from '@inertiajs/react'

function Home({ user, ideas, categories, avatar, statusCounts }) {
  console.log("ideas in Home:", ideas)
  // console.log("user in Home.jsx", user)
  // console.log("categories in Home:", categories)
  // console.log("avatar in home:", avatar)


  return (
    <>
      <MainLayOut user={user} categories={categories} avatar={avatar}>
        <NavigationBar statusCounts={statusCounts} />
        <Filters categories={categories} />
        <Ideas ideas={ideas} user={user} />
        {ideas.data.length > 0 ? (<Pagination {...ideas} />) : null}
      </MainLayOut>
    </>

  )
}

export default Home
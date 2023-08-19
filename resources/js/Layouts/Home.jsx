import React from 'react'
import Guest from './GuestLayout'
import MainNav from '@/Components/MainNav'
import AddIdea from '@/Components/AddIdea'
import NavigationBar from '@/Components/NavigationBar'
import Filters from '@/Components/Filters'
import Ideas from '@/Components/Ideas'
import MainLayOut from './MainLayOut'
import Pagination from '@/Components/Pagination'

function Home({ user, ideas, categories }) {
  console.log("ideas in Home:", ideas)
  console.log("user in Home.jsx", user)
  console.log("categories in Home:", categories)
  return (
    <>
      <MainLayOut user={user} categories={categories}>
        <NavigationBar />
        <Filters></Filters>
        <Ideas ideas={ideas} />
        <Pagination {...ideas} />
      </MainLayOut>
    </>

  )
}

export default Home
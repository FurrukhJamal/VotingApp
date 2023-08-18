import React from 'react'
import Guest from './GuestLayout'
import MainNav from '@/Components/MainNav'
import AddIdea from '@/Components/AddIdea'
import NavigationBar from '@/Components/NavigationBar'
import Filters from '@/Components/Filters'
import Ideas from '@/Components/Ideas'
import LoginButtonToAddIdea from '@/Components/LoginButtonToAddIdea'

export default function MainLayOut({ user, children }) {
    console.log("user in MainLayOut", user)
    return (
        <>
            <MainNav {...user} />
            {
                user ? (
                    <Guest>
                        <div className='row'>
                            <div className='mainContainer'>
                                <div className="leftCol">
                                    <AddIdea user={user} />
                                </div>
                                <div className='rightCol'>
                                    {children}
                                </div>
                            </div>
                        </div>
                    </Guest>
                ) :
                    (
                        <div className="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                            <div className="w-full sm:max-m-md mt-3 px-6  shadow-md sm:rounded-lg">
                                <div className='row'>
                                    <div className='mainContainer'>
                                        <div className="leftCol">
                                            <LoginButtonToAddIdea></LoginButtonToAddIdea>
                                        </div>
                                        <div className='rightCol'>
                                            {children}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )
            }



        </>

    )
}
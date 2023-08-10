import React from 'react'
import Guest from './GuestLayout'
import MainNav from '@/Components/MainNav'
import AddIdea from '@/Components/AddIdea'
import NavigationBar from '@/Components/NavigationBar'
import Filters from '@/Components/Filters'
import Ideas from '@/Components/Ideas'

export default function MainLayOut({ user, children }) {
    return (
        <>
            <MainNav {...user} />
            <Guest>
                <div className='row'>
                    <div className='mainContainer'>
                        <div className="leftCol">
                            <AddIdea />
                        </div>
                        <div className='rightCol'>
                            {children}
                        </div>
                    </div>
                </div>
            </Guest>
        </>

    )
}
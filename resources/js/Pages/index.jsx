import React from 'react'
import { Link, Head } from '@inertiajs/react'
import "../../css/index.css"
import ApplicationLogo from '@/Components/ApplicationLogo'
import NavigationBar from '@/Components/NavigationBar'
import Filters from '@/Components/Filters'


function index({auth,}) {
  return (
    <>
        <Head title = "Voting App"/>
        <div>
            <header>
                <Link href = {route("home")}>
                    <ApplicationLogo/>
                </Link> 
                <div className="navLogRegContainer">
                    <div>
                        {auth.user ? (
                            <Link
                                href={route('logout')}
                                method = "post"
                                className="link"
                                
                            >
                                Log Out
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route('login')}
                                    className="link"
                                >
                                    Log in
                                </Link>

                                <Link
                                    href={route('register')}
                                    className="link"
                                >
                                    Register
                                </Link>
                            </>
                            )
                        }
                    </div>
                    <Link href = "#">
                        <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp" alt="avatar"/>
                    </Link>
                    
                </div>
            </header>
            
        </div>
        <div className='row'>
            <div className='mainContainer'>
                <div className = "leftCol">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe vero nisi error sapiente nam enim quo culpa dignissimos tenetur itaque ea id nemo, laudantium pariatur tempora voluptatum, eos corrupti facere.
                </div>
                <div className='rightCol'>
                    <NavigationBar/>
                    <Filters></Filters>
                    
                    {/* ideas container */}
                    <div className = "space-y-4 my-4">
                        <div className = "bg-white rounded-xl flex">
                            <div className = "border-r border-gray-100 px-5 py-8">
                                <div className='text-center'>
                                    <div className='font-semibold text-2xl'>
                                        12
                                    </div>
                                    <div className = "text-gray-500">
                                        Votes
                                    </div>
                                    
                                </div>
                            </div>

                        </div>
                    </div>
                    {/* end of ideas container */}
                </div>
            </div>
        </div>
        
    </>
  )
}

export default index

{/* ideas container */}
{/* <div className = "space-y-4 my-4">
<div className = "bg-white rounded-xl flex">
    <div className = "border-r border-gray-100 px-5 py-8">
        <div className='text-center'>
            <div className='font-semibold text-2xl'>
                12
            </div>
            <div className = "text-gray-500">
                Votes
            </div>
            
        </div>
    </div>

</div>
</div> */}
{/* end of ideas container */}
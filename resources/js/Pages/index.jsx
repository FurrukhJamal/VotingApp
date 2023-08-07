import React from 'react'
import { Link, Head } from '@inertiajs/react'
import "../../css/index.css"
import ApplicationLogo from '@/Components/ApplicationLogo'
import NavigationBar from '@/Components/NavigationBar'
import Filters from '@/Components/Filters'
import PrimaryButton from '@/Components/PrimaryButton'
import NavLink from '@/Components/NavLink'
import Dropdown from '@/Components/Dropdown'

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
                                as = "button"
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
                        <div className = "bg-white cursor-pointer hover:shadow-card transition duration-150 ease-in rounded-xl flex">
                            <div className = "border-r border-gray-100 px-5 py-8">
                                <div className='text-center'>
                                    <div className='font-semibold text-2xl'>
                                        12
                                    </div>
                                    <div className = "text-gray-500">
                                        Votes
                                    </div>
                                    <div className = "mt-8">
                                        <PrimaryButton className = "w-20 bg-gray-200 font-bold text-xs uppercase">Vote</PrimaryButton>
                                    </div>
                                </div>
                            </div>

                            <div className = "flex px-2 py-6">
                                <Link className = "flex-none" href = {route("profile.edit")}>
                                
                                    <img  
                                        src= "https://source.unsplash.com/200x200/?face&crop=face&v=1" 
                                        alt="avatar" 
                                        className='w-14 h-14 rounded-xl' />
                                </Link>
                                <div className = "mx-4">
                                    <Link href = "#" className = "hover:underline">
                                        <h1 className='text-xl font-semibold'>A random title </h1>
                                    </Link>
                                    <div className='text-gray-600 mt-3 line-clamp-3'>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore nemo suscipit fugit quibusdam perferendis veritatis qui ad! Aspernatur est iusto praesentium et? Doloribus vitae libero adipisci quis magni, veritatis ea.</p>
                                    </div>

                                    <div className="flex mt-6 items-center justify-between">
                                        <div className="flex items-center text-gray-400 text-xs font-semibold space-x-2">
                                            <div>10 hours ago</div>
                                            <div>&bull;</div>
                                            <div>Category 1</div>
                                            <div>&bull;</div>
                                            <div className = "text-gray-900">3 comments</div>
                                            <div>&bull;</div>
                                        </div>

                                        <div className="flex items-center space-x-2">
                                            <div className="flex justify-center bg-gray-200 text-xxs items-center font-bold uppercase rounded-full w-28 h-7 text-center py-2 px-4">
                                                Open
                                            </div>
                                            <Dropdown>
                                                <Dropdown.Trigger>
                                                    <PrimaryButton className='rounded-full h-7 bg-gray-400 transition duration-150 ease-in'>...</PrimaryButton>
                                                </Dropdown.Trigger>
                                                <Dropdown.Content className = "shahdow-dialogue" align = "left" width = "w-44">
                                                    <Link  className = "text-center w-full justify-center" href = "" as = "button">Mark as spam</Link>
                                                    <Link  className = "text-center w-full justify-center" href = "" as = "button">Delete Post</Link>

                                                </Dropdown.Content>
                                            </Dropdown>
                                        </div>      
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


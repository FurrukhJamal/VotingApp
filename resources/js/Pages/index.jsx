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
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptate dolores quidem praesentium odit aspernatur aliquid natus cumque impedit officiis rerum maiores, fugit incidunt sed, ullam labore, iusto consequuntur ex culpa? Consectetur impedit provident tempora nihil pariatur officiis, illum itaque voluptas est aspernatur quidem maiores distinctio iure hic architecto modi? Incidunt veniam tempore sapiente explicabo ab quod dignissimos, consequatur consequuntur quisquam, alias totam adipisci ipsa quasi aliquam illo, distinctio consectetur voluptates ea molestiae odio fugit asperiores nemo. Exercitationem ipsam velit possimus dolor totam! Vel perspiciatis quasi aliquam obcaecati. Laudantium et repudiandae similique eum consequatur consequuntur ad tempora, assumenda ab consectetur rem aut ipsum odit, corrupti accusamus facilis amet deserunt dolores. Praesentium vitae, quaerat incidunt consequatur obcaecati, earum non laudantium quidem dicta labore totam, id cupiditate perferendis. In ipsam iusto iste eveniet laudantium dolorem enim velit aut est placeat itaque tempora doloribus, quas qui perspiciatis tempore sequi consectetur odio fugit. Quasi quod ea fugiat mollitia sequi, beatae, deserunt id vero quidem earum nisi harum? Officiis nulla deserunt delectus omnis. Perferendis similique omnis ex? Perferendis dolore cupiditate architecto ea aperiam eveniet voluptatem facilis, odit est sint esse eius omnis facere accusamus nesciunt ipsum veritatis eos obcaecati praesentium tenetur optio minus? Aliquid, fuga porro?
                </div>
            </div>
        </div>
        
    </>
  )
}

export default index
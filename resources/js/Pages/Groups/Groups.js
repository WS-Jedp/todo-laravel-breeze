import { InertiaLink, usePage } from '@inertiajs/inertia-react'
import React from 'react'
import Authenticated from '../../Layouts/Authenticated'
export default function Groups({groups}) {

    const { auth } = usePage().props

    return (
        <Authenticated
            auth={auth}
            errros={null}
            header={(
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">Groups</h2>
            )}
        >

            <article className="my-5 mx-auto bg-white shadow container flex flex-col p-10 p-10">

                <div className="container mx-auto my-5 flex flex-row align-center justify-between">
                    <h3 className="font-semibold text-xl text-gray-800">
                        Groups
                    </h3>
                    <InertiaLink 
                        className="w-24 flex align-center justify-center text-center space-5 rounded-md bg-green-400 text-white hover:shadow-md hover:bg-green-700 " 
                        href={route('groups.create')}>
                        Create One
                    </InertiaLink>
                </div>

                <ul className="flex flex-col">
                    {
                        groups.length === 0 ? (
                            <div>
                                There is no groups
                            </div>
                        ) : groups.map(el => (
                            <li className="flex flex-row align-center">
                                {
                                    groups.name
                                }
                            </li>
                            )
                        )
                    }
                </ul>
            </article>

            
            
        </Authenticated>
    )

}  
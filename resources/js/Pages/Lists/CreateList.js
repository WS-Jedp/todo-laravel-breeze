import React from 'react'
import { useForm } from 'react-hook-form'
import { usePage } from '@inertiajs/inertia-react'
import { Inertia } from '@inertiajs/inertia'
import Authenticated from '../../Layouts/Authenticated'
export default function CreateList() {

    const { auth, errors } = usePage().props

    const { register, handleSubmit, formState: { errors: errorsForm } } = useForm()

    const onSubmit = (data) => {
        Inertia.post('/lists', data)
    }



    return (
        <Authenticated
            auth={auth}
            errros={null}
            header={(
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">Create List</h2>
            )}
        >

            <form 
                className="my-5 mx-auto bg-white shadow container flex flex-col p-10" 
                onSubmit={handleSubmit(onSubmit)}
            >
                <h3 className="font-bold text-2xl text-purple-800 my-5">
                    Create List
                </h3>

                {
                    Object.values(errors).length >= 1 && (
                        <div className="bg-red-400 container my-2 flex flex-col rounded-md shadow-md p-4">
                            <h4 className="text-red-900 text-md">
                                Oops! Something went wrong:
                            </h4>
                            <ul>
                                {Object.values(errors).map(el => (
                                    <li className="text-red-900">
                                        {el}
                                    </li>
                                ))}
                            </ul>
                        </div>
                    )
                }

                <label className="flex flex-col font-semibold text-md text-gray-600 mx-auto lg:mx-0 my-2 lg:my-5" htmlFor="name">
                    The name of the List
                    <input  { ...register('list_title', { min:3, max:30, required: true  }) } type="text" placeholder="Create a name for the list" id="name" required />
                    {
                        errorsForm.name && (
                            <small className="text-red-500 text-sm">
                                {errorsForm.name}
                            </small>
                        )
                    }
                </label>
                <label className="flex flex-col font-semibold text-md text-gray-600 mx-auto lg:mx-0 my-2 lg:my-5" htmlFor="description">
                    A description for the List
                    <textarea  { ...register('list_description', { max:60  }) } type="text" placeholder="Create a description for the list (optional)" id="description" />
                    {
                        errorsForm.description && (
                            <small className="text-red-500 text-sm">
                                {errorsForm.description}
                            </small>
                        )
                    }
                </label>

                <input type="number" hidden value={auth.user.id} { ...register('user_id') } />
                
                <button type="submit" className="flex flex-row container text-center justify-center text-white rounded-md lg:w-28 lg:h-10 bg-green-500 items-center hover:bg-green-700 hover:shadow-md">Create</button>
                
            </form>
            
        </Authenticated>
    )

}  
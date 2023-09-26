import React from 'react'

function NotificationMessage({ message, hideNotification }) {
    return (
        <div className="flex max-w-sm w-full justify-between fixed bottom-0 right-0 bg-white rounded-xl shadow-lg border px-6 py-5 mx-6 my-8">
            <div className="flex items-center font-semibold text-base text-gray-500 capitalize">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="text-green-500 w-6 h-6">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <div className="ml-2">{message}</div>
            </div>
            <button
                className='text-gray-400 hover:text-gray-500'
                onClick={() => hideNotification()}>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>

            </button>
        </div>
    )
}

export default NotificationMessage
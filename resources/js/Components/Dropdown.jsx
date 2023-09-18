import React, { useState, createContext, useContext, Fragment, useEffect } from 'react';
import { Link } from '@inertiajs/react';
import { Transition } from '@headlessui/react';

const DropDownContext = createContext();

const Dropdown = ({ children, className = "", onClick = () => { } }) => {
    const [open, setOpen] = useState(false);

    const toggleOpen = () => {
        setOpen((previousState) => !previousState);
    };

    return (
        <DropDownContext.Provider value={{ open, setOpen, toggleOpen }}>
            <div onClick={onClick} className={`relative ${className}`}>{children}</div>
        </DropDownContext.Provider>
    );
};

const Trigger = ({ children }) => {
    const { open, setOpen, toggleOpen } = useContext(DropDownContext);
    //to close to dropDown list if its used as a dropdown and selection doesnt take to any links


    useEffect(() => {
        //Bug fix for setStatusButton so that it does not close when a radio button is selected
        //TODO
        setOpen(false)


    }, [children])

    return (
        <>
            <div onClick={toggleOpen}>{children}</div>

            {open && <div className="fixed inset-0 z-40" onClick={() => setOpen(false)}></div>}
        </>
    );
};

const Content = ({ hasLinks = true, align = 'right', width = '48', contentClasses = 'py-1 bg-white', children }) => {
    console.log("hasLinks : ", hasLinks)
    const { open, setOpen } = useContext(DropDownContext);

    let alignmentClasses = 'origin-top';

    if (align === 'left') {
        alignmentClasses = 'origin-top-left left-0';
    } else if (align === 'right') {
        alignmentClasses = 'origin-top-right right-0';
    }


    let widthClasses = '';

    if (width === '48') {
        widthClasses = 'w-full';
    }
    else {
        widthClasses = width
    }

    return (
        <>
            <Transition
                as={Fragment}
                show={open}
                enter="transition ease-out duration-200"
                enterFrom="opacity-0 scale-95"
                enterTo="opacity-100 scale-100"
                leave="transition ease-in duration-75"
                leaveFrom="opacity-100 scale-100"
                leaveTo="opacity-0 scale-95"
            >
                <div
                    className={`absolute z-50 mt-2 rounded-md shadow-lg ${alignmentClasses} ${widthClasses}`}
                    onClick={(e) => {
                        console.log("radio clicked")
                        console.log("hasLink in anonymous F : ", hasLinks)
                        if (hasLinks) {
                            setOpen(false)
                        }
                    }}
                >
                    <div className={`rounded-md ring-1 ring-black ring-opacity-5 ` + contentClasses}>{children}</div>
                </div>
            </Transition>
        </>
    );
};

const DropdownLink = ({ asUnLinkedButton = false, className = '', children, ...props }) => {
    return (
        <Link
            {...props}
            className={
                'block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out ' +
                className
            }
        >
            {children}
        </Link>
    );
};

Dropdown.Trigger = Trigger;
Dropdown.Content = Content;
Dropdown.Link = DropdownLink;

export default Dropdown;

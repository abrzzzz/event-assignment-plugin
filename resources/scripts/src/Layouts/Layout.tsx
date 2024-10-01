import { ReactNode } from "react";
import { Link } from "@inertiajs/react";

export default function Layout({ children } : {children: ReactNode})
{

    return (
        <div className="container">
            <div>
                <div className=" overflow-hidden relative rounded-b-[60px] bg-purple-900 p-10 shadow-md">
                    <div className="flex justify-between items-center menu">
                        <div className="text-white flex">
                            <ul className="flex gap-4">
                                <li> 
                                    <a href="/">
                                        home
                                    </a>
                                </li>
                                <li>
                                    <Link href="/events">Events</Link>
                                </li>
                            </ul>
                        </div>
                        <div> 
                            <div className="text-gray-200 text-sm font-light">Current Location</div>
                            <span className="text-white ">California - USA</span>
                        </div>
                    </div>
                </div>
                {children}
            </div>
        </div>
    );

}
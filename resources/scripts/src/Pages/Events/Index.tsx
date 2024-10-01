import { Carousel, CarouselContent, CarouselItem } from "@/components/Carousel";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/Input";
import Layout from "@/Layouts/Layout";
import { Link, usePage } from "@inertiajs/react";
import axios from "axios";
import { useCallback, useState } from "react";
import { DebounceInput } from 'react-debounce-input';
import {
    Alert,
    AlertDescription,
    AlertTitle,
} from "@/components/ui/alert";

const TypeRandomColors = [
    "#F0635A",
    "#F59762",
    "#29D697",
    "#46CDFB"
]

const randomBg = () => {
    let bg = TypeRandomColors[Math.floor(Math.random() * TypeRandomColors.length)];
    return bg;
}


export default function Index({ events, types }) {

    const [eventResult, setEventResult] = useState(events);
    const { props } = usePage();


    const handleSearch = (e) => {

        axios.get('/wp-json/wp/v2/events', {
            params: {
                key: e.target.value
            }
        }).then(res => {
            setEventResult(res.data)
        }).catch(err => {
            console.log(err);
        });

    }

    return (
        <Layout>
            <div id="filter" className="relative -top-5 w-3/4  rounded-xl m-auto">
                <Carousel
                >
                    <CarouselContent>
                        {
                            types && types.map(t => {
                                return (
                                    <CarouselItem className="basis-1/4">
                                        <Button className={`rounded-full bg-[#F59762]  !bg-[` + randomBg() + `]`} size={'lg'}>{t?.name}</Button>
                                    </CarouselItem>
                                )
                            })
                        }
                    </CarouselContent>
                </Carousel>
            </div>
            <div className="flex flex-col gap-4">
                <div>

                    <DebounceInput
                        className="bg-white rounded-lg p-2 w-full shadow mt-5"
                        placeholder="Search Events"
                        minLength={3}
                        debounceTimeout={300}
                        onChange={handleSearch} />

                </div>

                <div className="grid grid-cols-2 gap-5">
                    {
                        eventResult.length > 0 && eventResult.map((e) => {
                            let detail = e?.event_detail[0];
                            return (
                                <div className="bg-white shadow-md p-5 rounded-[15px]">
                                    <div className="flex flex-col  gap-5 ">
                                        <Link href={`/events/` + e.ID}>
                                            <img className="rounded-lg" src={e?.featured_image} />
                                        </Link>
                                        <div className="text-left flex flex-col gap-1">
                                            <div className="flex gap-5">
                                                <p className="font-light"> date: <span className="text-purple-900">{detail?.date}</span></p>
                                                <p className="font-light"> location: <span className="text-purple-900">{detail?.location}</span></p>
                                            </div>
                                            <Link href={`/events/` + e.ID}>
                                                <h3 className="font-bold">{e?.post_title}</h3>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            )
                        })

                    }


                </div>

                {
                    eventResult.length === 0 && (
                        <Alert className="bg-transparent border-0 text-lg text-center w-full ">
                            <AlertTitle className="text-red-800 font-bold">No Record</AlertTitle>
                        </Alert>
                    )
                }
            </div>
        </Layout>
    )

}
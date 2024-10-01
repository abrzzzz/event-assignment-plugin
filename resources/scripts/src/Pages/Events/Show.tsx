import { Button } from "@/components/ui/button";
import Layout from "@/Layouts/Layout";
import { usePage } from "@inertiajs/react";
import parse from 'html-react-parser';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/Dialog";
import { Input } from "@/components/ui/Input";
import { useState } from "react";
import axios from "axios";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";

export default function Show({ event }) {

    const { props } = usePage();

    const [userEmail, setUserEmail] = useState(false);
    const [requestResponse, setRequestResponse] = useState(null);
    const [errors, setErrors] = useState(null);

    const detail = event?.event_detail[0];

    const attendThisEvent = () => {

        if (!userEmail) return;
        axios.post('/events/' + event.ID + '/attend', {
            email: userEmail,
            security: props?.security?.nonce,
        }
        ).then((res) => {
            setRequestResponse(res.data);
        }).catch(err => {
            setErrors(err.response.data.errors)
        });

    }

    return (
        <Layout>
            {
                requestResponse?.email &&
                <Alert className="my-10 bg-green-700 rounded-lg">
                    <AlertTitle className="text-white text-md font-bold mb-0">You Attended to this event please check the email ({requestResponse?.email})</AlertTitle>
                </Alert>
            }
            <div className="flex  flex-col  gap-5 p-4 bg-white  mt-10 rounded-lg mb-20">
                <div className="">
                    <img className="rounded-lg w-full" src={event?.featured_image} />
                </div>
                <div className="flex gap-5 justify-between">
                    <p> date: <span className="text-purple-900">{detail?.date}</span></p>
                    <p> location: <span className="text-purple-900">{detail?.location}</span></p>
                </div>
                <div>
                    <h3 className="font-bold text-2xl" >{event?.post_title}</h3>
                </div>
                <div>
                    {parse(event?.post_content)}
                </div>
            </div>
            {
                !requestResponse &&
                <Dialog>
                    <DialogTrigger asChild>
                        <Button className="fixed bg-purple-900 w-20 bottom-10 m-auto right-0 left-0 px-40 py-5 rounded-full text-lg">Attend this event</Button>
                    </DialogTrigger>
                    <DialogContent className="sm:max-w-md">
                        <DialogHeader>
                            <DialogTitle>Your Email</DialogTitle>
                            <DialogDescription>
                                Please fill the input with your email
                            </DialogDescription>
                        </DialogHeader>
                        <ol className="list-disc px-5">
                            {
                                errors && errors.map((e) => {
                                    return (
                                        <li className="text-sm p-1 text-red-800">{e}</li>
                                    )
                                })
                            }
                        </ol>
                        <div className="flex items-center space-x-2">
                            <div className="grid flex-1 gap-2">
                                <Input
                                    id="link"
                                    onChange={(e) => { setUserEmail(e.target.value) }}
                                />
                            </div>
                            <Button type="submit" onClick={attendThisEvent} size="sm" className="px-3">
                                <span className="">submit</span>
                            </Button>
                        </div>
                        <DialogFooter className="sm:justify-start">
                            <DialogClose asChild>
                                <Button type="button" variant="secondary">
                                    Close
                                </Button>
                            </DialogClose>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
                ||
                <Button className="fixed bg-gray w-20 bottom-10 m-auto right-0 left-0 px-40 py-7 rounded-full text-lg">
                    Hoora! You've just attended this event
                </Button>


            }
        </Layout>
    )

}
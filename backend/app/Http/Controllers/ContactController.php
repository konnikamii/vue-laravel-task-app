<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\CreateContactRequest;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Create a new contact.
     */
    public function createContact(CreateContactRequest $request)
    {
        $contact = Contact::create($request->validated());
        try {
            // Send email to MailHog
            Mail::raw($contact->message, function ($message) use ($contact) {
                $message->to('your-email@example.com')->subject($contact->subject)->from($contact->email);
            });
        } catch (\Exception $e) {
            return response()->json('Contact saved!', 201);
        }

        return response()->json('Message sent successfully!', 200);
    }
}

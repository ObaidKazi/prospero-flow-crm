<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\MainController;
use App\Repositories\ContactRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactSaveController extends MainController
{
    private ContactRepository $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function save(Request $request): RedirectResponse
    {
        $contact = $this->contactRepository->save($request->all());

        // If the request comes from the contacts page, redirect back to contacts
        $referer = $request->headers->get('referer');
        if (strpos($referer, '/contact/create') !== false || strpos($referer, '/contact/update') !== false) {
            return redirect('/contact');
        }

        // Otherwise, redirect to the lead or customer page
        if ($contact->lead_id) {
            return redirect('lead/show/'.$contact->lead_id);
        } elseif ($contact->customer_id) {
            return redirect('customer/show/'.$contact->customer_id);
        }

        // Default fallback
        return redirect('/contact');
    }
}

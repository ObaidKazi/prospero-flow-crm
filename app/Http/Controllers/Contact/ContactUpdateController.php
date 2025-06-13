<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class ContactUpdateController extends Controller
{
    public function update(int $id)
    {
        $contact = Contact::find($id);
        
        // Get leads for dropdown selection
        $company_id = Auth::user()->company_id;
        $leads = Lead::query()
            ->orderBy('name')
            ->get();

        return view('contact.contact', compact('contact', 'leads'));
    }
}

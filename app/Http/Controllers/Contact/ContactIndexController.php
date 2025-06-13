<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContactIndexController extends Controller
{
    public function index(Request $request): View
    {
        $company_id = Auth::user()->company_id;
        $contacts = Contact::where('company_id', $company_id)
            ->with(['lead', 'customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $leads = Lead::where('company_id', $company_id)->orderBy('name')->get();
        $contact = new Contact();

        return view('contact.index', compact('contacts', 'leads', 'contact'));
    }
}

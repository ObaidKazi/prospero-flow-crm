<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContactCreateController extends Controller
{
    public function create(Request $request, string $model = null, string $id_model = null): View
    {
        $contact = new Contact;
        
        // If model and id_model are provided, associate the contact with that model
        if ($model && $id_model) {
            $contact->{$model.'_id'} = $id_model;
        }
        
        // Get leads for dropdown selection
        $leads = Lead::query()
            ->orderBy('name')
            ->get();

        return view('contact.contact', compact('contact', 'leads'));
    }
}

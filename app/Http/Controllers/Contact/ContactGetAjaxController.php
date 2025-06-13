<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ContactGetAjaxController extends Controller
{
    public function searchByName(string $name): JsonResponse
    {
        $company_id = Auth::user()->company_id;
        
        $contacts = Contact::where('company_id', $company_id)
            ->where(function ($query) use ($name) {
                $query->where('first_name', 'like', "%$name%")
                      ->orWhere('last_name', 'like', "%$name%");
            })
            ->limit(10)
            ->get();
            
        return response()->json($contacts);
    }
}

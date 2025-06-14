<?php

declare(strict_types=1);

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\MainController;
use App\Models\Industry;
use App\Models\Lead;
use App\Models\Company;
use App\Models\Source;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Squire\Models\Country;

class LeadCreateController extends MainController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $lead = new Lead;
        $industry = new Industry;
        $user = new User;
        $data['lead'] = $lead;
        $data['countries'] = Country::orderBy('name')->get();
        // Temporary fix get this from configuration
        if (Auth::user()->company_id == 3) {
            $industries = $industry->getAllByCompany((int) Auth::user()->company_id);
        } else {
            $industries = $industry->getAll();
        }
        $data['industries'] = $industries;
        $data['sellers'] = $user->getAllActiveByCompany((int) Auth::user()->company_id);
        $data['tags']=Tag::all();
        $data['sources'] = Source::all();
        $data['editorType'] = 'advanced';
        $data['companies'] = [];
        if(Auth::user()->hasRole('SuperAdmin')){
            $data['companies'] = Company::all();
        }

        return view('lead.lead', $data);
    }
}

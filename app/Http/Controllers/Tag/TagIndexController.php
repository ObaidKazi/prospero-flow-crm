<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagIndexController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $tags = Tag::query();

        if ($search) {
            $tags = $tags->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
            });
        }

        $tags = $tags->orderBy('name')->paginate(25);

        return view('tag.index', [
            'tags' => $tags,
            'search' => $search,
            'bootstrap_colors' => [
                'bg-primary',
                'bg-secondary',
                'bg-success',
                'bg-danger',
                'bg-warning text-dark',
                'bg-info text-dark',
            ],
        ]);
    }
}
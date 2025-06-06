<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagCreateController extends Controller
{
    public function create()
    {
        $tag = new Tag();

        return view('tag.tag', [
            'tag' => $tag,
        ]);
    }
}
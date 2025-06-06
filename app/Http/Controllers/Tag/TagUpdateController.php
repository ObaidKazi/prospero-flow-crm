<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagUpdateController extends Controller
{
    public function update($id)
    {
        $tag = Tag::findOrFail($id);

        return view('tag.tag', [
            'tag' => $tag,
        ]);
    }
}
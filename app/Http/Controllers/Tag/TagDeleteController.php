<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagDeleteController extends Controller
{
    public function delete($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect('/tag')->with('status', __('Tag deleted successfully'));
    }
}
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Repositories\TagRepository;

class TagSaveController extends Controller
{
    public function save(TagRequest $request)
    {
        $repository = new TagRepository();
        $tag = $repository->save($request->all());

        return redirect('/tag')->with('status', __('Tag saved successfully'));
    }
}
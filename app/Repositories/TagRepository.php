<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class TagRepository
{
    public function save(array $data): ?Tag
    {
        if (empty($data['id'])) {
            $tag = new Tag;
            $tag->created_at = now();
        } else {
            $tag = Tag::find($data['id']);
        }
        
        $tag->name = $data['name'];
        $tag->description = !empty($data['description']) ? $data['description'] : null;
        $tag->color = !empty($data['color']) ? $data['color'] : null;

        $tag->updated_at = now();
        $tag->save();

        return $tag;
    }
}
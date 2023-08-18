<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository
{
    public function getAllTags()
    {
        return Tag::all();
    }

    public function createTag($data)
    {
        $tag = new Tag();
        $tag->name = $data['name'];
        $tag->save();
        return $tag;
    }
}

?>
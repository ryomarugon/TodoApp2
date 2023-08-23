<?php
namespace App\Services;

use App\Models\Tag;
use App\Repositories\TagRepository;

class TagService
{
    protected $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function getAllTags()
    {
        return $this->tagRepository->getAllTags();
    }

    public function createTag($data)
    {
        return $this->tagRepository->createTag($data);
    }
}
?>
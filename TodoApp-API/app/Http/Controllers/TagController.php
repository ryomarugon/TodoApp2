<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TagService;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index()
    {
        $tags = $this->tagService->getAllTags();
        return response()->json($tags);
    }

    public function store(Request $request)
    {
        // バリデーションやリクエストの加工を行う部分
        // ...
        $tag = $this->tagService->createTag($request->all());
        return response()->json($tag);
    }
}


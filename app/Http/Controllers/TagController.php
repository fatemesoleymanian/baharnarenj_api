<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::query()->with('post')->orderByDesc('id')->get();
        return successResponse([
            'tags' => $tags
        ]);
    }
}

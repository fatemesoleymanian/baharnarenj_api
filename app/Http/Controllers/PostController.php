<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'only-admin'])->only(['store']);
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::with(['tags','admin'])->get();
        return successResponse([
            'posts' => $posts
        ]);
    }
    public function latest(): JsonResponse
    {
        $posts = Post::with(['tags','admin'])->latest()->take(8)->get();
        return successResponse([
            'posts' => $posts
        ]);
    }

    /**
     * @param PostRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store(PostRequest $request)
    {
        $data = filterData($request->validated());
        $admin = currentUser();
        $data['slug'] =  Str::slug($data['slug']);
        $post = Post::query()->create([
            'title' => $data['title'],
            'text' => $data['text'],
            'image' => $data['image'],
            'slug' => $data['slug'],
            'admin_id' => $admin->getId(),

        ]);
        if (exists($data['tags'])) {
           $tags = $data['tags'];
           foreach ($tags as $tag) {
               Tag::query()->create([
                   'title' => $tag,
                   'post_id' => $post->getId()
               ]);
           }
        }
        return successResponse();
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function show($slug): JsonResponse
    {
        $post = Post::query()->with(['tags','admin'])->where('slug',$slug)->first();
        return successResponse([
            'post' => $post
        ]);
    }

    /**
     * @param PostRequest $request
     * @param Post $post
     * @return JsonResponse
     * @throws CustomException
     */
    public function update(PostRequest $request, Post $post): JsonResponse
    {
        $data = filterData($request->validated());
//        if (isset($data['image'])) {
//            $data['image'] = handleFile('/posts', $data['image']);
//        }
        $data['slug'] =  Str::slug($data['slug']);
        $post->update([
            'image' => $data['image'] ?? $post->image,
            'title' => $data['title'] ?? $post->title,
            'text' => $data['text'] ?? $post->text,
            'slug' => $data['slug'],
        ]);
        if (isset($data['tags'])) {
            Tag::query()->where('post_id', $post->getId())->delete();
            $tags =  $data['tags'];
            foreach ($tags as $tag) {
                Tag::query()->create([
                    'post_id' => $post->getId(),
                    'title' => $tag
                ]);
            }
        }
        return successResponse();
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        $path = parse_url($post['image']);
        $remove = \Illuminate\Support\Facades\File::delete(public_path($path['path']));
        $post->delete();
        return successResponse();
    }

    /**
     * @return JsonResponse
     */
    public function allForAdmin(): JsonResponse
    {
        $posts = Post::with(['tags', 'admin'])->get()->makeHidden('text');
        return successResponse([
            'posts' => $posts
        ]);
    }
}

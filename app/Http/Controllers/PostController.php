<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = auth()->user();
        return PostResource::collection($user->posts()->paginate($request->get('per_page', 10)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostRequest $request
     * @return PostResource
     * @throws AuthorizationException
     */
    public function store(PostRequest $request): PostResource
    {
        $this->authorize('create', Post::class);

        $post = auth()->user()->posts()->create($request->validated());
        $post->addMedia($request->file('thumbnail'))
            ->toMediaCollection('posts', 'media');
        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return PostResource
     * @throws AuthorizationException
     */
    public function show(Post $post): PostResource
    {
        $this->authorize('view', $post);
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PostRequest $request
     * @param Post $post
     * @return PostResource
     * @throws AuthorizationException
     */
    public function update(PostRequest $request, Post $post): PostResource
    {
        $this->authorize('update', $post);
        $post->update($request->validated());
        $post->addMedia($request->file('thumbnail'))
            ->toMediaCollection('posts', 'media');
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);
        $post->delete();
        return  response()->json([
            'message' => 'delete was successfully'
        ], Response::HTTP_OK);

    }
}

<?php

namespace App\Http\Controllers\API\V1\Communication;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Communication\StoreForumPostRequest; // To be created
use App\Http\Requests\Communication\StoreForumThreadRequest; // To be created
use App\Models\Communication\Forum;
use App\Models\Communication\ForumThread;
use App\Services\Communication\ForumService;
use Illuminate\Http\JsonResponse;

class ForumController extends Controller
{
    protected $forumService;

    public function __construct(ForumService $forumService)
    {
        $this->forumService = $forumService;
    }

    /**
     * Display a listing of all forums or threads within a forum.
     *
     * @param Forum|null $forum
     * @return JsonResponse
     */
    public function index(Forum $forum = null): JsonResponse
    {
        if ($forum) {
            $threads = $this->forumService->getThreadsForForum($forum);
            return ResponseHelper::success($threads);
        }

        $forums = $this->forumService->getAllForums();
        return ResponseHelper::success($forums);
    }

    /**
     * Display a specific thread with its posts.
     *
     * @param ForumThread $thread
     * @return JsonResponse
     */
    public function showThread(ForumThread $thread): JsonResponse
    {
        $posts = $this->forumService->getPostsForThread($thread);
        return ResponseHelper::success($posts);
    }

    /**
     * Store a new thread in a forum.
     *
     * @param StoreForumThreadRequest $request
     * @param Forum $forum
     * @return JsonResponse
     */
    public function storeThread(StoreForumThreadRequest $request, Forum $forum): JsonResponse
    {
        $thread = $this->forumService->createThread($forum, $request->user(), $request->validated());
        return ResponseHelper::success($thread, 'Thread created successfully.', 201);
    }

    /**
     * Store a new post in a thread (i.e., a reply).
     *
     * @param StoreForumPostRequest $request
     * @param ForumThread $thread
     * @return JsonResponse
     */
    public function storePost(StoreForumPostRequest $request, ForumThread $thread): JsonResponse
    {
        $post = $this->forumService->createPost($thread, $request->user(), $request->validated());
        return ResponseHelper::success($post, 'Reply posted successfully.', 201);
    }
}
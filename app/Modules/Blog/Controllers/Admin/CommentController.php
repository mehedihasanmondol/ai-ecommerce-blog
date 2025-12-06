<?php

namespace App\Modules\Blog\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Blog\Services\CommentService;
use Illuminate\Http\Request;

/**
 * ModuleName: Blog
 * Purpose: Admin controller for blog comment moderation
 * 
 * @category Blog
 * @package  App\Modules\Blog\Controllers\Admin
 * @author   AI Assistant
 * @created  2025-11-07
 */
class CommentController extends Controller
{
    protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        // Authorization check
        if (!auth()->user()->hasPermission('blog-comments.view')) {
            abort(403, 'Unauthorized action.');
        }

        $filters = $request->only(['status', 'post_id']);
        $comments = $this->commentService->getAllComments(config('app.paginate', 20), $filters);
        $counts = $this->commentService->getCommentsCountByStatus();

        return view('admin.blog.comments.index', compact('comments', 'counts'));
    }

    public function approve($id)
    {
        // Authorization check
        if (!auth()->user()->hasPermission('blog-comments.approve')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $this->commentService->approveComment($id);

        return response()->json([
            'success' => true,
            'message' => 'মন্তব্য অনুমোদিত হয়েছে',
        ]);
    }

    public function spam($id)
    {
        // Authorization check
        if (!auth()->user()->hasPermission('blog-comments.approve')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $this->commentService->markAsSpam($id);

        return response()->json([
            'success' => true,
            'message' => 'মন্তব্য স্প্যাম হিসেবে চিহ্নিত হয়েছে',
        ]);
    }

    public function trash($id)
    {
        // Authorization check
        if (!auth()->user()->hasPermission('blog-comments.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $this->commentService->moveToTrash($id);

        return response()->json([
            'success' => true,
            'message' => 'মন্তব্য ট্র্যাশে সরানো হয়েছে',
        ]);
    }

    public function destroy($id)
    {
        // Authorization check
        if (!auth()->user()->hasPermission('blog-comments.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $this->commentService->deleteComment($id);

        return response()->json([
            'success' => true,
            'message' => 'মন্তব্য সফলভাবে মুছে ফেলা হয়েছে',
        ]);
    }
}

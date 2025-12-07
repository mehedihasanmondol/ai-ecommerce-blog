<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Services\FeedbackService;
use Illuminate\Http\Request;

/**
 * Admin Feedback Controller
 * 
 * Manages feedback approval, rejection, and deletion
 * 
 * @category Controllers
 * @package  App\Http\Controllers\Admin
 * @author   Windsurf AI
 * @created  2025-11-25
 */
class FeedbackController extends Controller
{
    protected $feedbackService;

    public function __construct(FeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }

    /**
     * Display feedback management page
     */
    public function index()
    {
        // Authorization check
        if (!auth()->user()->hasPermission('feedback.view')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.feedback.index');
    }

    /**
     * Show feedback details
     */
    public function show(Feedback $feedback)
    {
        $feedback->load('user', 'approver');

        return view('admin.feedback.show', compact('feedback'));
    }

    /**
     * Approve feedback
     */
    public function approve(Feedback $feedback)
    {
        // Authorization check
        if (!auth()->user()->hasPermission('feedback.approve')) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $this->feedbackService->approveFeedback($feedback);

        return redirect()
            ->back()
            ->with('success', 'Feedback approved successfully!');
    }

    /**
     * Reject feedback
     */
    public function reject(Feedback $feedback)
    {
        // Authorization check
        if (!auth()->user()->hasPermission('feedback.reject')) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $this->feedbackService->rejectFeedback($feedback);

        return redirect()
            ->back()
            ->with('success', 'Feedback rejected successfully!');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeature(Feedback $feedback)
    {
        // Authorization check
        if (!auth()->user()->hasPermission('feedback.feature')) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $this->feedbackService->toggleFeatured($feedback);

        return redirect()
            ->back()
            ->with('success', 'Featured status updated!');
    }

    /**
     * Delete feedback
     */
    public function destroy(Feedback $feedback)
    {
        // Authorization check
        if (!auth()->user()->hasPermission('feedback.delete')) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $feedback->delete();

        return redirect()
            ->route('admin.feedback.index')
            ->with('success', 'Feedback deleted successfully!');
    }
}

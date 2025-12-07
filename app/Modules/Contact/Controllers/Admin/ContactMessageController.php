<?php

namespace App\Modules\Contact\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of contact messages
     */
    public function index(): View
    {
        if (!auth()->user()->hasPermission('contact-messages.view')) {
            abort(403, 'You do not have permission to view contact messages.');
        }

        return view('admin.contact.messages.index');
    }

    /**
     * Display the specified message
     */
    public function show(ContactMessage $message): View
    {
        if (!auth()->user()->hasPermission('contact-messages.view')) {
            abort(403, 'You do not have permission to view contact messages.');
        }

        // Mark as read if unread
        if ($message->status === 'unread') {
            $message->markAsRead();
        }

        return view('admin.contact.messages.show', compact('message'));
    }

    /**
     * Update message status
     */
    public function updateStatus(Request $request, ContactMessage $message): RedirectResponse
    {
        if (!auth()->user()->hasPermission('contact-messages.update-status')) {
            abort(403, 'You do not have permission to update message status.');
        }

        $validated = $request->validate([
            'status' => 'required|in:unread,read,replied,archived',
            'admin_note' => 'nullable|string',
        ]);

        $message->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? $message->admin_note,
            'read_at' => $validated['status'] !== 'unread' ? now() : null,
        ]);

        return redirect()
            ->route('admin.contact.messages.show', $message)
            ->with('success', 'Message status updated successfully.');
    }

    /**
     * Delete the specified message
     */
    public function destroy(ContactMessage $message): RedirectResponse
    {
        if (!auth()->user()->hasPermission('contact-messages.delete')) {
            abort(403, 'You do not have permission to delete contact messages.');
        }

        $message->delete();

        return redirect()
            ->route('admin.contact.messages.index')
            ->with('success', 'Message deleted successfully.');
    }

    /**
     * Bulk action on messages
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        if (!auth()->user()->hasPermission('contact-messages.bulk-action')) {
            abort(403, 'You do not have permission to perform bulk actions on messages.');
        }

        $validated = $request->validate([
            'action' => 'required|in:mark_read,mark_replied,archive,delete',
            'messages' => 'required|array',
            'messages.*' => 'exists:contact_messages,id',
        ]);

        $messages = ContactMessage::whereIn('id', $validated['messages'])->get();

        switch ($validated['action']) {
            case 'mark_read':
                foreach ($messages as $message) {
                    $message->markAsRead();
                }
                $successMessage = 'Messages marked as read.';
                break;
            case 'mark_replied':
                foreach ($messages as $message) {
                    $message->markAsReplied();
                }
                $successMessage = 'Messages marked as replied.';
                break;
            case 'archive':
                foreach ($messages as $message) {
                    $message->archive();
                }
                $successMessage = 'Messages archived.';
                break;
            case 'delete':
                ContactMessage::whereIn('id', $validated['messages'])->delete();
                $successMessage = 'Messages deleted.';
                break;
        }

        return redirect()
            ->route('admin.contact.messages.index')
            ->with('success', $successMessage);
    }
}

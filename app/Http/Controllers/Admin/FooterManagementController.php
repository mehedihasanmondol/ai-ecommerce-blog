<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterSetting;
use App\Models\FooterLink;
use App\Models\FooterBlogPost;
use Illuminate\Http\Request;

class FooterManagementController extends Controller
{
    public function index()
    {
        $settings = FooterSetting::all()->groupBy('group');
        $blogPosts = FooterBlogPost::orderBy('sort_order')->get();

        return view('admin.footer-management.index', compact('settings', 'blogPosts'));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            FooterSetting::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }


    public function storeBlogPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('footer/blog', 'public');
        }

        $validated['sort_order'] = FooterBlogPost::max('sort_order') + 1;
        FooterBlogPost::create($validated);

        return redirect()->back()->with('success', 'Blog post added successfully!');
    }

    public function deleteBlogPost(FooterBlogPost $blogPost)
    {
        if ($blogPost->image) {
            \Storage::disk('public')->delete($blogPost->image);
        }
        $blogPost->delete();
        return redirect()->back()->with('success', 'Blog post deleted successfully!');
    }
}

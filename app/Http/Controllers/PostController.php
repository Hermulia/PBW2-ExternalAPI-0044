<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    // Display all posts
    public function index()
    {
        try {
            $response = Http::get('https://jsonplaceholder.typicode.com/posts');
            $posts = $response->json();

            // Check if the response was successful
            if ($response->failed()) {
                return redirect()->route('posts.index')->with('error', 'Failed to fetch posts.');
            }

            return view('posts.index', ['posts' => $posts]);
        } catch (\Exception $e) {
            return redirect()->route('posts.index')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // Show the edit form for a specific post
    public function edit($id)
    {
        try {
            $response = Http::get("https://jsonplaceholder.typicode.com/posts/{$id}");
            $post = $response->json();

            // Check if the response was successful
            if ($response->failed()) {
                return redirect()->route('posts.index')->with('error', 'Failed to fetch the post for editing.');
            }

            return view('posts.edit', ['post' => $post]);
        } catch (\Exception $e) {
            return redirect()->route('posts.index')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // Update the post
    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        try {
            $response = Http::put("https://jsonplaceholder.typicode.com/posts/{$id}", [
                'title' => $validated['title'],
                'body' => $validated['body'],
            ]);

            // Check if the response was successful
            if ($response->successful()) {
                return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
            }

            return redirect()->route('posts.index')->with('error', 'Failed to update the post.');
        } catch (\Exception $e) {
            return redirect()->route('posts.index')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // Delete the post
    public function destroy($id)
    {
        try {
            $response = Http::delete("https://jsonplaceholder.typicode.com/posts/{$id}");

            // Check if the response was successful
            if ($response->successful()) {
                return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
            }

            return redirect()->route('posts.index')->with('error', 'Failed to delete the post.');
        } catch (\Exception $e) {
            return redirect()->route('posts.index')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}

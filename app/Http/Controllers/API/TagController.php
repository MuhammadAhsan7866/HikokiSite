<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    // Fetch all tags with pagination
    public function index()
    {
        $tags = Tag::select('id','name')->get();
        return response()->json([
            'data' => $tags
        ]);
    }

    // Store a new tag
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ], [
            'name.required' => 'Tag Name is required.'
        ]);

        $tag = Tag::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Tag Created Successfully',
            'data' => $tag
        ], 201); // 201 for created resource
    }

    // Show a single tag by ID
    public function show($id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found'
            ], 404);
        }

        return response()->json([
            'data' => $tag
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ], [
            'name.required' => 'Tag Name is required.'
        ]);

        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found'
            ], 404);
        }

        $tag->update([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Tag Updated Successfully',
            'data' => $tag
        ]);
    }

    // Delete a tag
    public function destroy($id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found'
            ], 404);
        }

        $tag->delete();

        return response()->json([
            'message' => 'Tag Deleted Successfully'
        ]);
    }
}

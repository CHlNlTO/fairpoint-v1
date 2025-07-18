<?php
// app/Http/Controllers/Api/V1/BlogPostController.php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;

class BlogPostController extends Controller
{
    /**
     * Get all blog posts with tags
     */
    public function index(): JsonResponse
    {
        try {
            $blogPosts = BlogPost::with('tags')
                ->orderBy('order', 'asc')
                ->orderBy('published_date', 'desc')
                ->get();

            $totalCount = $blogPosts->count();
            $activeCount = $blogPosts->where('active', true)->count();
            $inactiveCount = $totalCount - $activeCount;

            return response()->json([
                'success' => true,
                'message' => 'Blog posts retrieved successfully',
                'data' => $blogPosts,
                'meta' => [
                    'total_count' => $totalCount,
                    'active_count' => $activeCount,
                    'inactive_count' => $inactiveCount,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve blog posts',
                'data' => null,
                'error' => [
                    'code' => 500,
                    'type' => 'INTERNAL_SERVER_ERROR',
                    'details' => config('app.debug') ? $e->getMessage() : 'An error occurred'
                ]
            ], 500);
        }
    }

    /**
     * Get a single blog post by ID with tags
     */
    public function show($id): JsonResponse
    {
        try {
            $blogPost = BlogPost::with('tags')->find($id);

            if (!$blogPost) {
                return response()->json([
                    'success' => false,
                    'message' => 'Blog post not found',
                    'data' => null,
                    'error' => [
                        'code' => 404,
                        'type' => 'NOT_FOUND'
                    ]
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Blog post retrieved successfully',
                'data' => $blogPost
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve blog post',
                'data' => null,
                'error' => [
                    'code' => 500,
                    'type' => 'INTERNAL_SERVER_ERROR',
                    'details' => config('app.debug') ? $e->getMessage() : 'An error occurred'
                ]
            ], 500);
        }
    }
}

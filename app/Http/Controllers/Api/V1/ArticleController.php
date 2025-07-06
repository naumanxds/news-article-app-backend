<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleListRequest;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ArticleListRequest $request): JsonResponse
    {
        $articles = Article::with('tag')
            ->filter($request->get('filters', []))
            ->paginateFilter();

        return response()->json(array_merge(
            $articles->toArray(),
            [
                'success' => 1,
                'message' => __('api.data.found_successful'),
            ]
        ), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}

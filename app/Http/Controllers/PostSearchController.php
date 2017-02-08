<?php

namespace App\Http\Controllers;

use App\Version;
use Illuminate\Http\Request;
use App\Http\Requests\PostSearchRequest;

class PostSearchController extends Controller
{
    public function search(PostSearchRequest $request)
    {
        $search_query = $request->input('query');
        $versions = Version::where('version', 'like', '%' . $search_query . '%')
            ->orWhereHas('software', function($q) use ($search_query) {
                $q->where('name', 'like', '%' . $search_query . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->view('search.results', ['versions' => $versions]);
    }
}

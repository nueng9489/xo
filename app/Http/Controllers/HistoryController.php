<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $query = DB::table('histories')
                   ->orderBy('created_at', 'DESC')
                   ->paginate(10);

        return view('history.index')->with(compact('query'));
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'log' => 'required'
        ]);

        $histories = History::create($data);

        return Response::json($histories);
    }
}

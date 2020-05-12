<?php

namespace App\Http\Controllers;

use App\Family;
use App\Http\Requests\FamilyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item = Family::whereNull('parent')->withChildCount()->with('childRecursive')->first();

        return view('tree.index', compact('item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FamilyRequest $request)
    {
        Family::create($request->all());

        return redirect('/')->withStatus(__('erfolgreich hinzugefügt'));
    }

    public function parent(FamilyRequest $request, Family $item)
    {
        $item->child()->create($request->all());

        return redirect('/')->withStatus(__('erfolgreich hinzugefügt'));
    }
}

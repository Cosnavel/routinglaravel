<?php

namespace App\Http\Controllers;

use App\Family;
use Illuminate\Http\Request;
use App\Http\Requests\FamilyRequest;


class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tree = Family::with('childrenRecursive')->get();


        return view('family.index', compact('tree'));
    }

    public function store(FamilyRequest $request)
    {
        //
        $family = new Family;
        $family->name = $request->name;
        $family->birth = $request->date;
        $family->gender = $request->gender;

        $family->save();

        return redirect('family')->withStatus(__('erfolgreich hinzugefügt'));

        //ddd($family);
    }

    public function parent(FamilyRequest $request, Family $item)
    {


        $item->children()->create($request->all());

        return redirect('family')->withStatus(__('erfolgreich hinzugefügt'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function show(Family $family)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function edit(Family $family)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Family $family)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function destroy(Family $family)
    {
        //
    }
}

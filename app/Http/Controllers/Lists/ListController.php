<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use App\Models\ToDoList;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = ToDoList::all();
        return Inertia::render('Lists/Lists', ["lists" => $all]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Lists/CreateList');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "list_title" => "required|min:5|max:60",
            "list_description" => "nullable|max:90",
            "user_id" => "nullable|integer",
            "group_id" => "nullable|integer",
        ]);
        $validator->validate();
        $inputs = $validator->validated();
        try {
            $list = new ToDoList($inputs);
            $list->list_state = 'incomplete';
            $list->save();
            return Redirect::route('lists', ["data" => ["message" => "The $list->id was created :D"]]);
        } catch (Exception $ex) {
            return Inertia::render('Lists/CreateList', ["data" => ["error" => $ex->getMessage()]]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ToDoList $list)
    {
        return Inertia::render('Lists/Lists', ["list" => $list]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ToDoList $list)
    {
        return Inertia::render('Lists/EditList', ["list" => $list]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ToDoList $list)
    {
        $validator = Validator::make($request->all(), [
            "list_title" => "required|min:5|max:60",
            "list_description" => "nullable|max:90",
            "user_id" => "nullable|integer",
            "group_id" => "nullable|integer",
            "list_state" => 'required'
        ]);
        $validator->validate();
        $inputs = $validator->validated();
        try {
            $list->update($inputs);
            $list->save();
            return Inertia::render('Lists/Lists', ["status" => ["data" => "The list $list->id was created!", "list" => $list]]);
        } catch (Exception $ex) {
            return Inertia::render('Lists/CreateList', ["status" => ["error" => $ex->getMessage()]]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ToDoList $list)
    {
        $id = $list->id;
        $list->destroy($id);
        $all = ToDoList::all();
        return Inertia::render('Lists/Lists', ["status" => ["data" => "The list $id was deleted!", "lists" => $all]]);
    }
}

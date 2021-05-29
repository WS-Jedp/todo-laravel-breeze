<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\UserHasGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = Auth::user()->groups;
        return Inertia::render('Groups/Groups', ["groups" => $all]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // From to create group
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
            'name' => 'min:3|required',
            'description' => 'max:90|nullable'
         ])->validate();

        try {
            $group = new Group([
                'name' => $request->post('name'),
                'description' => $request->post('description'),
            ]);
            $group->save();
            $all = Group::all();

            return Inertia::render('Groups/Groups', ["data" => ["message" => "The group was created", "id" => $group->id], "groups" => $all]);

            
        } catch(Exception $exception) {
            return Inertia::render('Groups/Groups', ["data" => ["error" => $exception->getMessage()], "groups" => Group::all()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return Inertia::render('Groups/Group', ["groups" => $group]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        return Inertia::render('Groups/EditGroup', ["groups" => $group]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'min:3|max:60|nullable',
            'description' => 'min:3|max:90|nullable',
        ]);
        $validator->validate();

        try {
            $group->update($validator->validated());
            $group->save();
            return Inertia::render('Groups/Group', ["groups" => $group]);
        } catch(Exception $exception) {
            return Inertia::render('Groups/Group', ["groups" => $group, "data" => ["error" => true, "message" => $exception->getMessage()]]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $id = $group->id;
        $group->delete($id);
        $all = Group::all();
        return Inertia::render('Groups/Groups', ["data" => ["message" => "The group $id was deleted!"], "groups" => $all]);
    }

    // Post
    public function addUser(Request $request, Group $group)
    {
        Validator::make($request->all(), [
            "user_id" => "required|integer"
        ])->validate();
        $user_id = $request->post('user_id');
        
        $userHasGroup = new UserHasGroup([
            "user_id" => $request->post('user_id'),
            "group_id" => $group->id
        ]);
        $userHasGroup->save();
        return Inertia::render(
            'Groups/Group', 
            [
                "data" => 
                    [
                        "message" => "The $user_id was added to the group $group->id", 
                    ],
                "groups" => $group
            ]
        );
    }

    public function deleteUser(Request $request, Group $group)
    {
        Validator::make($request->all(), [
            "user_id" => "required|integer"
        ])->validate();

        $group_id = $group->id;
        $user_id = $request->post('user_id');
            
        UserHasGroup::where('group_id', $group_id)
                            ->where('user_id', $user_id)
                            ->delete();

        $all = $request->user()->groups;
        return Inertia::render('Groups/Groups', ["data" => ["message" => "The $user_id was deleteed from the $group_id group"], "groups" => $all]);
    }
}

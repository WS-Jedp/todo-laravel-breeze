<?php

namespace App\Http\Controllers\Lists;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\ToDoList;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            "task_title" => "required|max:60|min:3",
            "task_description" => "nullable|max:90",
            "task_deadline" => "date|required",
            "to_do_list_id" => "required|integer"
        ]);
        $validator->validate();
        $inptus = $validator->validated();

        $task = new Task($inptus);
        $task->task_state = 'not_done';
        $task->save();
        $id = $task->id;

        return Inertia::render(
            'List', 
            [
                "status" => [
                    "data" => $request->post('to_do_list_id'),
                    "message" => "The task $id was created succesful" 
                ]
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Change for an API
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            "task_title" => "required|max:60|min:3",
            "task_description" => "nullable|max:90",
            "task_deadline" => "date|required",
            "task_state" => "required"
        ]);
        $validator->validate();
        $inputs = $validator->validated();
        try {
            $task->update($inputs);
            $task->save();
            $list = ToDoList::find($task->to_do_list_id);
            return Inertia::render('List', ["status" => ["data" => "The list $task->id was updated!", "list" => $list]]);
        } catch (Exception $ex) {
            return Inertia::render('List', ["status" => ["error" => $ex->getMessage()]]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $id = $task->id;
        $list = ToDoList::find($task->to_do_list_id);
        $task->delete($id);

        return Inertia::render('List', ["status" => ["list" => $list, "data" => "The $id task was deleted"]]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Studio\Totem\Task;
use Studio\Totem\Totem;
use Studio\Totem\Contracts\TaskInterface;
use Studio\Totem\Http\Requests\TaskRequest;
use Studio\Totem\Http\Controllers\ExportTasksController;
use File;
use function storage_path;
class TasksController extends Controller
{
    
    public function dashboard()
    {
        return redirect()->route('totem.tasks.all');
    }  
 
    public function index()
    {

        return view('totem.tasks.index_new', [
            'tasks' => Task::
                orderBy('description')
                ->when(request('q'), function ($query) {
                    $query->where('description', 'LIKE', '%'.request('q').'%');
                })
                ->paginate(20),
            'task'          => null,
            'commands'      => Totem::getCommands(),
            'timezones'     => timezone_identifiers_list(),
            'frequencies'   => Totem::frequencies(),
        ])->with('i', (request()->input('page', 1) - 1) * 10);
    } 

    public function create()
    {
        return view('totem::tasks.form', [
            'task'          => new Task,
            'commands'      => Totem::getCommands(),
            'timezones'     => timezone_identifiers_list(),
            'frequencies'   => Totem::frequencies(),
        ]);
    } 

    public function store(TaskRequest $request)
    {
        Task::store($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Task Created Successfully.'
        ]);
    } 

    public function view(Task $task)
    {
        return response()->json([
            'task'  => $task,
            'results'  => $task->results->count() > 0 ? number_format(  $task->results->sum('duration') / (1000 * $task->results->count()) , 2) : '0', 
        ]);
    }

    public function edit(Task $task)
    {
        return response()->json([
            'task'          => $task,
            'commands'      => Totem::getCommands(),
            'timezones'     => timezone_identifiers_list(),
            'frequencies'   => Totem::frequencies(),
        ]);
    }
 
    public function update(TaskRequest $request, Task $task)
    {
        $task = Task::update($request->all(), $task);

        return response()->json([
            'status' => true,
            'message' => 'Task Updated Successfully.'
        ]); 
    }
 
    public function destroy($task, Request $request)
    {
        if($task){
            $task->delete();
            return response()->json([
                'status' => true,
                'message' => 'Task Deleted Successfully.'
            ]);    
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Task Not Founf.'
            ]);
        }
    } 
 
    public function status($task, Request $request)
    {
        if($task){
            if($request->active == 1){
                DB::table('crontasks')->where('id', $task->id)->update([
                    'is_active' => 0
                ]);
                $msg = 'Task Deactivated Successfully.';
            }else{
                $x = DB::table('crontasks')->where('id', $task->id)->update([
                    'is_active' => 1
                ]);
                $msg = 'Task Activated Successfully.';
            }
            return response()->json([
                'status' => true,
                'message' => $msg
            ]);    
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Task Not Found.'
            ]);
        }
    } 
 
    public function execute(Task $task)
    {
        File::put(storage_path('tasks.json'), Task::all()->toJson());

        return response()
            ->download(storage_path('tasks.json'), 'tasks.json')
            ->deleteFileAfterSend(true);
    }


}

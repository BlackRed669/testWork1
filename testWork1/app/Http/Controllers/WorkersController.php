<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Worker;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class WorkersController extends Controller
{
    public function getWorkerEdit(Request $request)
    {
        $worker = Worker::select("id","name","salary")->where("id",$request->id)->first();
        return view('worker-edit', compact(['worker']));
    }

    public function getWorkers(Request $request)
    {
        try
        {
            $rules = [
                'id' => 'integer',
                'departmentId' => 'integer',
                'minValue' => 'numeric',
                'maxValue' => 'numeric',
                'name' => 'string|max:150',
                'sort' => 'string|max:4',
                'salary' => 'numeric'
            ];
            $validator = Validator::make($request->input(), $rules);

            if ($validator->fails()) 
            {
                $error = $validator->errors()->messages();
                return Response::json(['success'=> false, 'data' => null, 'error' => array_shift($error)[0]], 200);
            }

            $workers = Worker::select("id","name","salary")
                            ->when($request->sort, function ($query, $sort) {
                                return $query->orderBy("name",$sort);
                            });

            if($request->departmentId)
                $workers = $workers->WhereRaw(" id IN (SELECT worker_id FROM department_worker WHERE department_id=$request->departmentId) ");

            if(isset($request->minValue) && isset($request->maxValue) && $request->maxValue>0)
                $workers = $workers->whereBetween('salary', [$request->minValue,$request->maxValue]);

            $workers = $workers->paginate(20);

            return Response::json(['success'=> true, 'data' => $workers, 'error' => null], 200);
        }
        catch(Exception $e)
        {
            return Response::json(['success'=> false, 'data' => null, 'error' => $e->getMessage()], 200);
        }
    }

    public function getAllDepartments()
    {
        $result = Department::select("id","name")->orderBy("name")->get();

        return Response::json(['success'=> true, 'data' => $result, 'error' => null], 200);
    }

    public function setWorker(Request $request)
    {
        try
        {
            $rules = [
                'id' => 'integer',
                'name' => 'required|string|max:150',
                'salary' => 'required|numeric'
            ];
            $validator = Validator::make($request->input(), $rules);

            if ($validator->fails()) 
            {
                $error = $validator->errors()->messages();
                return Response::json(['success'=> false, 'data' => null, 'error' => array_shift($error)[0]], 200);
            }

            $updated = Worker::updateOrCreate(["id"=>$request->id],$request->input());
            
            if($updated)
                return Response::json(['success'=> true, 'data' => $updated, 'error' => null], 200);
        }
        catch(Exception $e)
        {
            return Response::json(['success'=> false, 'data' => null, 'error' => $e->getMessage()], 200);
        }
    }

    public function deleteWorker(Request $request)
    {
        try
        {
            $rules = [
                'id' => 'required|integer',
            ];
            $validator = Validator::make($request->input(), $rules);

            if ($validator->fails()) 
            {
                $error = $validator->errors()->messages();
                return Response::json(['success'=> false, 'data' => null, 'error' => array_shift($error)[0]], 200);
            }
            if($request->id ?? 0)
            {
                $deletedData = Worker::where('id',$request->id)->delete();
                if ($deletedData)
                    return Response::json(['success'=> true, 'data' => "Удаление завершено", 'error' => null], 200);
                else 
                    return Response::json(['success'=> false, 'data' => null, 'error' => "Ошибка удаления"], 200);
            }
            return Response::json(['success'=> false, 'data' => null, 'error' => "Код удаляемой записи не найден"], 200);
        }
        catch(Exception $e)
        {
            return Response::json(['success'=> false, 'data' => null, 'error' => $e->getMessage()], 200);
        }
    }
}

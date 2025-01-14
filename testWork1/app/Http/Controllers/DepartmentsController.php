<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Department_worker;
use App\Models\Worker;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class DepartmentsController extends Controller
{

    public function getDepartmentEdit(Request $request)
    {
        $department = Department::select("id","name","started_at")->where("id",$request->id)->first();
        return view('department-edit', compact(['department']));
    }


    public function getDepartments(Request $request)
    {
        try
        {
            $departments = Department::select("departments.id","departments.name","started_at",
                                                DB::Raw("(Select count(worker_id) from department_worker where department_id=departments.id) as countWorkers"));

            if($request->sort)
                $departments = $departments->orderBy("departments.name",$request->sort);
            
            if(isset($request->minValue) && isset($request->maxValue) && $request->maxValue>0)
            {
                $value = [$request->minValue,$request->maxValue];
                $departments = $departments->join("department_worker","department_id","departments.id")
                                ->join("workers","workers.id","worker_id")
                                ->whereBetween('workers.salary', $value)
                                ->groupBy("departments.id");
            }

            $departments = $departments->paginate(20);

            return Response::json(['success'=> true, 'data' => $departments, 'error' => null], 200);
        }
        catch(Exception $e)
        {
            return Response::json(['success'=> false, 'data' => null, 'error' => $e->getMessage()], 200);
        }
        //     return Response::json(['success'=> true, 'data' => null, 'error' => "Данные не найдены"], 200);
    }

    public function getWorkersForDepartment(Request $request)
    {
        try
        {
            $departmentId = $request->departmentId;
            $where = "";
            if($request->free)
                $where = " NOT ";

            $allWorkers = Worker::select("id","name","salary")
                                ->whereRaw(" workers.id $where IN (Select worker_id from department_worker Where department_id = $departmentId)")
                                ->when($request->name, function ($query, $name) {
                                    return $query->where('name', 'like', '%'.$name.'%');
                                })
                                ->paginate(20);

            return Response::json(['success'=> true, 'data' => $allWorkers, 'error' => null], 200);
        }
        catch(Exception $e)
        {
            return Response::json(['success'=> false, 'data' => null, 'error' => $e->getMessage()], 200);
        }
    }

    public function addWorkerToDepartment(Request $request)
    {
        try
        {
            $updated = Department_worker::create(["worker_id"=>$request->workerId,"department_id"=>$request->departmentId]);
            
            if($updated)
                return Response::json(['success'=> true, 'data' => $updated, 'error' => null], 200);
        }
        catch(Exception $e)
        {
            return Response::json(['success'=> false, 'data' => null, 'error' => $e->getMessage()], 200);
        }
    }

    public function setDepartment(Request $request)
    {
        try
        {
            $updated = Department::updateOrCreate(["id"=>$request->id],$request->input());
            
            if($updated)
                return Response::json(['success'=> true, 'data' => $updated, 'error' => null], 200);
        }
        catch(Exception $e)
        {
            return Response::json(['success'=> false, 'data' => null, 'error' => $e->getMessage()], 200);
        }
    }

    public function deleteDepartment(Request $request)
    {
        try
        {
            if($request->id ?? 0)
            {
                $deletedData = Department::where('id',$request->id)->delete();
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

    public function deleteFromDepartment(Request $request)
    {
        try
        {
            $deletedData = Department_worker::where('worker_id',$request->workerId)->where("department_id",$request->depId)->delete();

            if ($deletedData)
                return Response::json(['success'=> true, 'data' => "Удаление завершено", 'error' => null], 200);
            else 
                return Response::json(['success'=> false, 'data' => null, 'error' => "Ошибка удаления"], 200);
        }
        catch(Exception $e)
        {
            return Response::json(['success'=> false, 'data' => null, 'error' => $e->getMessage()], 200);
        }
    }
}



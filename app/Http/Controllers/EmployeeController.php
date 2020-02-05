<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmployeeResourceCollection;
use App\Models\Employee;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public function index()
    {
        return Employee::with(['departments', 'gender'])->get();
//        $employee = Employee::with(['departments', 'gender'])->get();
//        return new EmployeeResourceCollection($employee);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $employee = Employee::create($request->employee);
        $employee->departments()->attach($request->employee['departments']);
        $employee = $employee->load(['departments', 'gender']);
        return response()->json($employee, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Employee $employee
     * @return Employee
     */
    public function show(Employee $employee)
    {
        return $employee;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Employee $employee
     * @return Response
     */
    public function update(Request $request, Employee $employee)
    {
        $employee->update($request->employee);
        $employee->departments()->detach();
        $employee->departments()->attach($request->employee['departments']);
        $employee = $employee->load(['departments', 'gender']);
        return response()->json($employee, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Employee $employee
     * @return Response
     * @throws Exception
     */
    public function destroy(Employee $employee)
    {
        $employee->departments()->detach();
        $employee->delete();
        return response()->json(null, 204);
    }
}

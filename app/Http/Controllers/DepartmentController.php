<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Department[]|Collection
     */
    public function index()
    {
        return Department::with('employees')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $department = Department::create($request->department);
        return response()->json($department, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Department $department
     * @return Department
     */
    public function show(Department $department)
    {
        return $department;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Department $department
     * @return Response
     */
    public function update(Request $request, Department $department)
    {
        $department->update($request->department);
        return response()->json($department, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Department $department
     * @return Response
     * @throws Exception
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json(null, 204);
    }
}

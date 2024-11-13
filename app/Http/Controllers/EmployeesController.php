<?php

namespace App\Http\Controllers;
use App\Models\Employees;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function index()
    {
        return Employees::all();
    }

    public function store(Request $request)
    {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'gender' => 'required|in:M,F',
                'phone' => 'required|string',
                'address' => 'required|string',
                'email' => 'required|email|unique:employees,email',
                'status' => 'required|string',
                'hired_on' => 'required|date',
        ]);
    
        $employees = Employees::create($validatedData);
    
        return response()->json([
            'message' => 'Resource is added successfully',
            'data' => $employees
        ], 201);
    }

    public function show($id)
    {
        $employees = Employees::findOrFail($id);

        return response()->json([
            'message' => 'Get Detail Resource',
            'data' => $employees
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'nullable',
            'status' => 'nullable|in:active,inactive,terminated'
        ]);
    
        $employees = Employees::findOrFail($id);
        $employees->update($validatedData);
    
        return response()->json([
            'message' => 'Resource is updated successfully',
            'data' => $employees
        ], 200);
    }

    public function destroy($id)
    {
        $employees = Employees::findOrFail($id);
        $employees->delete();
    
        return response()->json([
            'message' => 'Resource is deleted successfully'
        ], 200);
    }

    public function search($name)
    {
        $employees = Employees::where('name', 'like', '%' . $name . '%')->get();

    if ($employees->isEmpty()) {
        return response()->json(['message' => 'Resource not found'], 404);
    }

    return response()->json([
        'message' => 'Get searched resource',
        'data' => $employees
    ], 200);
    }

    public function active()
    {
        $employees = Employees::where('status', 'active')->get();

    return response()->json([
        'message' => 'Get active resource',
        'total' => $employees->count(),
        'data' => $employees
    ], 200);
    }

    public function inactive()
    {
        $employees = Employees::where('status', 'inactive')->get();

    return response()->json([
        'message' => 'Get inactive resource',
        'total' => $employees->count(),
        'data' => $employees
    ], 200);
    }

    public function terminated()
    {
        $employees = Employees::where('status', 'terminated')->get();

        return response()->json([
            'message' => 'Get terminated resource',
            'total' => $employees->count(),
            'data' => $employees
        ], 200);
    }
}

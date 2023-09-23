<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Use the User model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    // Display a listing of employees
    public function index()
    {
        // Retrieve employees from the users table
        $employees = User::where('user_type', '!=', 'admin')->get();
        return view('employees.index', compact('employees'));
    }

    // Show the form for creating a new employee
    public function create()
    {
        return view('employees.create');
    }

    // Store a newly created employee in the database
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            // Update the unique rule to use the users table
            'address' => 'required|string|max:255',
            'dob' => 'required|date|before_or_equal:today',
            'image' => 'nullable|image|max:2048', // Max 2MB
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'required|digits:10',
            'password' => 'required|string|min:8',
            'user_type' => 'required|in:manager,developer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Upload and store the image if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        } else {
            $imagePath = null;
        }

        // Create a new user (employee) record
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'dob' => $request->input('dob'),
            'image' => $imagePath,
            'gender' => $request->input('gender'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'user_type' => $request->input('user_type'),
        ]);

        return redirect('/employees')->with('success', 'Employee added successfully.');
    }

    // Show the form for editing the specified employee
    public function edit($id)
    {
        $employee = User::findOrFail($id);
        return view('employees.edit', compact('employee'));
    }

    // Update the specified employee in the database
    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        // Validation rules for updating
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            // Update the unique rule to use the users table
            'address' => 'required|string|max:255',
            'dob' => 'required|date|before_or_equal:today',
            'image' => 'nullable|image|max:2048', // Max 2MB
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'required|digits:10',
            'password' => 'nullable|string|min:8', // Optional password change
            'user_type' => 'required|in:manager,developer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update user (employee) data
        $employee->name = $request->input('name');
        $employee->email = $request->input('email');
        $employee->address = $request->input('address');
        $employee->dob = $request->input('dob');
        $employee->gender = $request->input('gender');
        $employee->phone = $request->input('phone');
        $employee->user_type = $request->input('user_type');

        // Handle password update (if provided)
        if ($request->filled('password')) {
            $employee->password = Hash::make($request->input('password'));
        }

        $employee->save(); // Save the changes

        return redirect('/employees')->with('success', 'Employee updated successfully.');
    }
}

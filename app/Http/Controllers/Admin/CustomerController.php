<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Pelanggan::paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pelanggans',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Pelanggan::create($request->all());
        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully');
    }

    public function show(Pelanggan $customer)
    {
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Pelanggan $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Pelanggan $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pelanggans,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $customer->update($request->all());
        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully');
    }

    public function destroy(Pelanggan $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully');
    }
}

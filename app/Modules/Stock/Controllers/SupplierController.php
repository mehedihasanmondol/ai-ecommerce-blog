<?php

namespace App\Modules\Stock\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stock\Repositories\SupplierRepository;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierRepo;

    public function __construct(SupplierRepository $supplierRepo)
    {
        $this->supplierRepo = $supplierRepo;
    }

    public function index()
    {
        abort_if(!auth()->user()->hasPermission('suppliers.view'), 403, 'You do not have permission to view suppliers.');

        $suppliers = $this->supplierRepo->getAll(20);
        return view('admin.stock.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('suppliers.create'), 403, 'You do not have permission to create suppliers.');

        return view('admin.stock.suppliers.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('suppliers.create'), 403, 'You do not have permission to create suppliers.');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:suppliers,code',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'credit_limit' => 'nullable|numeric',
            'payment_terms' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $this->supplierRepo->create($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier created successfully');
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->hasPermission('suppliers.edit'), 403, 'You do not have permission to edit suppliers.');

        $supplier = $this->supplierRepo->find($id);
        return view('admin.stock.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        abort_if(!auth()->user()->hasPermission('suppliers.edit'), 403, 'You do not have permission to edit suppliers.');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:suppliers,code,' . $id,
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'credit_limit' => 'nullable|numeric',
            'payment_terms' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $this->supplierRepo->update($id, $validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier updated successfully');
    }

    public function destroy($id)
    {
        abort_if(!auth()->user()->hasPermission('suppliers.delete'), 403, 'You do not have permission to delete suppliers.');

        try {
            $this->supplierRepo->delete($id);
            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Supplier deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete supplier with existing records');
        }
    }
}

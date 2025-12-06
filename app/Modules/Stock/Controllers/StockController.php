<?php

namespace App\Modules\Stock\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stock\Services\StockService;
use App\Modules\Stock\Repositories\StockMovementRepository;
use App\Modules\Stock\Repositories\WarehouseRepository;
use App\Modules\Stock\Repositories\SupplierRepository;
use App\Modules\Stock\Repositories\StockAlertRepository;
use App\Modules\Ecommerce\Product\Repositories\ProductRepository;
use Illuminate\Http\Request;

class StockController extends Controller
{
    protected $stockService;
    protected $stockMovementRepo;
    protected $warehouseRepo;
    protected $supplierRepo;
    protected $alertRepo;
    protected $productRepo;

    public function __construct(
        StockService $stockService,
        StockMovementRepository $stockMovementRepo,
        WarehouseRepository $warehouseRepo,
        SupplierRepository $supplierRepo,
        StockAlertRepository $alertRepo,
        ProductRepository $productRepo
    ) {
        $this->stockService = $stockService;
        $this->stockMovementRepo = $stockMovementRepo;
        $this->warehouseRepo = $warehouseRepo;
        $this->supplierRepo = $supplierRepo;
        $this->alertRepo = $alertRepo;
        $this->productRepo = $productRepo;
    }

    /**
     * Dashboard
     */
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('stock.view'), 403, 'You do not have permission to view stock.');

        $recentMovements = $this->stockMovementRepo->getRecent(10);
        $pendingAlerts = $this->alertRepo->getPending();
        $warehouses = $this->warehouseRepo->getActive();

        return view('admin.stock.index', compact('recentMovements', 'pendingAlerts', 'warehouses'));
    }

    /**
     * Stock movements list
     */
    public function movements(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('stock.movements'), 403, 'You do not have permission to view stock movements.');

        $filters = $request->only(['type', 'warehouse_id', 'product_id', 'start_date', 'end_date', 'search']);
        $movements = $this->stockMovementRepo->getAll($filters, 20);
        $warehouses = $this->warehouseRepo->getActive();

        return view('admin.stock.movements.index', compact('movements', 'warehouses'));
    }

    /**
     * Show add stock form
     */
    public function createAddStock()
    {
        abort_if(!auth()->user()->hasPermission('stock.add'), 403, 'You do not have permission to add stock.');

        $warehouses = $this->warehouseRepo->getActive();
        $suppliers = $this->supplierRepo->getActive();
        $products = $this->productRepo->getAllActive();

        return view('admin.stock.add', compact('warehouses', 'suppliers', 'products'));
    }

    /**
     * Store add stock
     */
    public function storeAddStock(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('stock.add'), 403, 'You do not have permission to add stock.');

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'nullable|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $this->stockService->addStock($validated);
            return redirect()->route('admin.stock.movements')
                ->with('success', 'Stock added successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show remove stock form
     */
    public function createRemoveStock()
    {
        abort_if(!auth()->user()->hasPermission('stock.remove'), 403, 'You do not have permission to remove stock.');

        $warehouses = $this->warehouseRepo->getActive();
        $products = $this->productRepo->getAllActive();

        return view('admin.stock.remove', compact('warehouses', 'products'));
    }

    /**
     * Store remove stock
     */
    public function storeRemoveStock(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('stock.remove'), 403, 'You do not have permission to remove stock.');

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:out,damaged,lost',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $this->stockService->removeStock($validated);
            return redirect()->route('admin.stock.movements')
                ->with('success', 'Stock removed successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show adjust stock form
     */
    public function createAdjustStock()
    {
        abort_if(!auth()->user()->hasPermission('stock.adjust'), 403, 'You do not have permission to adjust stock.');

        $warehouses = $this->warehouseRepo->getActive();
        $products = $this->productRepo->getAllActive();

        return view('admin.stock.adjust', compact('warehouses', 'products'));
    }

    /**
     * Store stock adjustment
     */
    public function storeAdjustStock(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('stock.adjust'), 403, 'You do not have permission to adjust stock.');

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'new_quantity' => 'required|integer|min:0',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $this->stockService->adjustStock($validated);
            return redirect()->route('admin.stock.movements')
                ->with('success', 'Stock adjusted successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show transfer stock form
     */
    public function createTransfer()
    {
        abort_if(!auth()->user()->hasPermission('stock.transfer'), 403, 'You do not have permission to transfer stock.');

        $warehouses = $this->warehouseRepo->getActive();
        $products = $this->productRepo->getAllActive();

        return view('admin.stock.transfer', compact('warehouses', 'products'));
    }

    /**
     * Store stock transfer
     */
    public function storeTransfer(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('stock.transfer'), 403, 'You do not have permission to transfer stock.');

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            $this->stockService->transferStock($validated);
            return redirect()->route('admin.stock.movements')
                ->with('success', 'Stock transferred successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Stock alerts
     */
    public function alerts()
    {
        abort_if(!auth()->user()->hasPermission('stock.alerts'), 403, 'You do not have permission to view stock alerts.');

        $alerts = $this->alertRepo->getAll(20);

        return view('admin.stock.alerts.index', compact('alerts'));
    }

    /**
     * Resolve alert
     */
    public function resolveAlert($id)
    {
        abort_if(!auth()->user()->hasPermission('stock.alerts-resolve'), 403, 'You do not have permission to resolve stock alerts.');

        try {
            $this->alertRepo->markAsResolved($id);
            return back()->with('success', 'Alert resolved successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get current stock (AJAX)
     */
    public function getCurrentStock(Request $request)
    {
        // Check permission for AJAX request
        if (!auth()->user()->hasPermission('stock.view')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to view stock.',
            ], 403);
        }

        $stock = $this->stockService->getCurrentStock(
            $request->product_id,
            $request->variant_id,
            $request->warehouse_id
        );

        return response()->json(['stock' => $stock]);
    }
}

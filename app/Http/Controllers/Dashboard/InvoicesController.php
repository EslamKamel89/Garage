<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Enums\InvoiceStatus;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Labour;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoicesController extends Controller {
    public function index(Request $request) {
        $query = Invoice::with('client', 'user');

        if ($request->filter['search'] ?? null) {
            $search = $request->filter['search'];
            $query->whereHas('client', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhere('id', $search);
        }

        if ($request->filter['status'] ?? null) {
            $query->where('status', $request->filter['status']);
        }

        $invoices = $query->orderBy('created_at', 'desc')->get();

        return inertia('admin/Invoices/Index', [
            'invoices' => $invoices,
            'filters' => $request->filter ?? [],
        ]);
    }

    public function create() {
        return inertia('admin/Invoices/Create', [
            'clients' => Client::orderBy('name')->get(),
            'products' => Product::with('category')->orderBy('name')->get(),
            'labours' => Labour::where('is_active', true)->orderBy('name_ar')->get(),
        ]);
    }

    public function store(Request $request) {
        // dd($request->all());
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'labor_info.items' => 'array',
            'labor_info.items.*.name' => 'required|string',
            'labor_info.items.*.fee' => 'required|numeric|min:0',
            'actual_total' => 'required|numeric|min:0',
            'actual_paid_amount' => 'required|numeric|min:0',
            'status' => 'required|in:unpaid,paid,partial,cancelled',
            'notes' => 'nullable|string',
        ]);
        DB::transaction(function () use ($request) {
            $invoice = Invoice::create([
                'client_id' => $request->client_id,
                'user_id' => auth()->id(),
                'status' => $request->status,
                'calculated_total' => collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_price']) +
                    collect($request->labor_info['items'] ?? [])->sum('fee'),
                'actual_total' => $request->actual_total,
                'actual_paid_amount' => $request->actual_paid_amount,
                'labor_info' => $request->labor_info,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("الكمية غير كافية للمنتج: {$product->name}");
                }

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);

                $product->decrement('quantity', $item['quantity']);
            }
        });

        return redirect()->route('invoices.index')->with('success', 'تم إنشاء الفاتورة بنجاح.');
    }

    public function show(Invoice $invoice) {
        $invoice = $invoice->load(['client', 'items.product.category', 'user']);
        return response()->json($invoice);
    }

    public function edit(Invoice $invoice) {
        $invoice->load('client', 'items.product.category');

        return inertia('admin/Invoices/Edit', [
            'invoice' => $invoice,
            'clients' => Client::orderBy('name')->get(),
            'products' => Product::with('category')->orderBy('name')->get(),
            'labours' => Labour::where('is_active', true)->orderBy('name_ar')->get(),
        ]);
    }

    public function update(Request $request, Invoice $invoice) {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'labor_info.items' => 'array',
            'labor_info.items.*.name' => 'required|string',
            'labor_info.items.*.fee' => 'required|numeric|min:0',
            'actual_total' => 'required|numeric|min:0',
            'actual_paid_amount' => 'required|numeric|min:0',
            'status' => 'required|in:unpaid,paid,partial,cancelled',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $invoice) {
            // Restore original stock before applying new changes
            foreach ($invoice->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('quantity', $item->quantity);
                }
            }

            // Delete old items
            $invoice->items()->delete();

            // Update invoice
            $invoice->update([
                'client_id' => $request->client_id,
                'status' => $request->status,
                'calculated_total' => collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_price']) +
                    collect($request->labor_info['items'] ?? [])->sum('fee'),
                'actual_total' => $request->actual_total,
                'actual_paid_amount' => $request->actual_paid_amount,
                'labor_info' => $request->labor_info,
                'notes' => $request->notes,
            ]);

            // Add new items and deduct stock
            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("الكمية غير كافية للمنتج: {$product->name}");
                }

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);

                $product->decrement('quantity', $item['quantity']);
            }
        });

        return redirect()->route('invoices.index')->with('success', 'تم تحديث الفاتورة بنجاح.');
    }

    public function destroy(Invoice $invoice) {
        DB::transaction(function () use ($invoice) {
            // Restore stock
            foreach ($invoice->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('quantity', $item->quantity);
                }
            }

            // Delete invoice and items
            $invoice->items()->delete();
            $invoice->delete();
        });

        return back()->with('success', 'تم حذف الفاتورة بنجاح.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketCategory;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{
    public function index()
    {
        $categories = TicketCategory::latest()->paginate(10);

        return view('admin.ticket-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.ticket-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:ticket_categories,name',
            'status' => 'required',
        ]);

        TicketCategory::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('ticket-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(TicketCategory $ticketCategory)
    {
        return view(
            'admin.ticket-categories.edit',
            compact('ticketCategory')
        );
    }

    public function update(Request $request, TicketCategory $ticketCategory)
    {
        $request->validate([
            'name' => 'required|unique:ticket_categories,name,'.$ticketCategory->id,
            'status' => 'required',
        ]);

        $ticketCategory->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('ticket-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(TicketCategory $ticketCategory)
    {
        $ticketCategory->delete();

        return redirect()
            ->route('ticket-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}

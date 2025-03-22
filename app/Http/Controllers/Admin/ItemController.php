<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the items.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $items = Item::with('category')->get();
        return view('admin.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new item.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $categories = Category::all();
        return view('admin.items.create', compact('categories'));
    }

    /**
     * Store newly created items in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string'],
        ]);

        $categoryId = $request->category_id;
        $itemsData = $request->items;
        
        foreach ($itemsData as $itemData) {
            if (!empty($itemData['name'])) {
                Item::create([
                    'name' => $itemData['name'],
                    'description' => $itemData['description'] ?? null,
                    'category_id' => $categoryId,
                ]);
            }
        }

        return redirect()->route('admin.items.index')->with('success', 'Items created successfully.');
    }

    /**
     * Display the specified item.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Item $item)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        return view('admin.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified item.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Item $item)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $categories = Category::all();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Item $item)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $item->update($request->all());

        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified item from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Item $item)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $item->delete();

        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }
}
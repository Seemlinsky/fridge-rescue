<?php

namespace App\Http\Controllers;

use App\Models\FridgeItem;
use Illuminate\Http\Request;

class FridgeItemController extends Controller
{
    public function index()
    {
        $items = FridgeItem::orderBy('expires_at')->get();

        return view('fridge', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'nullable|string|max:100',
            'expires_at' => 'nullable|date',
        ]);

        FridgeItem::create($validated);

        return redirect('/');
    }
}

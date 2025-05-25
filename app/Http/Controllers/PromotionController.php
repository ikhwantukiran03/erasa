<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::where('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('promotions.index', compact('promotions'));
    }

    public function show(Promotion $promotion)
    {
        return view('promotions.show', compact('promotion'));
    }
}

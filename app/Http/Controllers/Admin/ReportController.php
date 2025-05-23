<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Get monthly revenue data for the chart
        $monthlyRevenue = Invoice::select(
            DB::raw('EXTRACT(MONTH FROM created_at) as month'),
            DB::raw('EXTRACT(YEAR FROM created_at) as year'),
            DB::raw('SUM(amount) as total')
        )
        ->where('status', 'verified')
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get()
        ->map(function ($item) {
            return [
                'month' => Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'),
                'total' => $item->total
            ];
        });

        // Get monthly financial summary
        $monthlySummary = Invoice::select(
            DB::raw('EXTRACT(MONTH FROM created_at) as month'),
            DB::raw('EXTRACT(YEAR FROM created_at) as year'),
            DB::raw('COUNT(*) as total_invoices'),
            DB::raw('SUM(amount) as total_revenue'),
            DB::raw('AVG(amount) as average_amount')
        )
        ->where('status', 'verified')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->limit(12)
        ->get()
        ->map(function ($item) {
            return [
                'month' => Carbon::createFromDate($item->year, $item->month, 1)->format('F Y'),
                'total_invoices' => $item->total_invoices,
                'total_revenue' => $item->total_revenue,
                'average_amount' => $item->average_amount
            ];
        });

        // Get yearly financial summary
        $yearlySummary = Invoice::select(
            DB::raw('EXTRACT(YEAR FROM created_at) as year'),
            DB::raw('COUNT(*) as total_invoices'),
            DB::raw('SUM(amount) as total_revenue'),
            DB::raw('AVG(amount) as average_amount')
        )
        ->where('status', 'verified')
        ->groupBy('year')
        ->orderBy('year', 'desc')
        ->get();

        // Get top 5 most ordered events
        $topEvents = Booking::select(
            'package_id',
            DB::raw('COUNT(*) as booking_count'),
            'packages.name as package_name'
        )
        ->join('packages', 'bookings.package_id', '=', 'packages.id')
        ->groupBy('package_id', 'packages.name')
        ->orderBy('booking_count', 'desc')
        ->limit(5)
        ->get();

        // Get monthly bookings data for the chart
        $monthlyBookings = Booking::select(
            DB::raw('EXTRACT(MONTH FROM created_at) as month'),
            DB::raw('EXTRACT(YEAR FROM created_at) as year'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get()
        ->map(function ($item) {
            return [
                'month' => Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'),
                'total' => $item->total
            ];
        });

        return view('admin.reports.index', compact(
            'monthlyRevenue',
            'monthlySummary',
            'yearlySummary',
            'topEvents',
            'monthlyBookings'
        ));
    }
} 
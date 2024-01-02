<?php

namespace App\Http\Controllers;

use App\Exports\OffersWithFullCategoriesHierarchyExport;
use App\Models\Offer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class OffersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $offersPaginated = Offer::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Offers', [
            'offers' => $offersPaginated,
            'filters' => $request->only('search')
        ]);
    }

    public function downloadExcel()
    {
        return Excel::download(new OffersWithFullCategoriesHierarchyExport(), 'offers.xlsx',  \Maatwebsite\Excel\Excel::XLSX);
    }
}

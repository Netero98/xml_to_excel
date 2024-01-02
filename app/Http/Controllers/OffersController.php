<?php

namespace App\Http\Controllers;

use App\Exceptions\DbTransactionRolledBackException;
use App\Exports\OffersWithFullCategoriesHierarchyExport;
use App\Models\Offer;
use App\Providers\RouteServiceProvider;
use App\Services\RefreshOffersDataService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class OffersController extends Controller
{
    private const ERRORS_KEY_REFRESH = 'refresh';

    private const SESSION_KEY_ERRORS = 'errors';

    public function index(Request $request)
    {
        $offersPaginated = Offer::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        $errors = $request->session()->get(self::SESSION_KEY_ERRORS, []);
        $request->session()->remove(self::SESSION_KEY_ERRORS);

        return Inertia::render('Offers', [
            'offers' => $offersPaginated,
            'filters' => $request->only('search'),
            'errors' => $errors
        ]);
    }

    public function downloadExcel()
    {
        return Excel::download(new OffersWithFullCategoriesHierarchyExport(), 'offers.xlsx',  \Maatwebsite\Excel\Excel::XLSX);
    }

    public function flush( RefreshOffersDataService $service)
    {
        $service->flushOffers();

        return to_route(RouteServiceProvider::ROUTE_OFFERS_INDEX);
    }

    public function refresh(Request $request, RefreshOffersDataService $service)
    {
        try {
            $service->refreshOffersFromDefaultUrl();
        } catch (DbTransactionRolledBackException $e) {
            $request->session()->put(self::SESSION_KEY_ERRORS, [
                self::ERRORS_KEY_REFRESH => $e->getMessage()
            ]);
        }

        return to_route(RouteServiceProvider::ROUTE_OFFERS_INDEX);
    }
}

<?php

namespace App\Exports;

use App\Http\Resources\OfferResource;
use App\Models\Offer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OffersWithFullCategoriesHierarchyExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        return new Collection(OfferResource::collection(Offer::all()));
    }

    public function headings(): array
    {
        return OfferResource::getResourceKeys();
    }
}

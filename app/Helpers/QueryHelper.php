<?php

namespace App\Helpers;

class QueryHelper
{

    public static function approvedListingOrder($query)
    {
        // Only show products which have a stock bigger than zero
        $query = $query->where('stock', '>=', 1);

        $query = $query->orderBy('scrap_priority', 'DESC');

        // Prioritize suppliers
        $prioritizeSuppliers = "CASE WHEN brand IN (4,13,15,18,20,21,24,25,27,30,32,144,145) AND category IN (11,39,5,41,14,42,60,17,31,63) AND supplier IN ('G & B Negozionline', 'Tory Burch', 'Wise Boutique', 'Biffi Boutique (S.P.A.)', 'MARIA STORE', 'Lino Ricci Lei', 'Al Duca d\'Aosta', 'Tiziana Fausti', 'Leam') THEN 0 ELSE 1 END";
        $query = $query->orderByRaw($prioritizeSuppliers);

        // Show on sale products first
        $query = $query->orderBy('is_on_sale', 'DESC');

        // Show latest approvals first
        $query = $query->orderBy('listing_approved_at', 'DESC');

        // Return query
        return $query;
    }

    public static function approvedListingOrderFinalApproval($query)
    {
        // Only show products which have a stock bigger than zero
        $query = $query->leftJoin('brands', function ($join) {
            $join->on('products.brand', '=', 'brands.id');
        });

        $query = $query->leftJoin('suppliers', function ($join) {
            $join->on('products.supplier_id', '=', 'suppliers.id');
        });

        $query = $query->where('stock', '>=', 1);

        $query = $query->orderBy('brands.priority', 'desc')->orderBy('suppliers.priority', 'desc')->latest("products.created_at");

        // Return query
        return $query;
    }
}
<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class KeywordToCategory extends Model
{
    public function orderStatus() {
        if ($this->category_type != 'order') {
            return null;
        }

        return [
            'Follow up for advance' => 'Follow up for advance',
            'Proceed without Advance' => 'Proceed without Advance',
            'Advance received' => 'Advance received',
            'Cancel' => 'Cancel',
            'Prepaid' => 'Prepaid',
            'Product Shiped form Italy' => 'Product Shiped form Italy',
            'In Transist from Italy' => 'In Transist from Italy',
            'Product shiped to Client' => 'Product shiped to Client',
            'Delivered' => 'Delivered',
            'Refund to be processed' => 'Refund to be processed',
            'Refund Dispatched' => 'Refund Dispatched',
            'Refund Credited' => 'Refund Credited',
            'VIP' => 'VIP',
            'HIGH PRIORITY' => 'HIGH PRIORITY'
        ][$this->model_id] ?? null;

    }

    public function leadStatus() {
        if ($this->category_type != 'lead') {
            return null;
        }

        return [
            '1' => 'Cold',
            '2' => 'Cold Important',
            '3' => 'Hot',
            '4' => 'Very Hot',
            '5' => 'Advance Follow Up',
            '6' => 'High Priority'
        ][$this->model_id] ?? 'N/A';
    }

    public function category() {
        if ($this->category_type != 'category') {
            return null;
        }

        return $this->belongsTo(CustomerCategory::class, 'model_id', 'id');

    }
}

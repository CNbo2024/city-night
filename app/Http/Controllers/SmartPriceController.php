<?php

namespace App\Http\Controllers;

use App\Models\Properties;
use App\Models\PropertyAddress;

class SmartPriceController extends Controller
{
	public function get()
	{
		$address = PropertyAddress::where('property_id', request()->property_id)->first();

		$properties = Properties::where('id', '!=', request()->property_id)->get();

		$result = [];

		foreach ($properties as $property) {
			if ($property->property_price->price >= request()->min && $property->property_price->price <= request()->max) {
				$theta = $address->longitude - $property->property_address->longitude;

			    $distance = (sin(deg2rad($address->latitude)) * sin(deg2rad($property->property_address->latitude))) + (cos(deg2rad($address->latitude)) * cos(deg2rad($property->property_address->latitude)) * cos(deg2rad($theta))); 
			    $distance = acos($distance); 
			    $distance = rad2deg($distance);
			    $distance = $distance * 60 * 1.1515;
			    $distance = $distance * 1.609344;

			    if ($distance <= 1) {
				    $result[] = [
				    	'property_id' => $property->id,
				    	'distance' => $distance,
				    	'price' => $property->property_price->price,
				    ];
			    }				
			}
		}

		$avg = array_sum(array_column($result, 'price')) / count($result);

		return number_format($avg, 2);
	}
}

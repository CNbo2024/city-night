<?php

/**
 * Amenities Model
 *
 * Amenities Model manages Amenities operation.
 *
 * @category   Language
 * @package    migrateshop
 * @author     Migrateshop
 * @copyright  2020 migrateshop.com
 * @license
 * @version    4.0
 * @link       http://migrateshop.com
 * @since      Version 1.3
 * @deprecated None
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class Amenities extends Model
{
    protected $table    = 'amenities';
    public $timestamps  = false;

    public function amenity_type()
    {
        return $this->belongsTo('App\Models\AmenityType', 'type_id', 'id');
    }

    
    public static function normal($property_id)
    {
		$current_lang = Session::get('language');
        $result = DB::select("select amenities.title as title, amenities.id as id, amenities.symbol, properties.id as status from amenities left join properties on find_in_set(amenities.id, properties.amenities) and properties.id = $property_id where lang='".$current_lang."' ");
        return $result;
    }

    public static function security($property_id)
    {
		$current_lang = Session::get('language');
        $result = DB::select("select amenities.title as title, amenities.id as id, amenities.symbol, properties.id as status from amenities left join properties on find_in_set(amenities.id, properties.amenities) and properties.id = $property_id where lang='".$current_lang."' and type_id=2 ");
        return $result;
    }
}

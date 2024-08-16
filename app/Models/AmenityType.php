<?php

/**
 * AmenityType Model
 *
 * AmenityType Model manages AmenityType operation.
 *
 * @category   AmenityType
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

class AmenityType extends Model
{
    protected $table    = 'amenity_type';
    public $timestamps  = false;

    public function amenities()
    {
        return $this->hasMany('App\Models\Amenities', 'type_id', 'id');
    }
}

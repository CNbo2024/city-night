<?php

/**
 * PropertyDetails Model
 *
 * PropertyDetails Model manages PropertyDetails operation.
 *
 * @category   PropertyDetails
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

class PropertyDetails extends Model
{
    protected $table    = 'property_details';
    public $timestamps  = false;
    protected $fillable = ['property_id', 'field', 'value'];

    public function properties()
    {
        return $this->belongsTo('App\Models\Properties', 'property_id', 'id');
    }
}

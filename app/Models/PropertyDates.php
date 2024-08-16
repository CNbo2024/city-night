<?php

/**
 * PropertyDates Model
 *
 * PropertyDates Model manages PropertyDates operation.
 *
 * @category   PropertyDates
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

class PropertyDates extends Model
{
    protected $table    = 'property_dates';
    protected $fillable = ['property_id', 'status', 'date', 'min_day', 'min_stay','max_day', 'max_stay', 'price','color','type'];

    public function properties()
    {
        return $this->belongsTo('App\Models\Properties', 'property_id', 'id');
    }
}

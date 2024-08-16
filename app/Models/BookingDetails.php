<?php

/**
 * BookingDetails Model
 *
 * BookingDetails Model manages BookingDetails operation.
 *
 * @category   BookingDetails
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

class BookingDetails extends Model
{
    protected $table    = 'booking_details';
    public $timestamps  = false;

    public function bookings()
    {
        return $this->belongsTo('App\Models\Bookings', 'booking_id', 'id');
    }
}

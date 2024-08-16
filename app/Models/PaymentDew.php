<?php

/**
 * PaymentDew Model
 *
 * PaymentDew Model manages PaymentDew operation.
 *
 * @category   PaymentDew
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

class PaymentDew extends Model
{
    protected $table = 'payouts';

    public function bookings()
    {
        return $this->belongsTo('App\Models\Bookings', 'booking_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function payout_penalties()
    {
        return $this->hasMany('App\Models\PayoutPenalties', 'payout_id', 'id');
    }
}

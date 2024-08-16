<?php

/**
 * PayoutPenalties Model
 *
 * PayoutPenalties Model manages PayoutPenalties operation.
 *
 * @category   PayoutPenalties
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

class PayoutPenalties extends Model
{
    protected $table   = 'payout_penalties';
    public $timestamps = false;

    public function payouts()
    {
        return $this->belongsTo('App\Models\Payouts', 'payout_id', 'id');
    }

    public function penalty()
    {
        return $this->belongsTo('App\Models\Penalty', 'penalty_id', 'id');
    }
}

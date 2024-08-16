<?php

/**
 * PropertyFees Model
 *
 * PropertyFees Model manages PropertyFees operation.
 *
 * @category   PropertyFees
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

class PropertyFees extends Model
{
    protected $table   = 'property_fees';
    public $timestamps = false;
}

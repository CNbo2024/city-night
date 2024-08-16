<?php

/**
 * PropertySteps Model
 *
 * PropertySteps Model manages PropertySteps operation.
 *
 * @category   PropertySteps
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

class PropertySteps extends Model
{
    protected $table   = 'property_steps';
    public $timestamps = false;

    public function property()
    {
        return $this->belongsTo('App\Models\Properties', 'property_id', 'id');
    }
}

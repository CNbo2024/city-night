<?php

/**
 * PropertyType Model
 *
 * PropertyType Model manages PropertyType operation.
 *
 * @category   PropertyType
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

class PropertyType extends Model
{
    protected $table   = 'property_type';
    public $timestamps = false;

    public function properties()
    {
        return $this->hasMany('App\Models\Properties', 'property_type', 'id');
    }
}

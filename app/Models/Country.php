<?php

/**
 * Country Model
 *
 * Country Model manages Country operation.
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
use Cache;


class Country extends Model
{
    protected $table   = 'country';
    public $timestamps = false;

    public static function getAll()
    {
        $data = Cache::get(config('cache.prefix') . '.countries');
        if (empty($data)) {
            $data = parent::all();
            Cache::forever(config('cache.prefix') . '.countries', $data);
        }
        return $data;
    }

    public function property_address()
    {
        return $this->hasMany('App\Models\PropertyAddress', 'country', 'short_name');
    }
}

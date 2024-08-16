<?php

/**
 * Settings Model
 *
 * Settings Model manages Settings operation.
 *
 * @category   Settings
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

class Settings extends Model
{
    protected $table    = 'settings';
    public $timestamps  = false;

    protected $fillable = ['name', 'value'];

    public static function getAll()
    {
        $data = Cache::get('settings');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('settings', $data, 1440);
        }
        return $data;
    }
}

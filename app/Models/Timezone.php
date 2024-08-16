<?php

/**
 * Timezpne Model
 *
 * Timezpne Model manages Timezpne operation.
 *
 * @category   Timezpne
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

class Timezone extends Model
{
    protected $table   = 'timezone';
    public $timestamps = false;
}

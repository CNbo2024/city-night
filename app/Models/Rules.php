<?php

/**
 * Rules Model
 *
 * Rules Model manages Rules operation.
 *
 * @category   Rules
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

class Rules extends Model
{
    protected $table   = 'rules';
    public $timestamps = false;
}

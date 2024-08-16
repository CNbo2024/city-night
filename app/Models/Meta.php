<?php

/**
 * Metas Model
 *
 * Metas Model manages Metas operation.
 *
 * @category   Metas
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

class Meta extends Model
{
    protected $table   = 'seo_metas';
    public $timestamps = false;
}

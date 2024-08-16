<?php

/**
 * SeoMetas Model
 *
 * SeoMetas Model manages SeoMetas operation.
 *
 * @category   SeoMetas
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

class SeoMetas extends Model
{
    protected $table   = 'seo_metas';
    public $timestamps = false;
}

<?php
/**
 * PropertyPhotos Model
 *
 * PropertyPhotos Model manages PropertyPhotos operation.
 *
 * @category   PropertyPhotos
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

class PropertyPhotos extends Model
{
    protected $table   = 'property_photos';
    public $timestamps = false;

    public function properties()
    {
        return $this->belongsTo('App\Models\Properties', 'property_id', 'id');
    }
}

<?php

/**
 * Properties Model
 *
 * Properties Model manages Properties operation. 
 *
 * @category   Properties
 * @package    Buy2rental
 * @author     Migrateshop
 * @copyright  2018 Migrateshop
 * @license    
 * @version    1.3
 * @link       http://migrateshop.com
 * @since      Version 1.3
 * @deprecated None
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PropertyPhotos;
use Illuminate\Database\Eloquent\SoftDeletes;

/* class Wishlist extends Model
{
	protected $table = 'wishlist';

} */

class Wishlist extends Model
{
    protected $table   = 'wishlist';
    public $timestamps = false;

    public function properties()
    {
        return $this->belongsTo('App\Models\Properties', 'propertyid', 'id');
    }
}

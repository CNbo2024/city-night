<?php

/**
 * RoleAdmin Model
 *
 * RoleAdmin Model manages RoleAdmin operation.
 *
 * @category   RoleAdmin
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

class RoleAdmin extends Model
{
    protected $table     = 'role_admin';
    protected $fillable  = ['role_id', 'admin_id'];
    public $timestamps   = false;
}

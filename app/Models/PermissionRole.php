<?php

/**
 * PermissionRole Model
 *
 * PermissionRole Model manages PermissionRole operation.
 *
 * @category   PermissionRole
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

class PermissionRole extends Model
{
    protected $fillable = ['permission_id', 'role_id'];
    protected $table    = 'permission_role';
    public $timestamps  = false;
}

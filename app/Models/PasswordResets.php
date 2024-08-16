<?php

/**
 * PasswordResets Model
 *
 * PasswordResets Model manages PasswordResets operation.
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

class PasswordResets extends Model
{
    protected $table   = 'password_resets';

    public $timestamps = false;
}

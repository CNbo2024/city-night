<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Card extends Model
{
    protected $table   = 'cards';

    protected $fillable = [
        'user_id',
        'type',
        'number',
        'cvc',
        'expiry_date'
    ];

    public function getNameAttribute()
    {
        $string = $this->type == '001' ? 'Visa' : 'Mastercard';
        return $string . ' - XXXXXXXXXXXX' . substr($this->number, -4);
    }
}

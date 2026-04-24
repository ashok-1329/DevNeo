<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'client_name',
        'client_abn',
        'client_phone',
        'client_representative',
        'client_rep_email',
        'client_account_email',
        'client_terms',
        'client_address',
        'internal_note',
        'client_logo',
        'status',
        'created_by',
        'updated_by',
    ];
}

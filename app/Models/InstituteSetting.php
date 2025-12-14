<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstituteSetting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'website',
        'logo_path',
        'principal_name',
        'principal_signature_path',
    ];

    // Singleton pattern - only one institute setting
    public static function current()
    {
        return static::first() ?? static::create([
            'name' => 'School Name',
            'code' => 'SCH001',
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Category extends Model
{
    protected static ?bool $supportsOwnership = null;

    protected $fillable = [
        'name',
        'parent_id',
        'status',
        'user_id',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function supportsOwnership(): bool
    {
        if (static::$supportsOwnership === null) {
            static::$supportsOwnership = Schema::hasColumn('categories', 'user_id');
        }

        return static::$supportsOwnership;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Customer extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone_number',
        'email'
    ];

//    public static function boot() {
//        parent::boot();
//
//        static::deleting(function($customer) {
//            Ledger::whereCustomerId($customer->id)->delete();
//            Balance::whereCustomerId($customer->id)->delete();
//        });
//    }


    public function customerLedger(): HasMany
    {
        return $this->hasMany(Ledger::class,'customer_id','id');
    }

    public function customerBalance(): HasOne
    {
        return $this->hasOne(Balance::class);
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
        ];
    }
}

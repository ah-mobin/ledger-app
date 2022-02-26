<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ledger extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'type',
        'amount',
        'balance',
        'date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($ledger) {
            if($ledger->type != "Ledger Open"){
                $instance = Ledger::find($ledger->id);
                $balance = Ledger::whereCustomerId($instance->customer_id)->orderBy('id','desc')->get()[1]->balance;
                
                if($ledger->type == 'Due Added'){
                    $instance->balance = $balance + $instance->amount;
                }
                
                if($ledger->type == 'Due Deducted'){
                    $instance->balance = $balance - $instance->amount;
                }

                $instance->save();
            }             
        });
    }


    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

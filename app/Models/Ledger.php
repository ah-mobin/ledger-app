<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Ledger extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'payment_type_id',
        'amount',
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
            try{
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
            }catch(\Exception | \Throwable $e){
                Log::critical($e->getMessage());
            }
        });
    }


    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function balance(): BelongsTo
    {
        return $this->belongsTo(Balance::class,'customer_id','customer_id');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }
}

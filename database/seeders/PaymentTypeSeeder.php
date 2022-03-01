<?php

namespace Database\Seeders;

use App\Constants\PaymentTypeConstants;
use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            PaymentTypeConstants::LEDGER_OPEN_TEXT,
            PaymentTypeConstants::DUE_ADD_TEXT,
            PaymentTypeConstants::PAYMENT_FROM_CUSTOMER_TEXT,
            PaymentTypeConstants::BONUS_ADD_TEXT,
            PaymentTypeConstants::PAYMENT_BY_BONUS_TEXT,
        ];

        foreach ($types as $type){
            PaymentType::create([
                'type' => $type
            ]);
        }
    }
}

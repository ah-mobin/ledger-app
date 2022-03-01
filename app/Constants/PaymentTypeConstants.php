<?php

namespace App\Constants;

class PaymentTypeConstants
{
    const LEDGER_OPEN = 1;
    const DUE_ADD = 2;
    const PAYMENT_FROM_CUSTOMER = 3;
    const BONUS_ADD = 4;
    const PAYMENT_BY_BONUS = 5;

    const LEDGER_OPEN_TEXT = 'Ledger Open';
    const DUE_ADD_TEXT = 'Due Add';
    const PAYMENT_FROM_CUSTOMER_TEXT = 'Payment From Customer';
    const BONUS_ADD_TEXT = 'Bonus Add';
    const PAYMENT_BY_BONUS_TEXT = 'Payment by Bonus';
}

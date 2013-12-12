<?php

$sLangName = "English";
$aLang = array(
    'charset' => 'UTF-8',
    'PAGE_CHECKOUT_PAYMENT_NUMBER' => 'Credit Card',
    'PAGE_CHECKOUT_PAYMENT_SECURITYCODE' => 'CVC',
    'PAGE_CHECKOUT_PAYMENT_HOLDERNAME' => 'Cardholder',
    'PAGE_CHECKOUT_PAYMENT_VALIDUNTIL' => 'Expiry Date',
    'PAGE_CHECKOUT_PAYMENT_ACCOUNTHOLDER' => 'Accountholder',
    'PAGE_CHECKOUT_PAYMENT_ACCOUNT' => 'Accountnumber',
    'PAGE_CHECKOUT_PAYMENT_BANKCODE' => 'Bankcode',
    'PAGE_CHECKOUT_PAYMENT_IBAN' => 'Iban',
    'PAGE_CHECKOUT_PAYMENT_BIC' => 'Bic',
    'PAGE_CHECKOUT_PAYMENT_MONTH_JAN' => 'January',
    'PAGE_CHECKOUT_PAYMENT_MONTH_FEB' => 'February',
    'PAGE_CHECKOUT_PAYMENT_MONTH_MAR' => 'March',
    'PAGE_CHECKOUT_PAYMENT_MONTH_APR' => 'April',
    'PAGE_CHECKOUT_PAYMENT_MONTH_MAY' => 'May',
    'PAGE_CHECKOUT_PAYMENT_MONTH_JUN' => 'June',
    'PAGE_CHECKOUT_PAYMENT_MONTH_JUL' => 'July',
    'PAGE_CHECKOUT_PAYMENT_MONTH_AUG' => 'August',
    'PAGE_CHECKOUT_PAYMENT_MONTH_OCT' => 'October',
    'PAGE_CHECKOUT_PAYMENT_MONTH_SEP' => 'September',
    'PAGE_CHECKOUT_PAYMENT_MONTH_NOV' => 'November',
    'PAGE_CHECKOUT_PAYMENT_MONTH_DEC' => 'December',
    'PAYMILL_VALIDATION_CARDNUMBER' => 'Please enter a valid credit card number.',
    'PAYMILL_VALIDATION_ACCOUNTNUMBER' => 'Please enter a valid account number.',
    'PAYMILL_VALIDATION_BANKCODE' => 'Please enter a valid bankcode.',
    'PAYMILL_VALIDATION_IBAN' => 'Please enter a valid iban.',
    'PAYMILL_VALIDATION_BIC' => 'Please enter a valid bic.',
    'PAYMILL_VALIDATION_EXP' => 'Please enter a valid expiry date.',
    'PAYMILL_VALIDATION_CVC' => 'Please enter a valid cvc.',
    'PAYMILL_VALIDATION_CARDHOLDER' => 'Please enter a valid cardholder.',
    'PAYMILL_VALIDATION_ACCOUNTHOLDER' => 'Please enter a valid account holder.',
    'PAYMILL_CC_POWERED_TEXT' => 'Save Creditcard Payment powered by',
    'PAYMILL_ELV_POWERED_TEXT' => 'Direct debit powered by',
    'PAGE_CHECKOUT_PAYMENT_CC_TOOLTIP' => 'What is a CVV/CVC number? Prospective credit cards will have a 3 to 4-digit number, usually on the back of the card. It ascertains that the payment is carried out by the credit card holder and the card account is legitimate. On Visa the CVV (Card Verification Value) appears after and to the right of your card number. Same goes for Mastercard’s CVC (Card Verfication Code), which also appears after and to the right of  your card number, and has 3-digits. Diners Club, Discover, and JCB credit and debit cards have a three-digit card security code which also appears after and to the right of your card number. The American Express CID (Card Identification Number) is a 4-digit number printed on the front of your card. It appears above and to the right of your card number. On Maestro the CVV appears after and to the right of your number. If you don’t have a CVV for your Maestro card you can use 000.',
    'PAYMILL_10001' => 'General undefined response.',
    'PAYMILL_10002' => 'Still waiting on something.',
    'PAYMILL_20000' => 'General success response.',
    'PAYMILL_40000' => 'General problem with data.',
    'PAYMILL_40001' => 'General problem with payment data.',
    'PAYMILL_40100' => 'Problem with credit card data.',
    'PAYMILL_40101' => 'Problem with cvv.',
    'PAYMILL_40102' => 'Card expired or not yet valid.',
    'PAYMILL_40103' => 'Limit exceeded.',
    'PAYMILL_40104' => 'Card invalid.',
    'PAYMILL_40105' => 'Expiry date not valid.',
    'PAYMILL_40106' => 'Credit card brand required.',
    'PAYMILL_40200' => 'Problem with bank account data.',
    'PAYMILL_40201' => 'Bank account data combination mismatch.',
    'PAYMILL_40202' => 'User authentication failed.',
    'PAYMILL_40300' => 'Problem with 3d secure data.',
    'PAYMILL_40301' => 'Currency / amount mismatch',
    'PAYMILL_40400' => 'Problem with input data.',
    'PAYMILL_40401' => 'Amount too low or zero.',
    'PAYMILL_40402' => 'Usage field too long.',
    'PAYMILL_40403' => 'Currency not allowed.',
    'PAYMILL_50000' => 'General problem with backend.',
    'PAYMILL_50001' => 'Country blacklisted.',
    'PAYMILL_50100' => 'Technical error with credit card.',
    'PAYMILL_50101' => 'Error limit exceeded.',
    'PAYMILL_50102' => 'Card declined by authorization system.',
    'PAYMILL_50103' => 'Manipulation or stolen card.',
    'PAYMILL_50104' => 'Card restricted.',
    'PAYMILL_50105' => 'Invalid card configuration data.',
    'PAYMILL_50200' => 'Technical error with bank account.',
    'PAYMILL_50201' => 'Card blacklisted.',
    'PAYMILL_50300' => 'Technical error with 3D secure.',
    'PAYMILL_50400' => 'Decline because of risk issues.',
    'PAYMILL_50500' => 'General timeout.',
    'PAYMILL_50501' => 'Timeout on side of the acquirer.',
    'PAYMILL_50502' => 'Risk management transaction timeout.',
    'PAYMILL_50600' => 'Duplicate transaction.',
    'PAYMILL_internal_server_error' => 'Communication with PSP failed',
    'PAYMILL_invalid_public_key' => 'Public Key is invalid',
    'PAYMILL_invalid_payment_data' => 'Payment mode, card type, currency or country not accepted.',
    'PAYMILL_unknown_error' => 'Unknown Error',
    'PAYMILL_3ds_cancelled' => '3-D Secure process has been aborted',
    'PAYMILL_field_invalid_card_number' => 'Invalid or missing card number',
    'PAYMILL_field_invalid_card_exp_year' => 'Invalid or missing expiry year',
    'PAYMILL_field_invalid_card_exp_month' => 'Invalid or missing expiry month',
    'PAYMILL_field_invalid_card_exp' => 'Card no longer (or not yet) valid',
    'PAYMILL_field_invalid_card_cvc' => 'Invalid CVC',
    'PAYMILL_field_invalid_card_holder' => 'Invalid card holder',
    'PAYMILL_field_invalid_amount_int' => 'Invalid or missing amount for 3-D Secure',
    'PAYMILL_field_field_invalid_amount' => 'Invalid or missing amount for 3-D Secure',
    'PAYMILL_field_field_field_invalid_currency' => 'Invalid or missing currency for 3-D Secure'
);
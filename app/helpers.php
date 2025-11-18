<?php

use App\Helpers\CurrencyHelper;

if (!function_exists('currency_symbol')) {
    /**
     * Get currency symbol
     *
     * @return string
     */
    function currency_symbol(): string
    {
        return CurrencyHelper::symbol();
    }
}

if (!function_exists('currency_code')) {
    /**
     * Get currency code
     *
     * @return string
     */
    function currency_code(): string
    {
        return CurrencyHelper::code();
    }
}

if (!function_exists('currency_format')) {
    /**
     * Format amount with currency
     *
     * @param float $amount
     * @param int $decimals
     * @return string
     */
    function currency_format($amount, int $decimals = 2): string
    {
        return CurrencyHelper::format($amount, $decimals);
    }
}

if (!function_exists('currency_number')) {
    /**
     * Format number only (no symbol)
     *
     * @param float $amount
     * @param int $decimals
     * @return string
     */
    function currency_number($amount, int $decimals = 2): string
    {
        return CurrencyHelper::formatNumber($amount, $decimals);
    }
}

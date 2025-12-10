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

if (!function_exists('generate_slug')) {
    /**
     * Generate URL-friendly slug with Bangla/Unicode support
     * Converts Bangla and other Unicode characters to readable ASCII equivalents
     *
     * @param string $text
     * @param string $separator
     * @return string
     */
    function generate_slug(string $text, string $separator = '-'): string
    {
        // Bangla to English transliteration map
        $banglaToEnglish = [
            'অ' => 'o',
            'আ' => 'a',
            'ই' => 'i',
            'ঈ' => 'i',
            'উ' => 'u',
            'ঊ' => 'u',
            'ঋ' => 'ri',
            'এ' => 'e',
            'ঐ' => 'oi',
            'ও' => 'o',
            'ঔ' => 'ou',
            'ক' => 'k',
            'খ' => 'kh',
            'গ' => 'g',
            'ঘ' => 'gh',
            'ঙ' => 'ng',
            'চ' => 'ch',
            'ছ' => 'chh',
            'জ' => 'j',
            'ঝ' => 'jh',
            'ঞ' => 'n',
            'ট' => 't',
            'ঠ' => 'th',
            'ড' => 'd',
            'ঢ' => 'dh',
            'ণ' => 'n',
            'ত' => 't',
            'থ' => 'th',
            'দ' => 'd',
            'ধ' => 'dh',
            'ন' => 'n',
            'প' => 'p',
            'ফ' => 'ph',
            'ব' => 'b',
            'ভ' => 'bh',
            'ম' => 'm',
            'য' => 'j',
            'র' => 'r',
            'ল' => 'l',
            'শ' => 'sh',
            'ষ' => 'sh',
            'স' => 's',
            'হ' => 'h',
            'ড়' => 'r',
            'ঢ়' => 'rh',
            'য়' => 'y',
            'ৎ' => 't',
            'ং' => 'ng',
            'ঃ' => 'h',
            'ঁ' => '',
            'া' => 'a',
            'ি' => 'i',
            'ী' => 'i',
            'ু' => 'u',
            'ূ' => 'u',
            'ৃ' => 'ri',
            'ে' => 'e',
            'ৈ' => 'oi',
            'ো' => 'o',
            'ৌ' => 'ou',
            '্' => '',
            'ৗ' => 'ou',
            '০' => '0',
            '১' => '1',
            '২' => '2',
            '৩' => '3',
            '৪' => '4',
            '৫' => '5',
            '৬' => '6',
            '৭' => '7',
            '৮' => '8',
            '৯' => '9',
        ];

        // Replace Bangla characters with English equivalents
        $text = strtr($text, $banglaToEnglish);

        // Convert to lowercase
        $text = mb_strtolower($text, 'UTF-8');

        // Replace any non-alphanumeric characters with separator
        $text = preg_replace('/[^a-z0-9]+/u', $separator, $text);

        // Remove separator from start and end
        $text = trim($text, $separator);

        // Replace multiple separators with single separator
        $text = preg_replace('/' . preg_quote($separator, '/') . '+/', $separator, $text);

        return $text;
    }
}

if (!function_exists('bengali_date')) {
    /**
     * Convert date to Bengali format
     *
     * @param \Carbon\Carbon|string|null $date
     * @param string $format Format: 'full' (default), 'short', 'custom'
     * @return string
     */
    function bengali_date($date = null, string $format = 'full'): string
    {
        $date = $date ? \Carbon\Carbon::parse($date) : now();

        // Bengali day names
        $bengaliDays = [
            'Sunday' => 'রবিবার',
            'Monday' => 'সোমবার',
            'Tuesday' => 'মঙ্গলবার',
            'Wednesday' => 'বুধবার',
            'Thursday' => 'বৃহস্পতিবার',
            'Friday' => 'শুক্রবার',
            'Saturday' => 'শনিবার'
        ];

        // Bengali month names
        $bengaliMonths = [
            'January' => 'জানুয়ারি',
            'February' => 'ফেব্রুয়ারি',
            'March' => 'মার্চ',
            'April' => 'এপ্রিল',
            'May' => 'মে',
            'June' => 'জুন',
            'July' => 'জুলাই',
            'August' => 'আগস্ট',
            'September' => 'সেপ্টেম্বর',
            'October' => 'অক্টোবর',
            'November' => 'নভেম্বর',
            'December' => 'ডিসেম্বর'
        ];

        // Bengali numbers
        $bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        $day = $bengaliDays[$date->format('l')];
        $dateNum = $date->format('d');
        $month = $bengaliMonths[$date->format('F')];
        $year = $date->format('Y');

        // Convert numbers to Bengali
        $dateNum = str_replace(range(0, 9), $bengaliNumbers, $dateNum);
        $year = str_replace(range(0, 9), $bengaliNumbers, $year);

        // Return based on format
        switch ($format) {
            case 'short':
                return "{$dateNum} {$month}, {$year}";
            case 'day_only':
                return $day;
            case 'date_only':
                return "{$dateNum} {$month}";
            case 'full':
            default:
                return "{$day}, {$dateNum} {$month}, {$year}";
        }
    }
}

if (!function_exists('bengali_number')) {
    /**
     * Convert English numbers to Bengali numbers
     *
     * @param int|string $number
     * @return string
     */
    function bengali_number($number): string
    {
        $bengaliNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str_replace(range(0, 9), $bengaliNumbers, (string) $number);
    }
}

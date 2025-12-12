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
        $hour24 = (int) $date->format('H');
        $minute = $date->format('i');

        // Convert to 12-hour format
        $hour12 = $hour24 % 12;
        if ($hour12 == 0) {
            $hour12 = 12;
        }

        // Determine Bengali time period based on hour
        $timePeriod = '';
        if ($hour24 >= 4 && $hour24 < 6) {
            $timePeriod = 'ভোর'; // Dawn (4 AM - 6 AM)
        } elseif ($hour24 >= 6 && $hour24 < 12) {
            $timePeriod = 'সকাল'; // Morning (6 AM - 12 PM)
        } elseif ($hour24 >= 12 && $hour24 < 15) {
            $timePeriod = 'দুপুর'; // Noon/Afternoon (12 PM - 3 PM)
        } elseif ($hour24 >= 15 && $hour24 < 18) {
            $timePeriod = 'বিকেল'; // Evening (3 PM - 6 PM)
        } elseif ($hour24 >= 18 && $hour24 < 20) {
            $timePeriod = 'সন্ধ্যা'; // Dusk (6 PM - 8 PM)
        } else {
            $timePeriod = 'রাত'; // Night (8 PM - 4 AM)
        }

        // Convert numbers to Bengali
        $dateNum = str_replace(range(0, 9), $bengaliNumbers, $dateNum);
        $year = str_replace(range(0, 9), $bengaliNumbers, $year);
        $hour12Bengali = str_replace(range(0, 9), $bengaliNumbers, (string) $hour12);
        $minute = str_replace(range(0, 9), $bengaliNumbers, $minute);

        // Return based on format
        switch ($format) {
            case 'short':
                return "{$dateNum} {$month}, {$year}";
            case 'short_time':
                return "{$dateNum} {$month}, {$year} - {$timePeriod} {$hour12Bengali}:{$minute} মি.";
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

if (!function_exists('bengali_time_ago')) {
    /**
     * Convert time difference to Bangla format
     * Examples: "২ মিনিট আগে", "২ ঘণ্টা আগে", "গতকাল", "৩ দিন আগে"
     *
     * @param \Carbon\Carbon|string|null $datetime
     * @return string
     */
    function bengali_time_ago($datetime): string
    {
        $datetime = $datetime ? \Carbon\Carbon::parse($datetime) : now();
        $now = now();

        $diffInSeconds = $now->diffInSeconds($datetime);
        $diffInMinutes = $now->diffInMinutes($datetime);
        $diffInHours = $now->diffInHours($datetime);
        $diffInDays = $now->diffInDays($datetime);

        // Just now (less than 1 minute)
        if ($diffInSeconds < 60) {
            return 'এই মাত্র';
        }

        // Minutes ago (1-59 minutes)
        if ($diffInMinutes < 60) {
            return bengali_number($diffInMinutes) . ' মিনিট আগে';
        }

        // Hours ago (1-23 hours)
        if ($diffInHours < 24) {
            return bengali_number($diffInHours) . ' ঘণ্টা আগে';
        }

        // Yesterday
        if ($diffInDays == 1) {
            return 'গতকাল';
        }

        // Days ago (2-6 days)
        if ($diffInDays < 7) {
            return bengali_number($diffInDays) . ' দিন আগে';
        }

        // Weeks ago (1-3 weeks)
        if ($diffInDays < 30) {
            $weeks = floor($diffInDays / 7);
            return bengali_number($weeks) . ' সপ্তাহ আগে';
        }

        // Months ago (1-11 months)
        if ($diffInDays < 365) {
            $months = floor($diffInDays / 30);
            return bengali_number($months) . ' মাস আগে';
        }

        // Years ago
        $years = floor($diffInDays / 365);
        return bengali_number($years) . ' বছর আগে';
    }
}

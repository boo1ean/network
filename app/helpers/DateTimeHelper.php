<?php
namespace app\helpers;

class DateTimeHelper
{
    /**
     * @param $ts string timestamp
     * @return string relative date
     * @link http://stackoverflow.com/questions/2690504/php-producing-relative-date-time-from-timestamps
     */
    public static function relativeTime($ts) {
        if(!ctype_digit($ts))
            $ts = strtotime($ts);

        $diff = time() - $ts;
        if($diff == 0) {
            return 'now';
        } elseif($diff > 0) {
            $day_diff = floor($diff / 86400);
            if($day_diff == 0) {
                if($diff < 60) return 'just now';
                if($diff < 120) return '1 minute ago';
                if($diff < 3600) return floor($diff / 60) . ' minutes ago';
                if($diff < 7200) return '1 hour ago';
                if($diff < 86400) return floor($diff / 3600) . ' hours ago';
            }
            if($day_diff == 1) return 'Yesterday';
            if($day_diff < 7) return $day_diff . ' days ago';
            if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
            if($day_diff < 60) return 'last month';
            return date('F Y', $ts);
        } else {
            $diff = abs($diff);
            $day_diff = floor($diff / 86400);
            if($day_diff == 0) {
                if($diff < 120) return 'in a minute';
                if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
                if($diff < 7200) return 'in an hour';
                if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
            }
            if($day_diff == 1) return 'Tomorrow';
            if($day_diff < 4) return date('l', $ts);
            if($day_diff < 7 + (7 - date('w'))) return 'next week';
            if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
            if(date('n', $ts) == date('n') + 1) return 'next month';
            return date('F Y', $ts);
        }
    }

    /**
     * Return datetime format for displaying
     * @param $str string datetime
     * @param bool $showSeconds true if need to display seconds, else - false
     * @return bool|string result datetime string in pretty format (example 16 August 2013 18:19:43)
     */
    public static function formatTime($str, $showSeconds = false){
        $timestamp = strtotime($str);
        $format = 'd F Y G:i';
        if ($showSeconds) {
            $format .= ':s';
        }
        return date($format, $timestamp);
    }
}
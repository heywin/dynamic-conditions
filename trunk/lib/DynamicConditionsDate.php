<?php namespace Lib;

class DynamicConditionsDate {

    /**
     * Filter date-output from date_i18n() to return always a timestamp
     *
     * @param string $j Formatted date string.
     * @param string $req_format Format to display the date.
     * @param int $i Unix timestamp.
     * @param bool $gmt Whether to convert to GMT for time. Default false.
     * @return int Unix timestamp
     */
    public function filterDateI18n( $j, $req_format, $i, $gmt ) {
        return $i;
    }

    /**
     * Filters the date of a post to return a timestamp
     *
     * @param string|bool $the_time The formatted date or false if no post is found.
     * @param string $d PHP date format. Defaults to value specified in
     *                               'date_format' option.
     * @param WP_Post|null $post WP_Post object or null if no post is found.
     *
     * @return mixed
     */
    public function filterPostDate( $the_time, $d, $post ) {
        if ( empty( $d ) ) {
            return $the_time;
        }
        $date = \DateTime::createFromFormat( $d, $the_time );
        if ( empty( $date ) ) {
            return $the_time;
        }

        return $date->getTimestamp();
    }

    /**
     * Convert string to timestamp or return string if it´s already a timestamp
     *
     * @param $string
     * @return int
     */
    public static function stringToTime( $string = '' ) {
        $timestamp = $string;
        $strToTime = strtotime( $string );
        if ( !empty( $strToTime ) ) {
            $timestamp = $strToTime;
        }

        return $timestamp;
    }

    /**
     * Untranslate a date-string to english date
     *
     * @param string $needle
     * @param null $setLocale
     * @return mixed|string
     */
    public static function unTranslateDate( $needle = '', $setLocale = null ) {
        if ( empty( $setLocale ) ) {
            $setLocale = get_locale();
        }
        $currentLocale = setlocale( LC_TIME, 0 );

        // get in translated lang
        setlocale( LC_TIME, $setLocale );
        $translatedMonths = self::loopMonths();
        $translatedDays = self::loopDays();
        setlocale( LC_TIME, $currentLocale );

        // get in english
        $englishMonths = self::getEnglishMonths();
        $englishDays = self::getEnglishDays();

        // replace translated days/months with english ones
        $needle = str_ireplace( $translatedDays, $englishDays, $needle );
        $needle = str_ireplace( $translatedMonths, $englishMonths, $needle );

        return $needle;
    }

    /**
     * Return a list of english days
     *
     * @return array
     */
    private static function getEnglishDays() {
        $englishDays = [];
        $year = date( 'o', time() );
        $week = date( 'W', time() );

        for ( $i = 1; $i <= 7; $i++ ) {
            $time = strtotime( $year . 'W' . $week . $i );
            $englishDays[$i] = date( "l", $time );
        }

        return $englishDays;
    }

    /**
     * Return a list of english months
     *
     * @return array
     */
    private static function getEnglishMonths() {
        $englishMonths = [];
        for ( $i = 1; $i <= 12; ++$i ) {
            $englishMonths[$i] = date( 'F', mktime( 0, 0, 0, $i, 1 ) );
        }

        return $englishMonths;
    }

    /**
     * Get a list of months (january, february,...)
     *
     * @return array
     */
    public static function getMonths() {
        $currentLocale = setlocale( LC_TIME, 0 );
        setlocale( LC_TIME, get_locale() );
        $monthList = self::loopMonths();
        setlocale( LC_TIME, $currentLocale );

        return $monthList;
    }

    /**
     * Loops all months an return in a list
     *
     * @return array
     */
    private static function loopMonths() {
        $monthList = [];
        for ( $i = 1; $i <= 12; ++$i ) {
            $monthList[$i] = strftime( '%B', mktime( 0, 0, 0, $i, 1 ) );
        }

        return $monthList;
    }

    /**
     * Get a list of days (monday, tuesday,...)
     *
     * @return array
     */
    public static function getDays() {
        $currentLocale = setlocale( LC_TIME, 0 );
        setlocale( LC_TIME, get_locale() );
        $dayList = self::loopDays();
        setlocale( LC_TIME, $currentLocale );

        return $dayList;
    }

    /**
     * Loops all days an return in a list
     *
     * @return array
     */
    private static function loopDays() {
        $dayList = [];
        $year = date( 'o', time() );
        $week = date( 'W', time() );
        for ( $i = 1; $i <= 7; $i++ ) {
            $time = strtotime( $year . 'W' . $week . $i );
            $dayList[$i] = strftime( "%A", $time );
        }

        return $dayList;
    }
}

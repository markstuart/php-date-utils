<?php

namespace PhpDateUtils;

class PhpDateUtils {

    private $localTimeZone;
    private $localFormat;

    function __construct($localTimeZone, $localFormat) {
        $this->localTimeZone = $localTimeZone;
        $this->localFormat = $localFormat;
    }

    public function newUtcDateTime() {
        return new \DateTime('now', new \DateTimeZone('UTC'));
    }

    public function newLocalDateTime() {
        return new \DateTime('now', new \DateTimeZone($this->localTimeZone));
    }

    public function localDateTimeToUtcDateTime(\DateTime $localDateTime) {
        $utcDateTime = clone $localDateTime;
        $utcDateTime->setTimeZone(new \DateTimeZone('UTC'));
        return $utcDateTime;
    }

    public function utcDateTimeToLocalDateTime(\DateTime $utcDateTime) {
        $localDateTime = clone $utcDateTime;
        $localDateTime->setTimeZone(new \DateTimeZone($this->localTimeZone));
        return $localDateTime;
    }

    public function mysqlUtcDateStringToDateTime($dateString) {
        $timezone = new \DateTimeZone('utc');
        return \DateTime::createFromFormat('Y-m-d H:i:s', $dateString, $timezone);
    }

    public function localDateStringToDateTime($dateString) {
        $timezone = new \DateTimeZone($this->localTimeZone);
        $format = $this->localFormat;
        return \DateTime::createFromFormat($format, $dateString, $timezone);
    }

    public function dateTimeToMysqlDateString(\DateTime $dateTime) {
        return $dateTime->format('Y-m-d H:i:s');
    }

    public function dateTimeToLocalDateString(\DateTime $dateTime, $options = []) {
        $defaultOptions = [
            'format' => $this->localFormat,
        ];
        $options = array_merge($defaultOptions, $options);
        return $dateTime->format($options['format']);
    }

    /**
     * Creates a new UTC MySQL formatted date string.
     */
    public function newUtcMysqlDateString() {
        return self::dateTimeToMysqlDateString(self::newUtcDateTime());
    }

    /**
     * Creates a new date string formatted and timezoned according to env vars
     * LOCAL_DATE_FORMAT and LOCAL_TIME_ZONE
     */
    public function newLocalDateString() {
        return self::dateTimeToLocalDateString(self::newLocalDateTime());
    }

    /**
     * Takes a UTC MySQL formatted date string and converts it to a locally
     * timezoned, locally formatted date string.
     * Uses LOCAL_DATE_FORMAT and LOCAL_TIME_ZONE env vars to determine locally.
     */
    public function utcMysqlDateStringToLocalDateString($dateString, $options = []) {
        $utcDateTime   = self::mysqlUtcDateStringToDateTime($dateString);
        $localDateTime = self::utcDateTimeToLocalDateTime($utcDateTime);
        return self::dateTimeToLocalDateString($localDateTime, $options);
    }

    /**
     * Takes a locally formatted and locally timezoned date string and converts it
     * to a MySQL formatted UTC timezoned date string.
     * Uses LOCAL_DATE_FORMAT and LOCAL_TIME_ZONE env vars to determine locally.
     */
    public function localDateStringToUtcMysqlDateString($dateString) {
        $localDateTime = self::localDateStringToDateTime($dateString);
        $utcDateTime   = self::localDateTimeToUtcDateTime($localDateTime);
        return self::dateTimeToMysqlDateString($utcDateTime);
    }

}

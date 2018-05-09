<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2018 w-vision AG (https://www.w-vision.ch)
 */

namespace WvisionBundle\Tool;

class Ics
{
    /**
     * The default date and time format.
     */
    const DT_FORMAT = 'Ymd\THis\Z';

    /**
     * @var array Available properties.
     */
    private static $availableProperties = [
        'description',
        'dtend',
        'dtstart',
        'location',
        'summary',
        'url'
    ];

    /**
     * @var array Properties passed to the object.
     */
    protected $properties = [];

    /**
     * Receives all data.
     *
     * @param $props
     */
    public function setProps($props)
    {
        $this->set($props);
    }

    /**
     * Sets all properties.
     *
     * @param $key
     * @param bool $val
     */
    public function set($key, $val = false)
    {
        if (\is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            if (\in_array($key, self::$availableProperties, true)) {
                $this->properties[$key] = $this->sanitizeVal($val, $key);
            }
        }
    }

    /**
     * Converts all properties to a ICS-file.
     *
     * @return string The ICS-file
     */
    public function toString(): string
    {
        $rows = $this->buildProps();

        return implode("\r\n", $rows);
    }

    /**
     * Composes all properties together.
     *
     * @return array
     */
    private function buildProps(): array
    {
        // Build ICS properties - add header
        $icsProps = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//hacksw/handcal//NONSGML v1.0//EN',
            'CALSCALE:GREGORIAN',
            'BEGIN:VEVENT'
        ];

        // Build ICS properties - add header
        $props = [];
        foreach ($this->properties as $k => $v) {
            $props[strtoupper($k . ($k === 'url' ? ';VALUE=URI' : ''))] = $v;
        }

        // Set some default values
        $props['DTSTAMP'] = $this->formatTimestamp('now');
        $props['UID'] = uniqid('ics_', true);
        // Append properties
        foreach ($props as $k => $v) {
            $icsProps[] = "$k:$v";
        }

        // Build ICS properties - add footer
        $icsProps[] = 'END:VEVENT';
        $icsProps[] = 'END:VCALENDAR';

        return $icsProps;
    }

    /**
     * Sanitize all data.
     *
     * @param $val
     * @param bool $key
     * @return mixed|string
     */
    private function sanitizeVal($val, $key = false)
    {
        switch ($key) {
            case 'dtend':
            case 'dtstamp':
            case 'dtstart':
                $val = $this->formatTimestamp($val);
                break;
            default:
                $val = $this->escapeString($val);
        }

        return $val;
    }

    /**
     * Formats a timestamp with the given setting.
     *
     * @param $timestamp
     * @return string
     */
    private function formatTimestamp($timestamp): string
    {
        $dt = new \DateTime($timestamp);

        return $dt->format(self::DT_FORMAT);
    }

    /**
     * Escapes all string.
     *
     * @param $str
     * @return null|string|string[]
     */
    private function escapeString($str)
    {
        return preg_replace('/([\,;])/','\\\$1', $str);
    }
}
<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\CarbonExtension\In;

use Carbon\Carbon;

/**
 * Class CarbonFormat
 */
class CarbonFormat
{
    /**
     * RFC3339 date format.
     * Example: `2005-08-15T15:52:01+00:00`
     */
    public const DEFAULT = 'RFC3339';

    /**
     * Simple Date format.
     * Example: `2005-08-15`
     */
    public const DATE = 'DATE';

    /**
     * Simple Time format.
     * Example: `15:52:01`
     */
    public const TIME = 'TIME';

    /**
     * Simple DateTime format.
     * Example: `2005-08-15 15:52:01`
     */
    public const DATE_TIME = 'DATE_TIME';

    /**
     * RFC 7231 date format.
     * Example: `Mon, 15 Aug 2005 15:52:01 GMT`
     */
    public const RFC7231 = 'RFC7231';

    /**
     * ISO-8601 date format.
     * Example: `2005-08-15T15:52:01+00:00`
     *
     * Note: This format is an alias of the RFC 3339 specification:
     * @see https://www.iso.org/iso-8601-date-and-time-format.html
     * @see https://www.ietf.org/rfc/rfc3339.txt
     */
    public const ISO8601 = 'ISO8601';

    /**
     * Human readable string.
     * Example: `2 days ago`
     */
    public const HUMAN_READABLE = 'HUMAN_READABLE';

    /**
     * @param string $value
     * @return string
     */
    public function fromEnumeration(string $value): string
    {
        switch ($value) {
            /**
             * Compatibility bugfix.
             * @see http://php.net/manual/en/class.datetime.php
             */
            case static::ISO8601:
                return Carbon::RFC3339;

            case static::RFC7231:
                return Carbon::RFC7231_FORMAT;

            case static::DATE_TIME:
                return 'Y-m-d H:i:s';

            case static::DATE:
                return 'Y-m-d';

            case static::TIME:
                return 'H:i:s';

            case static::HUMAN_READABLE:
                return static::HUMAN_READABLE;
        }

        return \constant(Carbon::class . '::' . $value);
    }
}

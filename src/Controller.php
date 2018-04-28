<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\CarbonExtension;

use Carbon\Carbon;
use Railt\CarbonExtension\Exception\InvalidDateTimeFormat;
use Railt\Http\InputInterface;

/**
 * Class Controller
 */
class Controller
{
    /**
     * Simple Date format.
     * Example: `2005-08-15`
     */
    private const DATE = 'DATE';

    /**
     * Simple Time format.
     * Example: `15:52:01`
     */
    private const TIME = 'TIME';

    /**
     * Simple DateTime format.
     * Example: `2005-08-15 15:52:01`
     */
    private const DATE_TIME = 'DATE_TIME';

    /**
     * RFC 7231 date format.
     * Example: `Mon, 15 Aug 2005 15:52:01 GMT`
     */
    private const RFC7231 = 'RFC7231';

    /**
     * ISO-8601 date format.
     * Example: `2005-08-15T15:52:01+00:00`
     *
     * Note: This format is an alias of the RFC 3339 specification:
     * @see https://www.iso.org/iso-8601-date-and-time-format.html
     * @see https://www.ietf.org/rfc/rfc3339.txt
     */
    private const ISO8601 = 'ISO8601';

    /**
     * Human readable string.
     * Example: `2 days ago`
     */
    private const HUMAN_READABLE = 'HUMAN_READABLE';

    /**
     * @param InputInterface $input
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDateTime(InputInterface $input): string
    {
        return $this->format($this->extract($input), $input->get('format'));
    }

    /**
     * @param \DateTimeInterface|\DateInterval $date
     * @param string $format
     * @return string
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Translation\Exception\InvalidArgumentException
     */
    private function format($date, string $format): string
    {
        switch ($format) {
            /**
             * Compatibility bugfix.
             * @see http://php.net/manual/en/class.datetime.php
             */
            case self::ISO8601:
                return $date->format(Carbon::RFC3339);

            case self::RFC7231:
                return $date->format(Carbon::RFC7231_FORMAT);

            case self::DATE_TIME:
                return $date->format('Y-m-d H:i:s');

            case self::DATE:
                return $date->format('Y-m-d');

            case self::TIME:
                return $date->format('H:i:s');

            case self::HUMAN_READABLE && $date instanceof \DateTimeInterface:
                return Carbon::instance($date)->diffForHumans();
        }

        return $date->format(\constant(Carbon::class . '::' . $format));
    }

    /**
     * @param InputInterface $input
     * @return Carbon
     * @throws \InvalidArgumentException
     */
    private function extract(InputInterface $input): Carbon
    {
        $date = $input->getParent();

        if ($date instanceof \DateTimeInterface) {
            return Carbon::instance($date);
        }

        $error = 'Return type of GraphQL DateTime must be an instance of DateTime, but %s given';
        $type  = \is_object($date) ? \get_class($date) : \strtolower(\gettype($date));

        throw new \InvalidArgumentException(\sprintf($error, $type));
    }

    /**
     * @param InputInterface $input
     * @return string
     * @throws \InvalidArgumentException
     */
    public function diff(InputInterface $input): string
    {
        /** @var Carbon $value */
        $value = $this->extract($input);

        /** @var Carbon $diff */
        $diff = $this->extractDiff($input);

        /** @var string $format */
        $format = $input->get('format');

        return $format === self::HUMAN_READABLE
            ? $value->diffForHumans($diff)
            : $this->format($value->diff($diff), $format);
    }

    /**
     * @param InputInterface $input
     * @return Carbon
     * @throws InvalidDateTimeFormat
     * @throws \InvalidArgumentException
     */
    private function extractDiff(InputInterface $input): Carbon
    {
        if ($input->has('with')) {
            try {
                return Carbon::parse($input->get('with'));
            } catch (\Throwable $e) {
                throw new InvalidDateTimeFormat('Invalid DateTime format passed in "with" argument');
            }
        }

        return Carbon::now($this->extract($input)->getTimezone());
    }
}

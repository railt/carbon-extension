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
use Railt\CarbonExtension\Exception\InvalidDateTimeFormat;

/**
 * Class CarbonDateTime
 */
class CarbonDateTime
{
    /**
     * @param string $value
     * @return \DateTimeInterface
     * @throws InvalidDateTimeFormat
     */
    public function fromString(string $value): \DateTimeInterface
    {
        try {
            if ((string)(int)$value === $value) {
                return Carbon::createFromTimestamp((int)$value);
            }

            return Carbon::parse($value);
        } catch (\Throwable $e) {
            $error = 'Unknown or unsupported input CarbonDateTime format "%s"';
            throw new InvalidDateTimeFormat(\sprintf($error, $value));
        }
    }

    /**
     * @param mixed $value
     * @return \DateTimeInterface|null
     * @throws InvalidDateTimeFormat
     * @throws \InvalidArgumentException
     */
    public function verify($value): ?\DateTimeInterface
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value;
        }

        $error = 'Input CarbonDateTime scalar should be compatible with String or Int scalar type';
        throw new InvalidDateTimeFormat($error);
    }
}

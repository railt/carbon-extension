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
use Railt\CarbonExtension\In\CarbonFormat;
use Railt\Http\InputInterface;

/**
 * Class CarbonController
 */
class CarbonController
{
    /**
     * @param InputInterface $input
     * @return Carbon|null
     * @throws InvalidDateTimeFormat
     * @throws \InvalidArgumentException
     */
    private function getValue(InputInterface $input): ?Carbon
    {
        $value = $input->getParentResponse()[$input->getFieldName()] ?? null;

        switch (true) {
            case $value === null:
                return null;
            case $value instanceof Carbon;
                return $value;
            case $value instanceof \DateTimeInterface;
                return Carbon::instance($value);
            case \is_string($value):
                return Carbon::parse($value);
            case \is_int($value) || \is_float($value):
                return Carbon::createFromTimestamp((int)$value);
        }

        $error = 'Response type of field %f should be a DateTime, string or int, but %s given';
        throw new InvalidDateTimeFormat(\sprintf($error, $input->getFieldDefinition(), \gettype($value)));
    }

    /**
     * @param InputInterface $input
     * @param string $format
     * @param Carbon|null $diff
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getDateTime(InputInterface $input, string $format, Carbon $diff = null): ?string
    {
        $value = $this->getValue($input);

        if ($value === null) {
            return null;
        }

        if ($format === CarbonFormat::HUMAN_READABLE || $diff !== null) {
            return $this->diff($value, $diff, $format);
        }

        return $value->format($format);
    }

    /**
     * @param Carbon $current
     * @param Carbon|null $diff
     * @param string $format
     * @return string
     * @throws \Symfony\Component\Translation\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     */
    private function diff(Carbon $current, ?Carbon $diff, string $format): string
    {
        if ($format === CarbonFormat::HUMAN_READABLE) {
            return $current->diffForHumans($diff);
        }

        $interval = $current->diff($diff ?? Carbon::now());

        return $current->add($interval)->format($format);
    }
}

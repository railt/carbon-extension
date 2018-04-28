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

/**
 * Class CarbonPresenter
 */
final class CarbonPresenter
{
    /**
     * @param \DateTimeInterface $dateTime
     * @return array
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Translation\Exception\InvalidArgumentException
     */
    public function fromDateTime(\DateTimeInterface $dateTime): iterable
    {
        yield from $this->fromCarbon(Carbon::instance($dateTime));
    }

    /**
     * @param string $dateTimeString
     * @return iterable
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Translation\Exception\InvalidArgumentException
     */
    public function fromString(string $dateTimeString): iterable
    {
        yield from $this->fromCarbon(Carbon::parse($dateTimeString));
    }

    /**
     * @param Carbon $carbon
     * @return iterable
     * @throws \Symfony\Component\Translation\Exception\InvalidArgumentException
     */
    public function fromCarbon(Carbon $carbon): iterable
    {
        yield 'date' => $carbon->toRfc3339String();
        yield 'diff' => $carbon->diffForHumans();
    }
}

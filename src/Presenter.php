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
 * Class Presenter
 */
final class Presenter
{
    /**
     * @param Carbon $carbon
     * @return array
     */
    public function fromCarbon(Carbon $carbon): array
    {
        return [
            'format'        => $carbon->toRfc3339String(),
            'diffForHumans' => $carbon->diffForHumans(),
        ];
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return array
     */
    public function fromDateTime(\DateTimeInterface $dateTime): array
    {
        return $this->fromCarbon(Carbon::instance($dateTime));
    }
}

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
use Railt\Http\InputInterface;

/**
 * Class Controller
 */
class Controller
{
    /**
     * @param InputInterface $input
     * @return string
     * @throws \InvalidArgumentException
     */
    public function format(InputInterface $input): string
    {
        $format = \constant(Carbon::class . '::' . $input->get('format'));

        return $this->getDateTime($input)->format($format);
    }

    /**
     * @param InputInterface $input
     * @return \DateTimeInterface
     */
    private function getDateTime(InputInterface $input): \DateTimeInterface
    {
        $date = $input->getParent();

        if ($date instanceof \DateTimeInterface) {
            return $date;
        }

        $error = 'Return type of GraphQL DateTime must be an instance of DateTime, but %s given';
        $type  = \is_object($date) ? \get_class($date) : \strtolower(\gettype($type));

        throw new \InvalidArgumentException(\sprintf($error, $type));
    }

    /**
     * @param InputInterface $input
     * @return string
     * @throws \InvalidArgumentException
     */
    public function diffForHumans(InputInterface $input): string
    {
        $response = $this->getDateTime($input);

        if ($response instanceof Carbon) {
            return $response->diffForHumans();
        }

        return Carbon::instance($response)->diffForHumans();
    }
}

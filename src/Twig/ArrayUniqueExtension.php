<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ArrayUniqueExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('array_unique', [$this, 'arrayUnique']),
        ];
    }

    /*
     * https://stackoverflow.com/questions/307674/how-to-remove-duplicate-values-from-a-multi-dimensional-array-in-php
     */
    public function arrayUnique($input): array
    {
        $serialized = array_map('serialize', $input);
        $unique = array_unique($serialized);
        return array_intersect_key($input, $unique);
    }
}

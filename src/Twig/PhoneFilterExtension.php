<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PhoneFilterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('phonefilter', [$this, 'phonefilter'], ['is_safe' => ['html']]),
        ];
    }

    public function phonefilter($value)
    {
        $phone_number = preg_replace('/(\(0\))|[\s-]+/', '', $value);
        $phone_number = preg_replace('/^00/', '+', $phone_number);

        return preg_replace('/^0/', '+31', $phone_number);
    }
}

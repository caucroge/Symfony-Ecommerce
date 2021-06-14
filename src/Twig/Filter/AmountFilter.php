<?php

namespace App\Twig\Filter;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountFilter extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('amount', [$this, "amount"])
        ];
    }

    public function amount($value, int $decimal = 2, string $symbol = '€', string $sepDec = ",", string $sepThousand = ' ')
    {
        $finalValue = $value / 100;
        $finalValue = number_format($finalValue, $decimal, $sepDec, $sepThousand);

        return "$finalValue $symbol";
    }
}

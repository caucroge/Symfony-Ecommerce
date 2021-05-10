<?php

namespace App\Taxes;

use Psr\Log\LoggerInterface;

class Calculator
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function calcul(float $prix) : float
    {
        $this->logger->info("Utilisation du service Calculator");
        return $prix * (20/100);
    }
}
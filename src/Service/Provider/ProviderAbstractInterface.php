<?php

namespace App\Service\Provider;

use App\Entity\Task; 

/**
 * Interface ProviderAbstractInterface
 * @package App\Interfaces
 */
interface ProviderAbstractInterface
{
    /**
     * @return Task[]|null 
     */
    public function getTasks(): array;
}
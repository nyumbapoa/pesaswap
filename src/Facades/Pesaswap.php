<?php

namespace Nyumbapoa\Pesaswap\Facades;

use Nyumbapoa\Pesaswap\Pesaswap as PesaswapGateway;
use Illuminate\Support\Facades\Facade;

class Pesaswap extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'nyumbapoa-pesaswap';
    }
}
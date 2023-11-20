<?php
namespace Hen8y\Vpay\App\Facades;

use Illuminate\Support\Facades\Facade;



class Vpay extends Facade
{


    protected static function getFacadeAccessor(){
        
        return "Vpay";
    }

    
}
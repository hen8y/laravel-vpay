<?php

namespace Hen8y\Vpay\App\Commands;

use Illuminate\Console\command;

 class Publish extends command{


    protected $name = "make:vpay";
    protected $description = "Publishes the required files for vpay";
    public $composer;

    public function __construct(){

        parent::__construct();

        $this->composer = app()['composer'];
    }




    public function handle(){


        $app_path = app_path()."/Jobs";

        $config_path = config_path();

        $resource_path = resource_path()."/views/vpay";


        $vpay_job = file_get_contents(__DIR__ .'/../stubs/vpayjob.stub');

        $vpay_config = file_get_contents(__DIR__ .'/../stubs/config.stub');

        $vpay_checkout = file_get_contents(__DIR__ ."/../stubs/checkout.stub");

        $this->createFile($app_path. DIRECTORY_SEPARATOR,'VpayJob.php',$vpay_job);
        $this->info('Vpay job published In '.$app_path);

        $this->createFile($config_path. DIRECTORY_SEPARATOR,'vpay.php',$vpay_config);
        $this->info('Vpay config published in '.$config_path);


        $this->createFile($resource_path. DIRECTORY_SEPARATOR,'checkout.blade.php',$vpay_checkout);
        $this->info('Vpay checkout page published in '.$resource_path);

        $this->info('Generating autoload files');
        $this->composer->dumpOptimized();
    }

    public static function createFile($path, $filename, $contents){

        if(!file_exists($path)){
            
            mkdir($path,0755, true);
        }

        $path = $path.$filename;

        file_put_contents($path, $contents);
    }
 }
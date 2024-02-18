<?php

namespace Hen8y\Vpay\App\Commands;

use Illuminate\Console\command;

 class Publish extends command{


    protected $name = "vpay:publish";
    protected $description = "Publishes the required files for vpay";
    public $composer;

    public function __construct(){

        parent::__construct();

        $this->composer = app()['composer'];
    }




    public function handle(){


        $app_path = app_path()."/Jobs";


        $vpay_job = file_get_contents(__DIR__ .'/../stubs/vpayjob.stub');


        $this->createFile($app_path. DIRECTORY_SEPARATOR,'VpayJob.php',$vpay_job);
        $this->info('Vpay job published In '.$app_path);


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
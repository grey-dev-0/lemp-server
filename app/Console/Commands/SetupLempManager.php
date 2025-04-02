<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupLempManager extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemp:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets up the LEMP manager web app database and server configurations.';

    /**
     * Execute the console command.
     */
    public function handle(){
        $this->info('Setting up database..');
        $password = env('DB_PASSWORD');
        $dbCheck = trim(`docker exec lemp-mariadb-1 mysql -u root -p$password -N -e 'show databases;' | grep --color=none {$this->project->database}`);
        if($dbCheck != 'lemp'){
            $output = `docker exec lemp-mariadb-1 mysql -u root -p$password -e 'create database lemp;'`;
            $output .= `php artisan migrate`;
            $this->comment($output);
            $this->comment('Database setup complete.');
        } else
            $this->comment('Database already exists.');
    }
}

<?php

namespace App\Jobs;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProvisionProject implements ShouldQueue{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param Project $project The project to be provisioned.
     */
    public function __construct(private Project $project){}

    /**
     * Execute the job.
     */
    public function handle(): void{
        $password = env('DB_PASSWORD');
        $dbCheck = trim(`docker exec lemp-mariadb-1 mysql -u root -p$password -N -e 'show databases;' | grep --color=none {$this->project->database}`);
        \Log::info("Provisioning project located in '{$this->project->path}'..");
        if($dbCheck != $this->project->database){
            \Log::info("Creating database {$this->project->database}");
            \Log::comment(`docker exec lemp-mariadb-1 mysql -u root -p$password -e 'create database {$this->project->database};'`);
        } else
            \Log::info("Database {$this->project->database} already exists.");
        $this->project->update(['provisioned_at' => now()]);
        $this->project->domains->each(function($domain){
            dispatch(new ProvisionDomain($domain));
        });
    }
}

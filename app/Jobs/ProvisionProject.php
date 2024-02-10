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
        if($dbCheck != $this->project->database)
            `docker exec lemp-mariadb-1 mysql -u root -p$password -e 'create database {$this->project->database};'`;
        $this->project->update(['provisioned_at' => now()]);
        $this->project->domains->each(function($domain){
            dispatch(new ProvisionDomain($domain));
        });
    }
}

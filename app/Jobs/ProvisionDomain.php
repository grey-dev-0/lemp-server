<?php

namespace App\Jobs;

use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProvisionDomain implements ShouldQueue{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param Domain $domain The domain to be provisioned.
     */
    public function __construct(private Domain $domain){}

    /**
     * Execute the job.
     */
    public function handle(): void{
        # TODO: Provisioning domain.
        $this->domain->update(['provisioned_at' => now()]);
    }
}

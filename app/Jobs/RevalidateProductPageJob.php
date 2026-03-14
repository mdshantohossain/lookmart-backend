<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class RevalidateProductPageJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $slug)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $url = config('services.frontend.url').'/api/revalidate';

        Http::post($url, [
            'secret' => config('services.frontend.revalidate_secret'),
            'path' => '/products/' . $this->slug,
        ]);
    }
}

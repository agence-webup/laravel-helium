<?php

namespace Webup\LaravelHelium\Redirection\Http\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Webup\LaravelHelium\Redirection\Http\Repositories\RedirectionRepository;

class DestroyRedirection implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RedirectionRepository $redirectionRepo)
    {
        $redirectionRepo->deleteById($this->id);
    }
}

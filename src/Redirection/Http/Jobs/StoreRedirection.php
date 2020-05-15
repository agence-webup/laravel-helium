<?php

namespace Webup\LaravelHelium\Redirection\Http\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Webup\LaravelHelium\Redirection\Entities\Redirection;
use Webup\LaravelHelium\Redirection\Http\Repositories\RedirectionRepository;

class StoreRedirection implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RedirectionRepository $redirectionRepo)
    {
        $redirection = new Redirection();


        $fromUrl = Arr::get($this->data, "from");
        foreach (config("helium.modules.redirection.removeparts", []) as $removepart) {
            $fromUrl = str_replace($removepart, "", $fromUrl);
        }
        $toUrl = Arr::get($this->data, "to");
        foreach (config("helium.modules.redirection.removeparts", []) as $removepart) {
            $toUrl = str_replace($removepart, "", $toUrl);
        }

        //Add first slash
        $fromUrl = '/' . ltrim($fromUrl, '/');
        $toUrl = '/' . ltrim($toUrl, '/');
        //Remove last slash
        $fromUrl = rtrim($fromUrl, '/');
        $toUrl = rtrim($toUrl, '/');

        if ($fromUrl == $toUrl) {
            throw new \Exception("Error Processing Request", 1);
            return;
        }
        $redirection->from = $fromUrl;
        $redirection->to = $toUrl;


        $redirectionRepo->save($redirection);

        return $redirection;
    }
}

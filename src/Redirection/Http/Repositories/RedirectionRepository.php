<?php

namespace Webup\LaravelHelium\Redirection\Http\Repositories;

use Webup\LaravelHelium\Redirection\Entities\Redirection;

class RedirectionRepository
{
    public function all()
    {
        return Redirection::all();
    }

    public function get($id)
    {
        return Redirection::find($id);
    }

    public function save(Redirection $redirection)
    {
        $redirection->save();
    }

    public function delete(Redirection $redirection)
    {
        $redirection->delete();
    }

    public function deleteById($id)
    {
        $redirection = $this->get($id);
        if ($redirection) {
            $redirection->delete();
        }
    }
}

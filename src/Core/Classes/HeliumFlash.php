<?php

namespace Webup\LaravelHelium\Core\Classes;

use Webup\LaravelHelium\Core\Contracts\HeliumFlash as HeliumFlashContract;
use Webup\LaravelHelium\Core\Helpers\HeliumHelper;

/**
 * Helium Breadcrumb helper
 *
 * Used to push items to breadcrumb and generate view
 */
class HeliumFlash implements HeliumFlashContract
{

    public function success(string $message)
    {
        $this->pushToSession('success', $message);

        return $this;
    }

    public function warning(string $message)
    {
        $this->pushToSession('warning', $message);

        return $this;
    }

    public function info(string $message)
    {
        $this->pushToSession('info', $message);

        return $this;
    }

    public function error(string $message)
    {
        $this->pushToSession('error', $message);

        return $this;
    }

    private function pushToSession(string $level, string $message)
    {
        $flashes = session()->get("helium.notif", []);

        $flashes[] = [
            'message' => $message,
            'level' => $level,
        ];

        session()->flash('helium.notif', $flashes);
    }


    public function generate()
    {
        return view("helium::elements.notif", [
            "notifications" => session()->get("helium.notif", []),
        ]);
    }
}

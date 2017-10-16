<?php

namespace Webup\LaravelHelium\Translation;

class LocaleRepository
{
    private $locales;

    public function supportedLocales()
    {
        if (!$this->locales) {
            foreach (config('app.locales') as $code => $name) {
                $this->locales[] = (object)[
                    'code' => $code,
                    'name' => $name
                ];
            }
        }

        return $this->locales;
    }

    public function get($code)
    {
        $locales = $this->supportedLocales();
        foreach ($locales as $locale) {
            if ($locale->code == $code) {
                return $locale;
            }
        }

        return null;
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;

class EnvEditor
{

    public function updateEnvValues(array $data): bool
    {

        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return false;
        }

        $env = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $key = strtoupper($key);

            // If key exists, replace it
            if (preg_match("/^{$key}=.*/m", $env)) {
                $env = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $env);
            } else { // if key not exists, append it
                $env .= "\n{$key}=\"{$value}\"";
            }
        }

        file_put_contents($envPath, $env);

        dispatch(function() {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('config:cache');
        });


        return true;
    }

}

<?php

namespace App\Providers;

use App\Models\Contatto;
use App\Models\ContattoAbilita;
use App\Models\Gruppo;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        if (app()->environment() !== "testing"){
            //Gate basati su un ruolo
            Gruppo::all()->each(
                function (Gruppo $gruppo) {
                    Gate::define($gruppo->nome, function (Contatto $contatto) use ($gruppo){
                        return $contatto->gruppi->contains('nome', $gruppo->nome);
                    });
                }
            );

            //Gate basati su multipli ruoli
            ContattoAbilita::all()->each(function (ContattoAbilita $abilita){
                Gate::define($abilita->sku, function (Contatto $contatto) use ($abilita){
                    $check = false;
                    foreach ($contatto->gruppi as $item) {
                        if ($item->abilita->contains('sku', $abilita->sku)) {
                            $check = true;

                            break;
                        }
                    }
                    return $check;
                });
            });
        }
    }
}

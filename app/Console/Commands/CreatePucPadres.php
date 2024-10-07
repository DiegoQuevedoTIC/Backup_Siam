<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreatePucPadres extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-puc-padres';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Se crean los pucs padres';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pucs_padres = DB::table('pucs')->where('puc_padre', '!=', '')->get()->toArray();

        foreach ($pucs_padres as $puc_padre) {
            DB::table('puc_padres')->insert([
                'puc' => $puc_padre->puc_padre,
                'descripcion' => $puc_padre->descripcion,
                'naturaleza' => $puc_padre->naturaleza,
            ]);
        }
    }
}

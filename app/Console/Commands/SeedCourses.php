<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CourseController;
use Illuminate\Http\Request;

class SeedCourses extends Command
{
    protected $signature = 'courses:seed';

    public function handle()
    {
        $corsi = [
            [
                'id' => '1',
                'title' => 'Cucina Tradizionale',
                'description' => 'Impara a cucinare i piatti tipici della tradizione locale.',
                'skills' => ['Cucina', 'HACCP'],
                'duration' => '3 mesi',
                'remote' => false
            ],
            [
                'id' => '2',
                'title' => 'Sartoria Base',
                'description' => 'Le basi del cucito e della riparazione dei vestiti.',
                'skills' => ['Cucito', 'Design'],
                'duration' => '2 mesi',
                'remote' => false
            ],
            [
                'id' => '3',
                'title' => 'Informatica per tutti',
                'description' => 'Corso online per imparare le basi del computer.',
                'skills' => ['PC', 'Interenet'],
                'duration' => '1 mese',
                'remote' => true
            ],
            [
                'id' => '4',
                'title' => 'Lavorazione del legno',
                'description' => 'Costruisci piccoli mobili e oggetti in legno.',
                'skills' => ['Legno', 'Strumenti'],
                'duration' => '4 mesi',
                'remote' => false
            ],
            [
                'id' => '5',
                'title' => 'Giardinaggio Bio',
                'description' => 'Impara a gestire un orto biologico.',
                'skills' => ['Orto', 'Bio'],
                'duration' => '3 mesi',
                'remote' => false
            ]
        ];

        $controller = new CourseController();

        foreach ($corsi as $datiCorso) {
            $req = new Request($datiCorso);
            $controller->embed($req);
            $this->info("Inserito: " . $datiCorso['title']);
        }
    }
}

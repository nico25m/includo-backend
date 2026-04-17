<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CourseController extends Controller
{
    public function embed(Request $request)
    {
        $dati = $request->validate([
            'id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'skills' => 'required',
            'duration' => 'required',
            'remote' => 'required',
        ]);

        $testo1 = $dati['title'] . ". " . $dati['description'];
        $testo2 = " Skills: " . implode(', ', $dati['skills']);
        $testo3 = ". Durata: " . $dati['duration'];
        
        $testoPerEmbedding = $testo1 . $testo2 . $testo3;

        $chiaveOpenAI = config('services.openai.key');
        
        $rispostaEmbedding = Http::withToken($chiaveOpenAI)->post('https://api.openai.com/v1/embeddings', [
            'model' => 'text-embedding-3-small',
            'input' => $testoPerEmbedding,
        ]);

        $ArrayRisposta = $rispostaEmbedding->json();
        $vettore = $ArrayRisposta['data'][0]['embedding'];
        
        $competenzeUnite = implode(', ', $dati['skills']);

        $corso = Course::updateOrCreate(
            ['id' => $dati['id']],
            [
                'vector' => $vettore,
                'title' => $dati['title'],
                'description' => $dati['description'],
                'skills' => $competenzeUnite,
                'duration' => $dati['duration'],
                'remote' => $dati['remote'],
            ]
        );

        return response()->json(['status' => 'success', 'id' => $corso->id]);
    }

    public function semanticSearch($testoCercato, $quantiCorsi = 2)
    {
        $chiaveOpenAI = config('services.openai.key');

        $rispostaEmbedding = Http::withToken($chiaveOpenAI)->post('https://api.openai.com/v1/embeddings', [
            'model' => 'text-embedding-3-small',
            'input' => $testoCercato,
        ]);

        $ArrayRisposta = $rispostaEmbedding->json();
        $vettoreQuery = $ArrayRisposta['data'][0]['embedding'];

        $tuttiICorsi = Course::all();
        $risultatiConPunteggio = [];

        foreach ($tuttiICorsi as $corso) {
            $vettoreCorso = $corso->vector;
            
            if (!is_array($vettoreCorso)) {
                $vettoreCorso = json_decode($vettoreCorso, true);
            }
            
            $prodottoScalare = 0;
            $normaA = 0;
            $normaB = 0;

            $lunghezzaVettore = count($vettoreQuery);

            for ($i = 0; $i < $lunghezzaVettore; $i++) {
                $valoreQuery = $vettoreQuery[$i];
                $valoreCorso = $vettoreCorso[$i];
                
                $prodottoScalare = $prodottoScalare + ($valoreQuery * $valoreCorso);
                $normaA = $normaA + ($valoreQuery * $valoreQuery);
                $normaB = $normaB + ($valoreCorso * $valoreCorso);
            }

            if ($normaA > 0 && $normaB > 0) {
                $radiceA = sqrt($normaA);
                $radiceB = sqrt($normaB);
                $similitudine = $prodottoScalare / ($radiceA * $radiceB);
            } else {
                $similitudine = 0;
            }

            $elemento = [
                'punteggio' => $similitudine,
                'corso' => $corso
            ];
            
            array_push($risultatiConPunteggio, $elemento);
        }

        usort($risultatiConPunteggio, function($a, $b) {
            if ($a['punteggio'] == $b['punteggio']) {
                return 0;
            }
            if ($a['punteggio'] < $b['punteggio']) {
                return 1;
            } else {
                return -1;
            }
        });

        $topCorsi = array_slice($risultatiConPunteggio, 0, $quantiCorsi);
        
        $rispostaFinale = [];
        
        foreach ($topCorsi as $item) {
            $c = $item['corso'];
            
            if ($c->remote == true) {
                $testoRemoto = 'Sì';
            } else {
                $testoRemoto = 'No';
            }
            
            $corsoTrovato = [
                'title' => $c->title,
                'description' => $c->description,
                'duration' => $c->duration,
                'skills' => $c->skills,
                'remote' => $testoRemoto
            ];
            
            array_push($rispostaFinale, $corsoTrovato);
        }

        return $rispostaFinale;
    }
}

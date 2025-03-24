<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Services\ProductService;  
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SlackCommandExecuted;

class BaixarArquivosProdutos extends Command
{
    protected $signature = 'produtos:baixar-arquivos';
    protected $description = 'Baixa os arquivos JSON.GZ do Open Food Facts para storage/app/products/';

    public function __construct(
        protected readonly ProductService $productService
    ) {
        parent::__construct();
    }

    public function handle()
    {   
        try {
            $this->downloadFiles();
            $this->productService->importProducts();
    
            // registrar de ultima execução do cron
            $now = Carbon::now()->format('d/m/Y H:i:s');
            $logEntry = "Comando executado em: {$now}" . PHP_EOL;        
            Storage::disk('public')->append('logs/command.log', $logEntry);
    
            if(env('SLACK_NOTIFICATION'))
                Notification::route('slack', env('SLACK_WEBHOOK_URL'))->notify(new SlackCommandExecuted('Baixar Produtop'));

        } catch (\Throwable $th) {
            if(env('SLACK_NOTIFICATION'))
                Notification::route('slack', env('SLACK_WEBHOOK_URL'))->notify(new SlackCommandExecuted($th->getMessage()));
        }
    }

    public function downloadFiles(): void
    {  
        $this->info("Iniciando o download dos arquivos...");
        $arquivos = [
            'products_01.json.gz',
            'products_02.json.gz',
            'products_03.json.gz',
            'products_04.json.gz',
            'products_05.json.gz',
            'products_06.json.gz',
            'products_07.json.gz',
            'products_08.json.gz',
            'products_09.json.gz'
        ];

        $urlBase = env('URL_BASE_FILES_OPENFOOD');

        $pastaDestino = 'products/';
        
        Storage::disk('public')->makeDirectory($pastaDestino);

        foreach ($arquivos as $arquivo) {
            $url = $urlBase . $arquivo;
            $caminhoLocal = $pastaDestino . $arquivo;
            $this->info("Baixando: $url");

            try {
                $resposta = Http::timeout(120)->get($url);

                if ($resposta->successful()) {
                    Storage::disk('public')->put($caminhoLocal, $resposta->body());
                    $this->info("Arquivo salvo em: storage/app/$caminhoLocal");
                } else {
                    $this->error("Erro ao baixar: $url");
                }
            } catch (\Exception $e) {
                $this->error("Falha no download de $url - " . $e->getMessage());
            }
        }
        $this->info("Download concluído!");
    }
}

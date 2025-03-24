<?php 


namespace Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class StatusService
{
    private $startTime;

    public function __construct()
    {
        $this->startTime = LARAVEL_START ?? microtime(true);
    }

    public function status(): JsonResponse
    {
        $test_db = [
            'conexão' => $this->testConnection(),
            'leitura' => $this->testRead(),
            'escrita' => $this->testWrite(),
        ];

        // Tempo online
        $uptime = $this->getSystemUptime();

        // Uso de memória
        $memory = round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB';

        return response()->json([
            'banco_postgres'    => $test_db,
            'aplicacao_online'  => $uptime,
            'uso_memoria'       => $memory,
            'ultima_importacao' => $this->ultimaExecucao(),
        ]);
    }

    public function testConnection(): string 
    {   
        try {
            DB::connection()->getPdo()->getAttribute(\PDO::ATTR_CONNECTION_STATUS);
            return 'OK';
        } catch (\Exception $e) {
            $db_status = 'ERROR: ' . $e->getMessage();
        }
    }

    public function testRead(): string
    {
        try {
            DB::table('products')->first(); 
            return 'OK';
        } catch (\Exception $e) {
            $db_status = 'ERROR: ' . $e->getMessage();
        }
    }

    public function testWrite(): string
    {
        try {
            DB::beginTransaction();
        
            DB::table('products')->insert([
                'code'         => 'TESTE-123',
                'product_name' => 'Produto de Teste',
                'imported_t'   => now(),
            ]);
        
            DB::rollBack();
            return  'OK';
        } catch (\Exception $e) {
            $write_status = 'ERROR: ' . $e->getMessage();
        }
    }

    public function getSystemUptime(): string
    {
        try {
            $uptimeContent = file_get_contents('/proc/uptime');
            $uptimeSeconds = floatval(explode(' ', $uptimeContent)[0]);

            $uptimeFormatted = gmdate('H:i:s', $uptimeSeconds); // hh:mm:ss
            $uptimeDays = floor($uptimeSeconds / 86400); // dias

            return $uptimeDays > 0
                ? "{$uptimeDays}d {$uptimeFormatted}"
                : $uptimeFormatted;
        } catch (\Exception $e) {
            return 'Unavailable';
        }
    }


    public function ultimaExecucao(): string|JsonResponse
    {
        $caminhoArquivo = 'logs/command.log';
        
        if (!Storage::disk('public')->exists($caminhoArquivo)) {
            return response()->json([
                'ultima_execucao' => null,
                'mensagem' => 'Nenhuma execução registrada ainda.'
            ]);
        }

        $linhas = explode("\n", Storage::disk('public')->get($caminhoArquivo));
        $linhas = array_filter(array_map('trim', $linhas)); // remove linhas vazias e espaços
        $ultimaLinha = end($linhas);

        preg_match('/\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}:\d{2}|\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $ultimaLinha, $matches);

        return  $matches[0] ?? null;
    }
}
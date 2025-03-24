<?php 


namespace Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Services\DTO\ImportProductDTO;
use Repositories\Product\ProductRepository;

class ProductService
{
    public $countP = 0;

    public function __construct(
        public readonly ProductRepository $productRepository
    ) {
    }

    public function listProduct(): LengthAwarePaginator
    {
        return $this->productRepository->listProduct();
    }

    public function detailProduct(string $code): Product
    {
        return $this->productRepository->detailProduct($code);
    }

    public function updateProduct(string $code, array $data): Product
    {
        $data['code'] = $code;
        $this->productRepository->storeOrUpdate($data, array_keys($data));
        return $this->detailProduct($code);
    }

    public function deleteProduct(string $code): void
    {
        $this->productRepository->deleteProduct($code);
    }

    public function importProducts(): void 
    {
        $files = Storage::disk('products')->files();

        foreach ($files as $key => $file) {

            $caminhoArquivo = storage_path("app/public/products/{$file}");

            if (!file_exists($caminhoArquivo)) {
                $this->error("Arquivo não encontrado: $caminhoArquivo");
                return;
            }

            // Abre o arquivo GZ
            $gz = gzopen($caminhoArquivo, 'r');
            if (!$gz) {
                $this->error("Erro ao abrir o arquivo.");
                return;
            }

            $this->importaProduto($gz);

            gzclose($gz);
        }//end foreach

            
        info("Importação concluída! {$this->countP} produtos importados.");
  
        //Limpa novamente o diretório
        Storage::disk('products')->delete($files);
    }    


    public function importaProduto($gz): void
    {
        $count = 0;
        $limite = 100;

        while (!gzeof($gz)) {   
            $linha = gzgets($gz); // Lê uma linha por vez
            $produto = json_decode($linha, true);

            $dto = ImportProductDTO::makeFromRequest($produto);
            $dadosParaInserir[] = $dto->toDatabase();

            if ($produto !== null) {
                $count++;
                $this->countP += 1;
            }

            // Para após importar 100 produtos
            if ($count >= $limite) {
                $this->productRepository->storeOrUpdate($dadosParaInserir, array_keys($dto->toDatabase()));
                $dadosParaInserir = [];
                break;
            }
        }
    }

}
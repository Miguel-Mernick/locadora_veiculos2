<?php
    namespace Services;
    use models\{Veiculo, Carro, Moto};

    // classe para gerenciar a locadora
    class Locadora {
        private array $veiculos = [];

        // método construtor
        public function __construct() {
            $this->carregarVeiculos();
        }

        private function carregarVeiculos(): void {

            // verifica se o arquivo existe
            if (file_exists(ARQUIVO_JSON)) {
                // lê o conteudo e decodifica o JSON para o array
                $dados = json_decode(file_get_contents(ARQUIVO_JSON), true);
                foreach ($dados as $dado){

                    if($dado['tipo']=== 'Carro'){
                        $veiculo = new Carro($dado['modelo'], $dado['placa']);
                    }else{
                        $veiculo = new Moto($dado['modelo'], $dado['placa']);
                    }
                    $veiculo->setDisponivel($dado['disponivel']);

                    $this->veiculos[] = $veiculo;
                }
            } 
        }
        // salvar os veiculos
        private function salvarVeiculos(): void {
            $dados = [];

            foreach($this->veiculos as $veiculo){
                $dados[] = [
                    'tipo' => ($veiculo instanceof Carro) ? 'Carro' : 'Moto',
                    'modelo' => $veiculo->getModelo(),
                    'placa' => $veiculo->getPlaca(),
                    'disponivel' => $veiculo->isDisponivel()
                ];

                $dir = dirname(ARQUIVO_JSON);
                // verifica se o diretório existe
                if (!is_dir($dir)) {
                    // cria o diretório se não existir
                    mkdir($dir, 0777, true);
                }

            }
            // salva o array de veículos no arquivo JSON
            file_put_contents(ARQUIVO_JSON, json_encode($dados, JSON_PRETTY_PRINT));
        }
            public function adicionarVeiculo(Veiculo $veiculo): void {
                $this->veiculos[] = $veiculo;
                $this->salvarVeiculos();
            }
            //remover veiculo
            public function removerVeiculo(string $placa): void {
                foreach ($this->veiculos as $key => $veiculo) {
                    if ($veiculo->getPlaca() === $placa) {
                        unset($this->veiculos[$key]);
                        $this->salvarVeiculos();
                        return;
                    }
                }
            }

            //alugar veiculo
            public function alugarVeiculo(string $placa): string {
                foreach ($this->veiculos as $veiculo) {
                    if ($veiculo->getPlaca() === $placa) {
                        return $veiculo->alugar();
                    }
                }
                return "Veículo não encontrado.";
            }

            //devolver veiculo
            public function devolverVeiculo(string $placa): string {
                foreach ($this->veiculos as $veiculo) {
                    if ($veiculo->getPlaca() === $placa) {
                        return $veiculo->devolver();
                    }
                }
                return "Veículo não encontrado.";
            }
            //retornar veiculos
            public function getVeiculos(): array {
                return $this->veiculos;
            }
            //calcular aluguel
            public function calcularAluguel(string $placa, int $dias): float {
                foreach ($this->veiculos as $veiculo) {
                    if ($veiculo->getPlaca() === $placa) {
                        return $veiculo->calcularAluguel($dias);
                    }
                }
                return 0.0;
            }
    }
?>
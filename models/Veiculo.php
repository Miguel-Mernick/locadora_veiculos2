<?php
 namespace Models;

 // Classe abstrata para todos os tipos de veículos

 abstract class Veiculo {
    protected string $modelo;
    protected string $placa;
    protected bool $disponivel;

    public function __construct (string $modelo, string $placa){
        $this -> modelo = $modelo;
        $this -> placa = $placa;
        $this -> disponivel = true;
    }

    // Função para cálculo de aluguel
    abstract public function calcularAluguel(int $dias) : float;

    public function isDisponivel():bool {
        return $this->disponivel;
    }
    public function getModelo(): string{
        return $this->modelo;
    }
    public function getPlaca(): string{
        return $this->placa;
    }
    public function setDisponivel(bool $disponivel):void{
        $this->disponivel = $disponivel;
    }
 }
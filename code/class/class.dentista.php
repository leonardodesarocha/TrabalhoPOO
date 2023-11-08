<?php
include_once "class.profissional.php";
include_once "class.especialidade.php";
class Dentista extends Profissional
{
    public string $cro;
    public $especialidades = array();

    public function __construct(string $nome, string $email, string $telefone, string $cpf, string $endereco, string $cro, array $especialidade)
    {
        parent::__construct($nome, $email, $telefone, $cpf, $endereco);
        $this->cro = $cro;
        $this->especialidades = $especialidade;
    }

    public function getCro()
    {
        return $this->cro;
    }
    public function getEspecialidades()
    {
        return $this->especialidades;
    }

    public function setCro($cro)
    {
        $this->cro =  $cro;
    }
    public function setEspecialidade($especialidade)
    {
        $this->especialidades = $especialidade;
    }

    public function adicionarEspecialidade(Especialidade $especialidade)
    {
       array_push($this->especialidades, $especialidade);
    }
}

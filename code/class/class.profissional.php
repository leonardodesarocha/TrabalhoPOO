<?php
include_once "class.pessoa.php";
include_once "class.endereco.php";
include_once "class.usuario.php";
class Profissional extends Pessoa
{
    protected string $cpf;
    protected  Endereco $endereco;
    protected  Usuario $usuario;
    static $local_filename = "profissionais.txt";

    public function __construct(string $nome, string $email, string $telefone, string $cpf, Endereco $endereco, Usuario $usuario)
    {
        parent::__construct($nome, $email, $telefone);
        $this->cpf = $cpf;
        $this->endereco = $endereco;
        $this->usuario = $usuario;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }
}

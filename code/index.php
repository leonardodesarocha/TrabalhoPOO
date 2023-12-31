<?php

include_once "class/class.dentistaparceiro.php";
include_once "class/class.dentistafuncionario.php";
include_once "class/class.cliente.php";
include_once "class/class.paciente.php";
include_once "class/class.secretaria.php";
include_once "class/class.auxiliar.php";
include_once "class/class.formaPagamento.php";
include_once "class/class.perfil.php";
include_once "class/class.usuario.php";
include_once "class/persist.php";
include_once "class/container.php";
include_once "class/class.consultaavaliacao.php";
include_once "class/class.funcionalidadesSistema.php";
require_once "global.php";


//PROVISORIO
$perfil_adm = new Perfil(
    "perfilTeste",
    [
        "cadastrarDentistaParceiro",
        "cadastrarDentistaFuncionario",
        "cadastrarProcedimento",
        "cadastrarFormaPagamento",
        "cadastrarPaciente",
        "cadastrarEspecialidade",
        "cadastrarPagamentoDoTratamento",
        "cadastrarCliente",
        "marcarConsultaAvaliacao",
        "marcarConsultaExecucao",
        "cadastrarOrcamento",
        "aprovarOrcamento",
        "calcularResultadoMensal",
        "selecionarConsultasAvaliacao"
    ]
);

$perfil_restricao = new Perfil(
    "perfilTeste",
    [
        "cadastrarDentistaParceiro",
        "cadastrarDentistaFuncionario",
        "cadastrarFormaPagamento",
        "cadastrarPaciente",
        "cadastrarEspecialidade",
        "cadastrarPagamentoDoTratamento",
        "cadastrarCliente",
        "marcarConsultaAvaliacao",
        "marcarConsultaExecucao",
        "cadastrarOrcamento",
        "aprovarOrcamento",
        "calcularResultadoMensal",
        "selecionarConsultasAvaliacao"
    ]
);

$funcionalidades_sistema = new FuncionalidadesSistema();

//Criação usuário 1 com perfil com acesso a todas menos Cadastrar Procedimento
$usuario_restricao = new Usuario("usuario1@gmail.com", "loginrest", "senha321", $perfil_restricao);
//$usuario_restricao->save();

//Criação usuário 2 com acesso a todas as funcionalidades
$usuario_adm = new Usuario("usuario2@gmail.com", "loginadm", "senha123", $perfil_adm);
//$usuario_adm->save();

echo "Teste 1: Acesso funcionalidade sem login \n";
//Acesso funcionalidade sem login
//resultado esperado -> exceção
$funcionalidades_sistema->cadastrarProcedimento("Limpeza", 200, "");
echo "\n";

echo "Teste 2: Login com sucesso do usuario com restricao \n";
//Login usuário 1
GerenciaLogin::Login("loginrest", "senha321");
echo "\n";

echo "Teste 3: Cadastro de procedimento sem permissao \n";
//Cadastro de procedimento com usuário 1
//resultado esperado -> exceção
$funcionalidades_sistema->cadastrarProcedimento("Limpeza", 200, "");
$procedimentos_cadastrados = Procedimento::getRecords();
echo "Array de procedimentos:";
print_r($procedimentos_cadastrados);

echo "\n";

echo "\nTeste 4: Logout \n";
//Logout usuário 1
GerenciaLogin::Logout();
echo "\n";

echo "Login usuario que pode tudo \n";
//Login usuário 2
GerenciaLogin::Login("loginadm", "senha123");
echo "\n";

echo "\n";
echo "Teste 5: Cadastro dos procedimentos com usuario com permissao \n";
//cria procedimentos (string $nome, float $valor, string $descricao)
$funcionalidades_sistema->cadastrarProcedimento("Limpeza", 200, "");
$funcionalidades_sistema->cadastrarProcedimento("Restauração", 185, "");
$funcionalidades_sistema->cadastrarProcedimento("Extração Comum", 280, "Não inclui dente siso.");
$funcionalidades_sistema->cadastrarProcedimento("Canal", 800, "");
$funcionalidades_sistema->cadastrarProcedimento("Extração de Siso", 400, "Valor por dente.");
$funcionalidades_sistema->cadastrarProcedimento("Clareamento a laser", 1700, "");
$funcionalidades_sistema->cadastrarProcedimento("Clareamento de moldeira", 900, "Clareamento caseiro.");
echo "\n";

$procedimentos_cadastrados = Procedimento::getRecords();
echo "Número de procedimentos cadastrados: " . count($procedimentos_cadastrados) . "\n \n";
// print_r($procedimentos_cadastrados);
// echo "\n";
// echo "\n";

echo "Teste 6: Cadastros das especialidades\n";
//cria especialidades (string $nome, array<Procedimentos> $procedimentospermitidos)
$funcionalidades_sistema->cadastrarEspecialidade(
    "Clínica Geral",
    [
        Procedimento::getRecordsByField("nome", "Limpeza")[0],
        Procedimento::getRecordsByField("nome", "Restauração")[0],
        Procedimento::getRecordsByField("nome", "Extração Comum")[0]
    ]
);
$funcionalidades_sistema->cadastrarEspecialidade(
    "Endodontia",
    [Procedimento::getRecordsByField("nome", "Canal")[0]]
);
$funcionalidades_sistema->cadastrarEspecialidade(
    "Cirurgia",
    [Procedimento::getRecordsByField("nome", "Extração de Siso")[0]]
);
$funcionalidades_sistema->cadastrarEspecialidade(
    "Estética",
    [
        Procedimento::getRecordsByField("nome", "Clareamento a laser")[0],
        Procedimento::getRecordsByField("nome", "Clareamento de moldeira")[0]
    ]
);

$especialidades_cadastradas = Especialidade::getRecords();
echo "Número de especialidades cadastradas: " . count($especialidades_cadastradas) . "\n \n";
// print_r($especialidades_cadastradas);
// echo "\n";
// echo "\n";

echo "Teste 7: Cadastro das formas de pagamento\n";
//Cria objetos das formas de pagamento
$funcionalidades_sistema->cadastrarFormaPagamento("Dinheiro à vista", 0, 0);
$funcionalidades_sistema->cadastrarFormaPagamento("Pix", 0, 0);
$funcionalidades_sistema->cadastrarFormaPagamento("Débito", 0, 0.03);
$funcionalidades_sistema->cadastrarFormaPagamento("Crédito de 1x", 1, 0.04);
$funcionalidades_sistema->cadastrarFormaPagamento("Crédito de 2x", 2, 0.04);
$funcionalidades_sistema->cadastrarFormaPagamento("Crédito de 3x", 3, 0.04);
$funcionalidades_sistema->cadastrarFormaPagamento("Crédito de 4x", 4, 0.07);
$funcionalidades_sistema->cadastrarFormaPagamento("Crédito de 5x", 5, 0.07);
$funcionalidades_sistema->cadastrarFormaPagamento("Crédito de 6x", 6, 0.07);
echo "\n";

$formas_pagamento_cadastradas = FormaPagamento::getRecords();
echo "Número de formas de pagamento cadastradas: " . count($formas_pagamento_cadastradas) . "\n \n";
// print_r($formas_pagamento_cadastradas);
// echo "\n";
// echo "\n";


echo "Teste 8: Cadastro de dentista funcionário\n";
//Dentista funcionario  (string $nome, string $email, string $telefone, string $cpf, Endereco $endereco, string $cro, array $especialidade, float $salario, Usuario $usuario)
$funcionalidades_sistema->cadastrarDentistaFuncionario(
    "Ana Oliveira",
    "ana@example.com",
    "9876543210",
    "98765432109",
    new Endereco("Rua dos Flores", "Bairro Primavera", "456", "54321-987", "Cidade Alegre", "Estado AA", "Apto 202"),
    "98765432",
    [Especialidade::getRecordsByField("nome", "Clínica Geral")[0], Especialidade::getRecordsByField("nome", "Endodontia")[0], Especialidade::getRecordsByField("nome", "Cirurgia")[0]],
    5000,
    $usuario_adm
);
echo "\n";

$dentistas_funcionarios_cadastrados = DentistaFuncionario::getRecords();
echo "Número de dentistas funcionarios cadastrados:" . count($dentistas_funcionarios_cadastrados) . "\n \n";
// print_r($dentistas_funcionarios_cadastrados);
// echo "\n";
// echo "\n";

//Cria dentistas parceiros (string $nome, string $email, int $telefone, string $cpf, Endereco $endereco, string $cro, array<Especialidades> $especialidades)
echo "Teste 9: Cadastro de dentista parceiro\n";
$funcionalidades_sistema->cadastrarDentistaParceiro(
    "Carlos Silva",
    "carlos@example.com",
    "1112223333",
    "11122233344",
    new Endereco("Rua A", "Bairro X", "123", "12345-678", "Cidade A", "Estado AA", "Apto 101"),
    "23123123",
    [Especialidade::getRecordsByField("nome", "Clínica Geral")[0], Especialidade::getRecordsByField("nome", "Estética")[0]],
    $usuario_adm,
    ["Clínica Geral" => 0.4, "Estética" => 0.4]
);

$dentistas_parceiros_cadastrados = DentistaParceiro::getRecords();
echo "Número de dentistas parceiros cadastrados:" . count($dentistas_parceiros_cadastrados) . "\n \n";
// print_r($dentistas_parceiros_cadastrados);
// echo "\n";
// echo "\n";

echo "Teste 10: Cadastro de cliente\n";
//Cadastra cliente responsável financeiro do paciente
$funcionalidades_sistema->cadastrarCliente(
    "John Smith",
    "johnsmith@example.com",
    "1234567890",
    "123456789",
    "12345678901"
);

$clientes_cadastrados = Cliente::getRecords();
echo "Número de clientes cadastrados:" . count($clientes_cadastrados) . "\n \n";
// print_r($clientes_cadastrados);
// echo "\n";
// echo "\n";

//print_r (Cliente::getRecordsByField("cpf", "12345678901"));

echo "Teste 11: Cadastro de Paciente\n";
//Cadastra paciente
$funcionalidades_sistema->cadastrarPaciente(
    "Bob Smith",
    "bob@example.com",
    "5556667777",
    "9876543",
    new DateTime("1985-08-22"),
    Cliente::getRecordsByField("cpf", "12345678901")[0]
);

$pacientes_cadastrados = Paciente::getRecords();
echo "Número de Pacientes cadastrados:" . count($pacientes_cadastrados) . "\n \n";
// print_r($pacientes_cadastrados);
// echo "\n";
// echo "\n";



//Agendamento de uma consulta de avaliação
echo "Teste 12: Marcar consulta de avaliação\n";
$funcionalidades_sistema->marcarConsultaAvaliacao(
    Paciente::getRecordsByField("rg", "9876543")[0],
    DentistaParceiro::getRecordsByField("cpf", "11122233344")[0],
    new DateTime("2023-11-06 14:00")
);

$consultas_avaliacao_cadastradas = ConsultaAvaliacao::getRecords();
echo "Número de consultas de avaliação cadastradas:" . count($consultas_avaliacao_cadastradas) . "\n \n";
// print_r($consultas_avaliacao_cadastradas);
// echo "\n";
// echo "\n";

echo "Teste 13: Cadastro de Orçamento\n";
//Criação de um orçamento a partir de uma consulta de avaliação
$funcionalidades_sistema->cadastrarOrcamento(
    1,
    new DateTime("2023-11-06 14:00"),
    [
        Procedimento::getRecordsByField("nome", "Limpeza")[0],
        Procedimento::getRecordsByField("nome", "Clareamento a laser")[0],
        Procedimento::getRecordsByField("nome", "Restauração")[0],
        Procedimento::getRecordsByField("nome", "Restauração")[0]
    ],
    $funcionalidades_sistema->selecionarConsultasAvaliacao(Paciente::getRecordsByField("rg", "9876543")[0], new DateTime("2023-11-06 14:00")),

);

$orcamentos_cadastrados = Orcamento::getRecords();
echo "Número de orçamentos cadastrados:" . count($orcamentos_cadastrados) . "\n \n";
// print_r($orcamentos_cadastrados);
// echo "\n";
// echo "\n";

echo "Teste 14: Cadastro de tratamento\n";
//Criação de um tratamento a partir da aprovação do orçamento
$funcionalidades_sistema->aprovarOrcamento(
    1,
    FormaPagamento::getRecordsByField("nome_forma_pagamento", "Pix")[0]
);

$tratamentos_cadastrados = Tratamento::getRecords();
echo "Número de tratamentos cadastrados:" . count($tratamentos_cadastrados) . "\n \n";
// print_r($tratamentos_cadastrados);
// echo "\n";
// echo "\n";

echo "Teste 15: Cadastro de consulta de execução\n";
// //Agendamento das consultas de realização
$funcionalidades_sistema->marcarConsultaExecucao(
    2,
    1,
    DentistaParceiro::getRecordsByField("cpf", "11122233344")[0],
    new DateTime("2023-12-05 15:00"),
    "30 minutos",
    Procedimento::getRecordsByField("nome", "Limpeza")[0]
);

$funcionalidades_sistema->marcarConsultaExecucao(
    2,
    2,
    DentistaParceiro::getRecordsByField("cpf", "11122233344")[0],
    new DateTime("2023-12-12 09:00"),
    "30 minutos",
    Procedimento::getRecordsByField("nome", "Clareamento a laser")[0]
);

$funcionalidades_sistema->marcarConsultaExecucao(
    2,
    3,
    DentistaParceiro::getRecordsByField("cpf", "11122233344")[0],
    new DateTime("2023-12-20 17:00"),
    "60 minutos",
    Procedimento::getRecordsByField("nome", "Restauração")[0]
);

$funcionalidades_sistema->marcarConsultaExecucao(
    2,
    4,
    DentistaParceiro::getRecordsByField("cpf", "11122233344")[0],
    new DateTime("2024-01-03 14:00"),
    "60 minutos",
    Procedimento::getRecordsByField("nome", "Restauração")[0]
);

$consultas_execucao_cadastradas = ConsultaExecucao::getRecords();
echo "Número de consultas de execução cadastradas:" . count($consultas_execucao_cadastradas) . "\n \n";
// print_r($consultas_execucao_cadastradas);
// echo "\n";
// echo "\n";

echo "Teste 16: Cadastro de pagamentos\n";
// //Adiciona os pagamentos
$funcionalidades_sistema->cadastrarPagamentoDoTratamento(
    2,
    FormaPagamento::getRecordsByField("nome_forma_pagamento", "Pix")[0],
    0.5 * Tratamento::getRecordsByField("id", "2")[0]->calculaValorTotal(),
    [new DateTime("2023-11-03 14:00")],
    $funcionalidades_sistema->getImpostoDaClinica()
);

$funcionalidades_sistema->cadastrarPagamentoDoTratamento(
    2,
    FormaPagamento::getRecordsByField("nome_forma_pagamento", "Crédito de 3x")[0],
    (0.5 * Tratamento::getRecordsByField("id", "2")[0]->calculaValorTotal()),
    [new DateTime("2023-11-15 14:00"), new DateTime("2023-12-15 14:00"), new DateTime("2024-01-15 14:00")],
    $funcionalidades_sistema->getImpostoDaClinica()
);

echo "\n";
$pagamentos_cadastrados = Pagamento::getRecords();
echo "Número de pagamentos cadastrados:" . count($pagamentos_cadastrados) . "\n \n";
// print_r($pagamentos_cadastrados);
// echo "\n";
// echo "\n";

echo "Teste 17: Cálculo do resultado mensal da clínica\n";
//Calcular resultado mensal da clínica em novembro 2023
echo "Resultado mensal: " . $funcionalidades_sistema->calcularResultadoMensal(new DateTime("2023-11-01"), new DateTime("2023-11-30"));
echo "\n";

GerenciaLogin::Logout();

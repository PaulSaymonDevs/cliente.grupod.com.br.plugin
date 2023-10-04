<?php
/*
Plugin Name: Meu Plugin
Description: Este é um exemplo de um plugin personalizado para o WordPress.
Version: 1.0
Author: Paul Saymon
*/
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once($_SERVER['DOCUMENT_ROOT'] .'/cursos/wp-config.php');

require_once($_SERVER['DOCUMENT_ROOT'] .'/cursos/wp-load.php');

require_once($_SERVER['DOCUMENT_ROOT'] .'/cursos/wp-content/plugins/forminator/forminator.php');

//capturar_dados_do_formulario();
function capturar_dados_do_formulario($entry_id, $form_id) {
    global $wpdb;
    $tabela = $wpdb->prefix . 'pluginWordpress2';

    if($entry_id == 9138){
        //CADASTRO PF

        $nome = sanitize_text_field($_POST['text-1']);
        $cpf = sanitize_text_field($_POST['number-1']);
        $email = sanitize_text_field($_POST['email-1']);
        $site = sanitize_text_field($_POST['url-1']);
        $password = sanitize_text_field($_POST['password-1']);
        $celular = sanitize_text_field($_POST['phone-1']);
        $telefone = sanitize_text_field($_POST['phone-2']);
        $genero = sanitize_text_field($_POST['radio-1']);
        $endereco = sanitize_text_field($_POST['address-1']);
        $logradouro = sanitize_text_field($_POST['text-2']);
        $numero = sanitize_text_field($_POST['number-2']);
        $rua = sanitize_text_field($_POST["address-1-street_address"]);
        $complemento = sanitize_text_field($_POST["address-1-address_line"]);
        $bairro = sanitize_text_field($_POST["text-3"]);
        $cidade = sanitize_text_field($_POST["address-1-city"]);
        $estado = sanitize_text_field($_POST["address-1-state"]);
        $cep = sanitize_text_field($_POST["address-1-zip"]);
        
        $wpdb->insert($tabela, array('nome' => $entry_id,'email' => $nome,), array('%s', '%s'));

        inserir_cadastro_pf_api($nome, $cpf, $email, $site, $telefone, $celular, $cep, $logradouro,
        $numero, $complemento, $$bairro, $cidade, $estado);

    }else if($entry_id == 9170){
        //CADASTRO PJ

        $razaoSocial = sanitize_text_field($_POST['text-1']);
        $cnpj = sanitize_text_field($_POST['number-1']);
        $inscricaoEstadual = sanitize_text_field($_POST['number-2']);
        $fantasia = sanitize_text_field($_POST['name-1']);
        $email = sanitize_text_field($_POST['email-1']);
        $site = sanitize_text_field($_POST['url-1']);
        $password = sanitize_text_field($_POST['password-1']);
        $celular = sanitize_text_field($_POST['phone-1']);
        $telefone = sanitize_text_field($_POST['phone-2']);
        $endereco = sanitize_text_field($_POST['address-1']);
        $logradouro = sanitize_text_field($_POST['text-2']);
        $numero = sanitize_text_field($_POST['number-2']);
        $rua = sanitize_text_field($_POST["address-1-street_address"]);
        $complemento = sanitize_text_field($_POST["address-1-address_line"]);
        $bairro = sanitize_text_field($_POST["text-3"]);
        $cidade = sanitize_text_field($_POST["address-1-city"]);
        $estado = sanitize_text_field($_POST["address-1-state"]);
        $cep = sanitize_text_field($_POST["address-1-zip"]);

        $wpdb->insert($tabela, array('nome' => $entry_id,'email' => $fantasia,), array('%s', '%s'));

        inserir_cadastro_pj_api($razaoSocial, $cnpj, $inscricaoEstadual, $fantasia, $email, $site, $password, $telefone, $celular, $cep, $logradouro,
        $numero, $complemento, $bairro, $cidade, $estado);

    }else if($entry_id == 1502){
        //LOGIN

        $userORemail = sanitize_text_field($_POST['text-1']);
        $senha = sanitize_text_field($_POST['password-1']);

        $wpdb->insert($tabela, array('nome' => $entry_id,'email' => $userORemail,), array('%s', '%s'));
    } 

}

function inserir_cadastro_pf_api($nomeUsuario, $cpfusuario, $email,
    $site, $telefone, $celular, $cep, $logradouro, $numero, $complemento, $bairro,
    $cidade, $estado){
    //CADASTRO PF
    
    $tipopessoa = 1;
    $token = getToken();

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.abntonline.com.br/cursoapi/cadastro.aspx',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
        "pessoafisica": {
            "nome": "'.$nomeUsuario.'",
            "cpf": "'.$cpfusuario.'",
            "sexo": 0
        },
        "pessoajuridica":{
            "razaosocial":"",
            "cnpj":"",
            "inscricaoestadual":"",
            "inscricaoestadual ":"",
            "fantasia":""
        },
        "id": 0,
        "tipo":'.$tipopessoa.', 
        "email":"'.$email.'",
        "site":"'.$site.'",
        "telefone":"'.$telefone.'",
        "celular":"'.$celular.'",
        "cep":"'.$cep.'",
        "logradouro":"'.$logradouro.'",
        "numero":"'.$numero.'",
        "complemento":"'.$complemento.'",
        "bairro":"'.$bairro.'",
        "cidade":"'.$cidade.'",
        "estado":"'.$estado.'"
    }',
    CURLOPT_HTTPHEADER => array(
        'token: '.$token.'',
        'operacao: INSERT',
        'Content-Type: application/json',
        'Authorization: Bearer '.$token.'',
        'Cookie: ASP.NET_SessionId=ioqp41bxnghwk0jeyh1tjuvp'
    ),
    ));

    $response = json_decode(curl_exec($curl));
    registra_log_cadastro_pf($response);

    curl_close($curl);
}

function inserir_cadastro_pj_api($razaoSocial, $cnpj, $inscricaoEstadual, $fantasia, $email, $site, $password, $telefone, $celular, $cep, $logradouro,
$numero, $complemento, $bairro, $cidade, $estado){
    $tipopessoa = 2;
    $token = getToken();

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.abntonline.com.br/cursoapi/cadastro.aspx',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
        "pessoafisica": {
            "nome": "",
            "cpf": "",
            "sexo": 0
        },
        "pessoajuridica":{
            "razaosocial":"'.$razaoSocial.'",
            "cnpj":"'.$cnpj.'",
            "inscricaoestadual":"'.$inscricaoEstadual.'",
            "inscricaoestadual ":"",
            "fantasia":"'.$fantasia.'"
        },
        "id": 0,
        "tipo":'.$tipopessoa.', 
        "email":"'.$email.'",
        "site":"'.$site.'",
        "telefone":"'.$telefone.'",
        "celular":"'.$celular.'",
        "cep":"'.$cep.'",
        "logradouro":"'.$logradouro.'",
        "numero":"'.$numero.'",
        "complemento":"'.$complemento.'",
        "bairro":"'.$bairro.'",
        "cidade":"'.$cidade.'",
        "estado":"'.$estado.'"
    }',
    CURLOPT_HTTPHEADER => array(
        'token: '.$token.'',
        'operacao: INSERT',
        'Content-Type: application/json',
        'Authorization: Bearer '.$token.'',
        'Cookie: ASP.NET_SessionId=ioqp41bxnghwk0jeyh1tjuvp'
    ),
    ));

    $response = json_decode(curl_exec($curl));
    registra_log_cadastro_pj($response);

    curl_close($curl);
}

function registra_log_cadastro_pf($response_json){
    global $wpdb;
    $tabela = $wpdb->prefix . 'log_cadastro_pf';

    $wpdb->insert($tabela, array
        (
        'idMensagem' => $response_json->{"retorno_codigo"},
        'mesangem' => $response_json->{"retorno_mensagem"},
        )
        , array('%s', '%s'));
}

function registra_log_cadastro_pj($response_json){
    global $wpdb;
    $tabela = $wpdb->prefix . 'log_cadastro_pf';

    $wpdb->insert($tabela, array
        (
        'idMensagem' => $response_json->{"retorno_codigo"},
        'mesangem' => $response_json->{"retorno_mensagem"},
        )
        , array('%s', '%s'));
}


/* CRIAR TABELAS PERSONALIZADAS NO BANCO DE DADOS MYSQL */

function criar_tabela_personalizada_pj() {
    global $wpdb;

    $nome_tabela = $wpdb->prefix . 'cadastro_pj';

    $sql = "CREATE TABLE $nome_tabela (
        id INT NOT NULL AUTO_INCREMENT,
        razaoSocial VARCHAR(255) NOT NULL,
        cnpj VARCHAR(255) NOT NULL,
        inscricaoEstadual VARCHAR(255) NOT NULL,
        fantasia VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        site_ VARCHAR(255) NOT NULL,
        password_ VARCHAR(255) NOT NULL,
        celular VARCHAR(255) NOT NULL,
        telefone VARCHAR(255) NOT NULL,
        genero VARCHAR(255) NOT NULL,
        endereco VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    )";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function criar_tabela_personalizada_pf() {
    global $wpdb;

    $nome_tabela = $wpdb->prefix . 'cadastro_pf';

    $sql = "CREATE TABLE $nome_tabela (
        id INT NOT NULL AUTO_INCREMENT,
        nome VARCHAR(255) NOT NULL,
        cpf VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        site_ VARCHAR(255) NOT NULL,
        password_ VARCHAR(255) NOT NULL,
        celular VARCHAR(255) NOT NULL,
        telefone VARCHAR(255) NOT NULL,
        genero VARCHAR(255) NOT NULL,
        endereco VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    )";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function criar_tabela_login() {
    global $wpdb;

    $nome_tabela = $wpdb->prefix . 'login';

    $sql = "CREATE TABLE $nome_tabela (
        id INT NOT NULL AUTO_INCREMENT,
        usuarioeemail VARCHAR(255) NOT NULL,
        senha VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    )";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function criar_tabela_log_cadastro_pf(){
    global $wpdb;

    $nome_tabela = $wpdb->prefix . 'log_cadastro_pf';

    $sql = "CREATE TABLE $nome_tabela (
        id INT NOT NULL AUTO_INCREMENT,
        idMensagem VARCHAR(255) NOT NULL,
        mesangem VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    )";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function criar_tabela_log_cadastro_pj(){
    global $wpdb;

    $nome_tabela = $wpdb->prefix . 'log_cadastro_pj';

    $sql = "CREATE TABLE $nome_tabela (
        id INT NOT NULL AUTO_INCREMENT,
        idMensagem VARCHAR(255) NOT NULL,
        mesangem VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    )";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/*FUNÇÔES PARA CRIAR TABELAS PERSONALIZADAS NO WORDPRESS */
//criar_tabela_personalizada_pj();
//criar_tabela_personalizada_pf();
//criar_tabela_login();
//criar_tabela_log_cadastro_pf();
//criar_tabela_log_cadastro_pj();
/* FIM CRIAÇÂO TABELAS PERSONALIZADAS */


/* FUNÇÔES TRABALHA DIRETO API */
function getToken(){
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.abntonline.com.br/cursoapi/token.aspx',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'PublicKey: C4P4C1T4C40_abnt_Pg5Q7k65',
        'SecretKey: Q5g81X6jaM0p44Wn3@tF77Az2$bY9.=',
        'Cookie: ASP.NET_SessionId=goq5o3101lwva4sav41bikxz',
        "Accept: application/json"
    ),
    ));

    $response = json_decode(curl_exec($curl));

    curl_close($curl);
    return ($response->{"token"});
}
/* FIM FUNÇÔES DIRETO API */


add_action('forminator_form_after_save_entry', 'capturar_dados_do_formulario', 10, 2);

?>
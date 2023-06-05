<?php
    //include_once 'sessao.php';

    include_once "doc_HTML.php";

    $curl = curl_init();

    // Configurando a conexão:
        $chave_acesso = "Abre-de-Cézamo";
        curl_setopt($curl, CURLOPT_URL, "https://api.mercadopago.com/?key=".$chave_acesso);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    // Passa as informações da compra....
        $compra = array();
        $compra["XP_Encomenda"] = 1;
        $compra["Produto"] = $P_Nome;
        $compra["Preco"] = $P_Preco;
        $compra["Pagamento"] = "Visa ou PIX ou Boleto ou....";
        curl_setopt($curl, CURLOPT_POSTFIELDS, $compra);
    
    $json_str = k_url( "http://pagfacil.profricardoms.repl.co/registrando.php?key=".$chave_acesso );
    $json_obj = json_decode($json_str, true);

    $conteudo .= "<h3> Dados Recebidos do PagFacil </h3><hr>";

    if ( $json_obj["status"] == "OK" )
    {
        $conteudo .= "Compra realizada com Sucesso!";
    }
    else
    {
        $conteudo .= "Erro no processamento da Encomenda.";
    }
    
    echo Monta_Doc_HTML( basename(__FILE__), $conteudo );

    function k_url( $p_URL )
    {
        $resposta = file_get_contents($p_URL);
        $resposta = str_replace("\n", '', $resposta);
        $resposta = str_replace("\r", '', $resposta);
        $resposta = str_replace(" ", '', $resposta);
        return $resposta;
    }
?>

<?php
    include_once 'sessao.php';

    include_once "controle_bd.php";
    include_once "doc_HTML.php";
    include_once "bd_produto.php";

    $xave = ( array_key_exists("produto", $_GET) ? $_GET["produto"] : 0 );

    $BD = BD_Conectar();        
    $produto = P_Detalhar( $BD, $xave );
    BD_Desconectar($BD);
    
    echo Monta_Doc_HTML( basename(__FILE__), $produto );

?>
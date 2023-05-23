<?php
    //include_once 'sessao.php';

    include_once "doc_HTML.php";
    
    $conteudo = "Página Inicial";
    
    echo Monta_Doc_HTML( basename(__FILE__), $conteudo );

?>

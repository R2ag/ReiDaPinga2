<?php 
include_once "controle_bd.php";

// **************************************************************************************
function E_Inserir( $p_Conexao, $DADOS = [] )
{
    $sql = "INSERT INTO Encomenta ( XE_Cliente, XE_Produto, E_Codigo, E_Data_Encomenda ) 
            VALUES ( :cliente, :produto, :codigo, :data );";
    
    $comando = $p_Conexao->prepare( $sql );

    $xp_cliente = $_SESSION['SES_Login'];
    $codigo     = random_int( 1000, 9999 );
    $data       = date('Y-m-d');

    $comando->bindValue(':cliente', $xp_cliente, SQLITE3_INTEGER);
    
    //$comando->bindValue(':produto', $DADOS["XP_Produto"], SQLITE3_INTEGER);
    $comando->bindValue(':nome_prod', $DADOS["produto"], SQLITE3_TEXT);
    
    $comando->bindValue(':codigo', $codigo, SQLITE3_TEXT);
    $comando->bindValue(':data', $data, SQLITE3_TEXT);
    
    // Execute statement
    $comando->execute();

}

// **************************************************************************************
function E_Consultar( $p_Conexao )
{
	$REGISTROS = $p_Conexao->query("SELECT * FROM Encomenda;");

	$listagem = "<h1>Encomendas</h1>";
	
	foreach ($REGISTROS as $registro) 
	{ 
		$listagem .= '<pre>' . print_r($registro, true) . '</pre><hr>';
	}

	return $listagem;
	
}

?>
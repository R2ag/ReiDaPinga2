<?php 
include_once "controle_bd.php";

// **************************************************************************************
function E_Inserir( $p_Conexao, $DADOS = [] )
{
    $sql = "INSERT INTO Encomenda ( XE_Cliente, XE_Produto, E_Codigo, E_Data_Encomenda, E_Pagamento ) 
            VALUES ( :cliente, :produto, :codigo, :data, :pagamento );";
    
    $comando = $p_Conexao->prepare( $sql );

    $xp_cliente = $_SESSION['SES_Login'];
    $codigo     = random_int( 1000, 9999 );
    $data       = date('Y-m-d');

    $comando->bindValue(':cliente', $xp_cliente, SQLITE3_INTEGER);    
    $comando->bindValue(':produto', $DADOS["produto"], SQLITE3_INTEGER);    
    $comando->bindValue(':codigo', $codigo, SQLITE3_TEXT);
    $comando->bindValue(':data', $data, SQLITE3_TEXT);
    $comando->bindValue(':pagamento', $DADOS["pagamento"], SQLITE3_TEXT);
    
    // Execute statement
    $comando->execute();

}

// **************************************************************************************
function E_Consultar( $p_Conexao )
{
    $sql = "";
    
    $sql .= "select * from Encomenda ";
    $sql .= "left join Produto on XE_Produto = XP_Produto ";
    $sql .= "left join Cliente on XE_Cliente = XP_Cliente ";
    $sql .= "order by P_Nome; ";
    
	$REGISTROS = $p_Conexao->query($sql);

	$listagem = "<h1>Encomendas</h1>";
	
	foreach ($REGISTROS as $registro) /* só deve ter 1 encomenda */
	{ 
        $listagem .= "Produto: ".$registro["P_Nome"]."<br>";
        $listagem .= "R$: ".$registro["P_Preco"]."<br>";

        $listagem .= "<form action='conf_encomenda.php' method='post'>";
        $listagem .= "<input type='hidden' value=".$registro["XP_Encomenda"].">";
        $listagem .= "Forma de Pagamento: <select name='pagamento'>";
        $listagem .= "<option>Visa</option>";
        $listagem .= "<option>PIX</option>";
        $listagem .= "<option>Boleto</option>";
        $listagem .= "<option>etc...</option>";
        $listagem .= "</select> <br>";
        
        //$listagem .= "<a href='?encomenda=".."'> Confirmar Encomenda </a>";
        $listagem .= "<input type='submit' value='Confirmar Encomenda'>";
        $listagem .= "</form>";
	}

	return $listagem;	
}

// **************************************************************************************
function E_Confirmar( $p_Conexao, $DADOS = [] )
{
    $confirmando = "";

    $confirmando .= "<pre>".print_r($DADOS, true)."</pre>";
    
    return $confirmando;
}


// **************************************************************************************
function E_Quant_Encomendas( )
{
    $bd = BD_Conectar();
    
    $sql = "select count(XP_Encomenda) as E_Quant_Encomendas from Encomenda where XE_Cliente = :cliente";
    
    $comando = $bd->prepare($sql);

    $comando->bindValue(":cliente", $_SESSION["Login"], SQLITE3_INTEGER);

    $comando->execute();

    $REGISTROS = $comando->fetchAll(PDO::FETCH_ASSOC); // sempre retorna uma lista indexada, mesmo que apenas 1

    $registro = $REGISTROS[0];

    $bd = null;
    
    return $registro["E_Quant_Encomendas"];
}

?>
<?php 
include_once "controle_bd.php";

// **************************************************************************************
function C_Exibir_Formulario( $p_Mensagem )
{
	$form = "";
	$form .= "<form action='cad_cliente.php' method='post'>";

	$form .= "Nome: <input type='text' name='Nome'> <br>";
	$form .= "Telefone: <input type='text' name='Telefone'> <br>";
	$form .= "Email: <input type='text' name='Email'> <br>";
    $form .= "Senha: <input type='password' name='Senha'> <br>";
    $form .= "Foto: <input type='File' name='Foto'> <br>";
    $form .= "CEP: <input type='text' name='CEP'> <br>";
    $form .= "Endereço: <input type='text' name='Endereco'> <br>";

	$form .= "<input type='submit' value='Enviar'>";
	$form .= "<input type='reset' value='Cancelar'>";

	$form .= "</form>";

    if ( $p_Mensagem )
    {
        $form .= "<span class='erro'>".$p_Mensagem."</span>";
    }

	return $form;
}

// **************************************************************************************
function C_Inserir( $p_Conexao, $DADOS = [] )
{
    if ( nao_repete_email( $p_Conexao, $DADOS["Email"]) )
    {
    	$comando = $p_Conexao->prepare( 
            "INSERT INTO Cliente (C_Nome, C_Telefone, C_Email, C_Senha, C_Foto, C_CEP, C_Endereco) 
    			VALUES (:nome, :telefone, :email, :senha, :foto, :cep, :endereco);");
    	// Bind values directly to statement variables
    	$comando->bindValue(':nome', $DADOS["Nome"], SQLITE3_TEXT);
    	$comando->bindValue(':telefone', $DADOS["Telefone"], SQLITE3_TEXT);
    	$comando->bindValue(':email', $DADOS["Email"], SQLITE3_TEXT);
        $comando->bindValue(':senha', $DADOS["Senha"], SQLITE3_TEXT);

        $comando->bindValue(':foto', $DADOS["Foto"], SQLITE3_TEXT);
        $comando->bindValue(':cep', $DADOS["CEP"], SQLITE3_TEXT);
        $comando->bindValue(':endereco', $DADOS["Endereco"], SQLITE3_TEXT);
        
    	// Execute statement
    	$comando->execute();
    }
    else
    {
        return "Não pode repetir os dados de um Cliente já cadastrado.";
    }

}

// **************************************************************************************
function C_Consultar( $p_Conexao )
{
	$REGISTROS = $p_Conexao->query("SELECT * FROM Cliente order by C_Nome;");

	$listagem = "<h1>Clientes</h1>";
	
	foreach ($REGISTROS as $registro) 
	{ 
		$listagem .= '<h4>' . $registro['C_Nome'] . '</h4>';
        $listagem .= $registro['C_Email'] . '<br>';
        $listagem .= $registro['C_Telefone'] . '<br>';
        $listagem .= $registro['C_Senha'] . '<br>';
        $listagem .= $registro['C_Foto'] . '<br>';
        $listagem .= $registro['C_CEP'] . '<br>';
        $listagem .= $registro['C_Endereco'] . '<br>';
	}

	return $listagem;
	
}

// ***************************************************
function C_Login( $p_Mensagem )
{
    $form = "";

    $form .= "<form action='ses_login.php' method='post'>";

    $form .= "<table>";
    $form .= "<tr><td>Email: </td><td> <input type='text' name='Email'> </td></tr>";
    $form .= "<tr><td>Senha: </td><td> <input type='password' name='Senha'> </td></tr>";
    $form .= "<tr><td></td><td> <input type='submit' value='Enviar'>";
    $form .= " <input type='reset' value='Cancelar'></td></tr>";
    $form .= "</table>";
    
    $form .= "</form>";
    $form .= $p_Mensagem;

    return $form;
}

// **************************************************************************************
/*
function C_Autorizar( $p_Conexao, $p_Login, $p_Senha )
{
    $XP_Cliente = 0;
    
    $sql = "select * from Cliente where C_Email = :email AND C_Senha = :senha;";
    $comando = $p_Conexao->prepare($sql);
    $comando->bindValue(':email', $p_Login, SQLITE3_TEXT);
    $comando->bindValue(':senha', $p_Senha, SQLITE3_TEXT);
    $comando->execute();

    // fetchAll(): retorna uma lista de itens, mesmo que haja apenas
    //             1 item, esse item vem em uma lista
    $REGISTROS = $comando->fetchAll(PDO::FETCH_ASSOC);

    if ( count($REGISTROS) == 1 )
    {
        $registro = $REGISTROS[0];
        if ( array_key_exists( "XP_Cliente", $registro ) )
        {
            $XP_Cliente = $registro["XP_Cliente"];
        }
    }
    
    return ( $XP_Cliente ); 
}
*/

function C_Autorizar( $p_Conexao, $p_Login, $p_Senha )
{
    $sql = "select * from Cliente where C_Email = :login and C_Senha = :senha;";
    //
    $comando = $p_Conexao->prepare($sql);
    $comando->bindValue(':login', $p_Login, SQLITE3_TEXT);
    $comando->bindValue(':senha', $p_Senha, SQLITE3_TEXT);
    $comando->execute();

    $REGISTROS = $comando->fetchAll(PDO::FETCH_ASSOC);

   if ( count($REGISTROS) == 1 )
    {
        $registro = $REGISTROS[0];
        if ( array_key_exists( "XP_Cliente", $registro ) )
        {
            $XP_Cliente = $registro["XP_Cliente"];
        }
    }
   
    return ( $XP_Cliente );
}
// **************************************************************************************
function nao_repete_email( $p_Conexao, $p_Login)
{
	$sql  = "Select * from Cliente ";
	$sql .= "Where C_Email = :email";
	
    $comando = $p_Conexao->prepare($sql);
    $comando->bindValue(':email', $p_Login, SQLITE3_TEXT);
    $comando->execute();

    $REGISTRO = $comando->fetchAll(PDO::FETCH_ASSOC);

    return ( count($REGISTRO) == 0 ); 
}

// **************************************************************************************
function Verifica_Duplicidade( $p_Conexao, $p_Login, $p_Telefone )
{
	$sql  = "Select * from Cliente ";
	$sql .= "Where C_Email = :email OR C_Telefone = :telefone;";
	
    $comando = $p_Conexao->prepare($sql);
    $comando->bindValue(':email', $p_Login, SQLITE3_TEXT);
    $comando->bindValue(':telefone', $p_Telefone, SQLITE3_TEXT);
    $comando->execute();

    $REGISTRO = $comando->fetchAll(PDO::FETCH_ASSOC);

    return ( count($REGISTRO) > 0 ); 
}

?>
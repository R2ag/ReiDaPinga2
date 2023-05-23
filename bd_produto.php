<?php 
include_once "controle_bd.php";

// **************************************************************************************
function P_Exibir_Formulario( $p_Mensagem )
{
	$form = "";
	$form .= "<form action='cad_produto.php' method='post' enctype='multipart/form-data'>";

	$form .= "Nome: <input type='text' name='Nome'> <br>";
	$form .= "Descrição: <textarea name='Descricao' rows='5' cols='40'> </textarea><br>";
	$form .= "Preço: <input type='text' name='Preco'> <br>";

    $form .= "Imagem: <input type='file' name='Imagem_1'><br>";
    $form .= "Imagem: <input type='file' name='Imagem_2'><br>";
    $form .= "Imagem: <input type='file' name='Imagem_3'><br>";
    
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
function P_Inserir( $p_Conexao, $DADOS = [] )
{
    $imagem_1 = "";
    $imagem_2 = "";
    $imagem_3 = "";
    $msg_erro = "";
    if ( count($_FILES) > 0 )
    {
        //$imagem = $_FILES["img_prod"]["name"];
        //P_Salvar_Imagem($_FILES["img_prod"]);
        
        $msg_erro = P_Salvar_Imagem($_FILES);
        $imagem_1 = $_FILES["Imagem_1"]["name"];
        $imagem_2 = $_FILES["Imagem_2"]["name"];
        $imagem_3 = $_FILES["Imagem_3"]["name"];
    }

    if ( $msg_erro == "" )
    {
        $sql  = "INSERT INTO Produto ( P_Nome, P_Descricao, P_Preco, P_Imagem_1, P_Imagem_2, P_Imagem_3 ) ";
    	$sql .= "VALUES (:nome, :descricao, :preco, :imagem_1, :imagem_2, :imagem_3);";
    	$comando = $p_Conexao->prepare($sql);
    
    	// Bind values directly to statement variables
    	$comando->bindValue(':nome', $DADOS["Nome"], SQLITE3_TEXT);
    	$comando->bindValue(':descricao', $DADOS["Descricao"], SQLITE3_TEXT);
    	$comando->bindValue(':preco', floatval($DADOS["Preco"]), SQLITE3_FLOAT);
        $comando->bindValue(':imagem_1', $imagem_1, SQLITE3_TEXT);
        $comando->bindValue(':imagem_2', $imagem_2, SQLITE3_TEXT);
        $comando->bindValue(':imagem_3', $imagem_3, SQLITE3_TEXT);
    
    	// Format unix time to timestamp
    	//$formatted_time = date('Y-m-d H:i:s');
    	//$stmt->bindValue(':time', $formatted_time, SQLITE3_TEXT);
    
    	// Execute statement
    	$comando->execute();
    }

    return $msg_erro;
}

// **************************************************************************************
function P_Consultar( $p_Conexao )
{
	$REGISTROS = $p_Conexao->query("SELECT * FROM Produto order by P_Nome;");

	$listagem = "<h1>Produtos</h1>";
	
	foreach ($REGISTROS as $registro) 
	{ 
		$listagem .= "<a href='desc_produto.php?produto=".$registro['XP_Produto'] ."'>";
        $listagem .= "<h4>" . $registro['P_Nome'] . "</h4>";
        $listagem .= "XP: ".$registro['XP_Produto']."<br>"; 
		$listagem .= $registro['P_Descricao']."<br>";  
		$listagem .= "R$ ".$registro['P_Preco']."<br>";  
        $listagem .= "<img src='imagens/".$registro['P_Imagem_1']."' width='200' height='150'>";
        $listagem .= "</a>";
		$listagem .= "<hr>";
	}

	return $listagem;	
}

// **************************************************************************************
function P_Detalhar( $p_Conexao, $p_XP_Produto )
{
    $sql = "select * from Produto where XP_Produto = :produto;";
    $comando = $p_Conexao->prepare($sql);
    $comando->bindValue(':produto', $p_XP_Produto, SQLITE3_INTEGER);
    $comando->execute();

    $REGISTROS = $comando->fetchAll(PDO::FETCH_ASSOC);

    if ( count($REGISTROS) == 1 )
    {
        $registro = $REGISTROS[0];
        
        $detalhes = "<h1>Detalhes do Produto Escolhido...</h1>";

        $detalhes .= "<table><tr>";
        
        $detalhes .= "<td>";
        $detalhes .= '<h3>' . $registro['P_Nome'] . '</h3>';
        $detalhes .= $registro['P_Descricao']."<br>";  
        $detalhes .= "R$ ".$registro['P_Preco']."<br>";  
        $detalhes .= "<a href='bd_encomenda.php?produto=".$registro['XP_Produto']."'> Comprar </a>";  
        $detalhes .= "</td>";

        $detalhes .= "<td>";
        $detalhes .= ( $registro['P_Imagem_1'] ? "<img src='imagens/".$registro['P_Imagem_1']."' height='384'> <br>" : "" );
        $detalhes .= ( $registro['P_Imagem_2'] ? "<img src='imagens/".$registro['P_Imagem_2']."' height='384'> <br>" : "" );
        $detalhes .= ( $registro['P_Imagem_3'] ? "<img src='imagens/".$registro['P_Imagem_3']."' height='384'> <br>" : "" );
        $detalhes .= "</td>";
        
        $detalhes .= "</tr></table>";
        
        $detalhes .= '<hr>';
    }
    else
    {
        $detalhes = "<h1>Houve um erro na identificação do Produto, tente novamente!</h1>";
    }
    
	return $detalhes;	
}

// *********************************************************************************
// Existem vários parâmetros que podem ser verificados, os mais importantes são:
// $imagem -> dentro do "foreach"
// $imagem["name"]: contém uma string indicando o nome original do arquivo
// $imagem["tmp_name"]: contém uma string com o nome do arquivo temporário
//                     no Servidor, geralmente na pasta "/tmp"
// $imagem["type"]: indica o tipo do arquivo
// $imagem["size"]: indica o tamanho em bytes do arquivo
function P_Salvar_Imagem( $IMAGENS )
{
    $msg_erro = "";
    $gravar_arquivo = true;

    foreach( $IMAGENS as $imagem )
    {
        if ( $imagem["name"] != "" )
        {
            $destino = "imagens/" . basename($imagem["name"]);

            // Verifica se já fez upload de um arquivo com o mesmo nome:
            if (file_exists($destino)) 
            {
                $msg_erro = "A imagem: '".basename($imagem["name"])."', já existe.";
                $gravar_arquivo = false;
                
            } 

            //Verifica o tamanho (em bytes) do arquivo enviado:
            if ( filesize( $imagem["tmp_name"] ) > 1024*1024 )
            {
                $msg_erro = "A imagem: '".basename($imagem["name"])."', deve ter no máximo 1024KB.";
                $gravar_arquivo = false;
            }
            
            if ( $gravar_arquivo )
            {
                if ( ! move_uploaded_file($imagem["tmp_name"], $destino) ) 
                {
                    $msg_erro = "Não foi possível salvar a imagem: '".basename($imagem["name"])."'.";
                }
            }
        }
    
    }

    return $msg_erro;
}

?>
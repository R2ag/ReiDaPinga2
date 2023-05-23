<?php 
include_once "../doc_HTML.php";

function Criar_Tabela()
{
    
}

try {
  $db = new PDO('sqlite:lojaRMS.sqlite');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $res = $db->exec(
                    "drop table if exists Produto;
                    create table if not exists Produto (
                    
                    XP_Produto  integer primary key autoincrement,
                    
                    P_Nome      text,
                    P_Descricao text,
                    P_Preco     decimal(10,2),
                    P_Imagem_1  text,
                    P_Imagem_2  text,
                    P_Imagem_3  text );"
                  );

//////////////////////////////////////////////////////////////////////////////////////
    // Insere os dados:
    $stmt = $db->prepare(
                            "INSERT INTO Produto (P_Nome, P_Descricao, P_Preco, P_Imagem_1 ) 
                              VALUES (:nome, :descricao, :preco, :imagem )"
                        );
    
    // Bind values directly to statement variables
    $stmt->bindValue(':nome', 'F1 AM23', SQLITE3_TEXT);
    $stmt->bindValue(':descricao', 'Aston Marim verde, único dono', SQLITE3_TEXT);
    $stmt->bindValue(':preco', 17000000.00, SQLITE3_FLOAT);
    $stmt->bindValue(':imagem', 'AM23.jpg', SQLITE3_TEXT);
    
    // Format unix time to timestamp
    //$formatted_time = date('Y-m-d H:i:s');
    //$stmt->bindValue(':time', $formatted_time, SQLITE3_TEXT);
    
    // Execute statement
    $stmt->execute();

//////////////////////////////////////////////////////////////////////////////////////
    // Listagem dos produtos
    $REGISTROS = $db->query("SELECT * FROM Produto;");

    $listagem = "<h1>Produtos</h1>";
    
    foreach ($REGISTROS as $registro) 
    { 
        $listagem .= '<h4>' . $registro['P_Nome'] . '</h4>';
        $listagem .= "XP: ".$registro['XP_Produto']."<br>"; 
        $listagem .= $registro['P_Descricao']."<br>";  
        $listagem .= "R$ ".$registro['P_Preco']."<br>";
        $listagem .= "<img src='../imagens/".$registro['P_Imagem_1']."' width='20%' height='20%'>";
        $listagem .= '<hr>';
    }

  // Terminando tudo, finaliza a conexão com o Banco de Dados
  $db = null;
    
//////////////////////////////////////////////////////////////////////////////////////
    echo Monta_Doc_HTML( "", $listagem );
    
} 
catch (PDOException $ex) 
{
  echo $ex->getMessage();
  die("X- FIM -X");
}

?>
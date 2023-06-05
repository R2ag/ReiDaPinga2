<?php 
include_once "../doc_HTML.php";

try {
  $db = new PDO('sqlite:lojaRDP.sqlite');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $res = $db->exec(
                        "drop table if exists Cliente;
                        create table if not exists Cliente (
                        XP_Cliente  integer primary key autoincrement,
                        C_Nome      text,
                        C_Telefone  varchar[11],
                        C_Email     text,
                        C_Senha     text,
                        C_Foto      text,
                        C_CEP       varchar[8],
                        C_Endereco  text );"
                    );

//////////////////////////////////////////////////////////////////////////////////////
    // Insere os dados:
    $stmt = $db->prepare(
                            "INSERT INTO Cliente (C_Nome, C_Senha, C_Email) 
                              VALUES (:nome, :senha, :email);"
                        );
    
    // Bind values directly to statement variables
    $stmt->bindValue(':nome', 'Prof. Ricardo', SQLITE3_TEXT);
    $stmt->bindValue(':senha', 'abc123', SQLITE3_TEXT);
    $stmt->bindValue(':email', 'profRicardo@gmail.com', SQLITE3_TEXT);
    
    // Format unix time to timestamp
    //$formatted_time = date('Y-m-d H:i:s');
    //$stmt->bindValue(':time', $formatted_time, SQLITE3_TEXT);
    
    // Execute statement
    $stmt->execute();

//////////////////////////////////////////////////////////////////////////////////////
    // Listagem dos produtos
    $REGISTROS = $db->query("SELECT * FROM Cliente;");

    $listagem = "<h1>Clientes</h1>";
    
    foreach ($REGISTROS as $registro) 
    { 
        $listagem .= '<h4>' . $registro['C_Nome'] . '</h4>';
        $listagem .= $registro['C_Email']."<br>";  
        $listagem .= '<hr>';
    }

  // Terminando tudo, finaliza a conexão com o Banco de Dados
  $db = null;
    
//////////////////////////////////////////////////////////////////////////////////////
    echo Monta_doc_HTML( __FILE__, $listagem );
    
} 
catch (PDOException $ex) 
{
  echo $ex->getMessage();
  die("X- FIM -X");
}

?>
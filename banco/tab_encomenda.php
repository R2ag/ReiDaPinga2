<?php

include_once "../doc_HTML.php";

try {
  $db = new PDO('sqlite:lojaRMS.sqlite');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $res = $db->exec(
                    "drop table if exists Encomenda;
                    create table if not exists Encomenda (
                    XP_Pedido 	     integer primary key autoincrement,
                    XE_Cliente       integer,
                    XE_Produto       integer,
                    
                    E_Nome_Produto   text,
                    E_Codigo         integer,
                    E_Data_Encomenda date,
                    Foreign Key( XE_Cliente) references Cliente(XP_Cliente));"
                  );

    echo Monta_doc_HTML( __FILE__, "Tabela Encomenda criada com sucesso!" );
    
} catch (PDOException $ex) 
{
  echo $ex->getMessage();
  die("X- FIM -X");
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Teste</title>
    <meta charset="UTF-8">
</head>
<body>

	Turma Controle Ambiental <br>
    <?php
      //inicia a sessão
      if (!isset($_SESSION)) session_start();
      // Tenta se conectar ao servidor MySQL
          mysql_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysql_error());
          // Tenta se conectar a um banco de dados MySQL
          mysql_select_db('matvirtual') or trigger_error(mysql_error());
      

      $sql5 = "SELECT nome FROM materias";
      $select5 = mysql_query($sql5);

      $sql1 = "SELECT id FROM turmas WHERE turmaNome = 'Controle Ambiental'";
      $select1 = mysql_query($sql1);
      $resultado1 = mysql_fetch_array($select1);

      $sql2 = "SELECT * FROM alunos WHERE turmas_id = '".$resultado1['id']."' ORDER BY professor DESC";
      $select2 = mysql_query($sql2);

      while ($row5 = mysql_fetch_assoc($select5)) {
      	echo $row5['nome'].'<br>';
      	$alunosAtividade = 0;
      	$alunosDesempenho = 0;
      	$turmaIntegrante = 0;
      	while($row = mysql_fetch_assoc($select2))
      	{
        	$sql3 = "SELECT * FROM usuarios WHERE id = '" .$row['usuarios_id']."'";
        	$select3 = mysql_query($sql3);
      		$resultado3 = mysql_fetch_array($select3);
      		if($resultado3['tipo'] == 0)
      		{
	        	$sql4 = "SELECT * FROM desempenho WHERE usuarioId = '" .$row['usuarios_id']."' AND materiaNome = '".$row5['nome']."'";
	        	$select4 = mysql_query($sql4);
	      		$resultado4 = mysql_fetch_array($select4);
	      		if ($resultado4['acertos'] != null)
	      		{
		        	echo $resultado3['Usuario'] . ' acertos ' . $resultado4['acertos'] . '<br>';
		        	$alunosAtividade += 1;
		        	$alunosDesempenho += $resultado4['acertos'];
		        }
        		$turmaIntegrante += 1;
        	}
       	}
       	echo "Alunos que fizeram: " . $alunosAtividade . "<br>";
       	echo "Alunos sem contar com prof: " . $turmaIntegrante . "<br>";
       	echo "Desempenho dos alunos que fizeram: " . $alunosDesempenho . "<br>";

      	/*while($row2 = mysql_fetch_assoc($select2))
      	{
        	$sql6 = "SELECT * FROM usuarios WHERE id = '" .$row2['usuarios_id']."'";
        	$select6 = mysql_query($sql6);
      		$resultado6 = mysql_fetch_array($select6);
      		var_dump($sql6);
      		if($resultado6['tipo'] == 0)
      		{
	        	$sql7 = "SELECT * FROM desempenho WHERE usuarioId = '" .$row2['usuarios_id']."' AND materiaNome = '".$row5['nome']."'";
	        	$select7 = mysql_query($sql7);
	      		$resultado7 = mysql_fetch_array($select7);
	      		if ($resultado4['acertos'] != null)
	      		{
		        	echo $resultado6['Usuario'] . ' acertos ' . $resultado7['acertos'] . '<br>';
		        }
        	}
       	}*/
      }
    ?>
</body>
</html>
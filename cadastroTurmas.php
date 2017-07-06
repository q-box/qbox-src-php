<html>
        <head>
  <meta charset="UTF-8">
        <link rel="shortcut icon" href="css/imagens/LOGO.ico" type="image/x-icon" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/css.css">
        <script src="javascript/jquery-3.1.0.min.js"></script>  
  </head>
        <body>
        <?php
        //inicia a sessão
        if (!isset($_SESSION)) session_start();
        //checa se existe usuário
        if (!isset($_SESSION['usuarioId']) || $_SESSION['usuarioTipo'] != 1) {
        //finaliza a sessão caso não haja usuário
        session_destroy();
        //redireciona para o início
        header("location: perfil.php");
        die();
        }
        // Tenta se conectar ao servidor MySQL
        mysql_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysql_error());
        // Tenta se conectar a um banco de dados MySQL
        mysql_select_db('matvirtual') or trigger_error(mysql_error());
        if (isset($_POST['nomeTurma'])) {
        //guarda os dados da turma em variáveis
        $codigoTurma = rand(0,9999999999999);
        var_dump($codigoTurma);
        $nomeTurma = mysql_real_escape_string($_POST['nomeTurma']);
        //insere turma no banco de dados
        $sql = "INSERT INTO turmas (`codigoTurma`, `turmaNome`) VALUES('".$codigoTurma."', '".$nomeTurma."')";
        $insert = mysql_query($sql);
          if(!$insert)
            echo "Não foi possível cadastrar turma";
          else
          {
            $sqlGetMaiorIdTurma = "SELECT id FROM turmas ORDER BY id DESC LIMIT 0, 1";
            $selectGetMaiorIdTurma = mysql_query($sqlGetMaiorIdTurma);
            $resultadoGetMaiorIdTurma = mysql_fetch_array($selectGetMaiorIdTurma);

            echo 'maior id = ' . $resultadoGetMaiorIdTurma['id'];

            $sqlInsertUsuarioProfessor = "INSERT INTO alunos (`usuarios_id`, `turmas_id`, `professor`) VALUES('".$_SESSION['usuarioId']."', '".$resultadoGetMaiorIdTurma['id']."', 1)";
            var_dump($sqlInsertUsuarioProfessor);
            $insertUsuarioProfessor = mysql_query($sqlInsertUsuarioProfessor);
            if (!$insertUsuarioProfessor)
            {
                echo "Não foi possível cadastrar o professor na turma";
            }
            else
            {
                header("location: perfil.php");
            }
           }
        }
        else echo "Cadê o nome da turma animal?";
        ?>
</html>
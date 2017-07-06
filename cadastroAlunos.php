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
        // Tenta se conectar ao servidor MySQL
        mysql_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysql_error());
        // Tenta se conectar a um banco de dados MySQL
        mysql_select_db('matvirtual') or trigger_error(mysql_error());
        // inicia sessão
        if(!isset($_SESSION)) session_start();

        // Pega id da turma pelo código
        $codigoEntrarTurma = mysql_real_escape_string($_POST['codigoEntrarTurma']);
        $query = "SELECT id FROM turmas WHERE (codigoTurma = '" .$codigoEntrarTurma."')";
        $select = mysql_query($query);
        $array = mysql_fetch_array($select);
        $idTurma = $array['id'];
        $userid = $_SESSION['usuarioId'];
        
        echo "id: ". $_SESSION['usuarioId'];

        //insere alunos no banco de dados
        $sql = "INSERT INTO alunos (`usuarios_id`, `turmas_id`) VALUES('".$userid."', '".$idTurma."')";
        $insert = mysql_query($sql);

        var_dump($sql);
        if(!$insert)
            echo "Não foi possível entrar na turma";
        else
            header("location: perfil.php");
        ?>
</html>
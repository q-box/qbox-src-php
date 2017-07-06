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
        header("location: index.php");
        die();
        }
        mysql_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysql_error());
        // Tenta se conectar a um banco de dados MySQL
        mysql_select_db('matvirtual') or trigger_error(mysql_error());
        ?>
        <div class="container-fluid">
        <div class="row" id="topoAtividade">
            <div class="col-md-1">
                <a href="index.php">   <img id="logoAtividade" src="css/imagens/LOGO.png" onmouseover="this.src='css/imagens/LOGOhover.png'" onmouseout="this.src='css/imagens/LOGO.png'"></a>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-4">
                <div id="statusAtividade">
                    <div id="titAtividade">Cadastro de turmas</div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
        </div>
        <div class="container-fluid">
  <form action="cadastroTurmas.php" method="post">
  <legend><h2>Insira o nome da nova turma:</h2></legend>
    Nome:<br>
        <input type="text" name="nomeTurma">
        <input type="submit" value="Cadastrar Turmas" id="botaoConfirmar" />
  </form>
    <h2>
    <?php 
        $sql = "SELECT codigoTurma FROM `turmas` ORDER BY id DESC";
        $sql2 = "SELECT codigoTurma, turmaNome, id FROM `turmas` ORDER BY id DESC";
        $query = mysql_query($sql) or die(mysql_error());
        $query2 = mysql_query($sql2) or die(mysql_error());
        $row = mysql_fetch_row($query);
        echo "Código da última turma cadastrada: ". $row[0]; 
    ?>
    </h2>
    <h3>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
            </tr>
        </thead>
            <tbody>
            <?php while($row2 = mysql_fetch_row($query2)) { ?>
              <tr>
                <td><?php echo $row2[0]; ?></td>
                <td><?php echo $row2[1]; ?></td>
                <td><form action="turmasDelete.php" enctype="multipart/form-data" method="post"><input type="submit" value='Deletar' /><input type="hidden" name='turmas' value='<?php echo $row2[2]; ?>'></form></td>
              </tr>
            <?php } ?>
            </tbody>
    </table>
    </h3>
        </div>
        </body>
</html>
<?php
        //inicia a sessão
        if (!isset($_SESSION)) session_start();
        //checa se existe usuário
        if (!isset($_SESSION['usuarioId']) || $_SESSION['usuarioTipo'] != 1) {
        //finaliza a sessão caso não haja usuário
        session_destroy();
        die();
        }
        // Tenta se conectar ao servidor MySQL
        mysql_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysql_error());
        // Tenta se conectar a um banco de dados MySQL
        mysql_select_db('matvirtual') or trigger_error(mysql_error());
        if (isset($_POST['nomeEditarTurma'])) {
        var_dump($codigoTurma);
        $novoNomeTurma = mysql_real_escape_string($_POST['nomeEditarTurma']);
        $novoIdTurma = mysql_real_escape_string($_POST['editarTurmas']);

        //insere turma no banco de dados
        $sql = "UPDATE turmas SET `turmaNome`='".$novoNomeTurma."' WHERE (id = '".$novoIdTurma."'')";
        $insert = mysql_query($sql);
          if(!$insert)
            {
                echo "Não foi possível editar turma";
                header("location: perfil.php");
            }
        }
        else echo "Cadê o nome da turma, animal?";
        ?>
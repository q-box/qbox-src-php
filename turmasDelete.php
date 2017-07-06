<! doctype>
<html>
  <head>
    <title>Deletar turmas</title>
  </head>
  <?php
      $link = mysqli_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysqli_error());
      //tenta se conectar ao banco de dados MatematicaVirtual
      mysqli_select_db($link, 'matvirtual') or trigger_error(mysqli_error($link));

      $query1 = "DELETE FROM alunos where turmas_id=". $_POST['turmas'];
      $delete1 = mysqli_query($link, $query1);
      
      $query2 = "DELETE FROM turmas where id=". $_POST['turmas'];
      $delete2 = mysqli_query($link, $query2);
      header('location: perfil.php');
    ?>
  <body>
  </body>
</html>
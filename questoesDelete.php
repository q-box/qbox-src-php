<! doctype>
<html>
  <head>
    <title>Deletar assuntos</title>
  </head>
  <?php
    $link = mysqli_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysql_error());
      //tenta se conectar ao banco de dados MatematicaVirtual
      mysqli_select_db($link, 'matvirtual') or trigger_error(mysqli_error($link));
      $query = "DELETE FROM questoes where id=". $_POST['questoes'];
      $delete = mysqli_query($link, $query);
      header('location: cadastroQuestoesForm.php');
    ?>
  <body>
  </body>
</html>
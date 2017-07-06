<! doctype>
<html>
  <head>
    <title>Deletar assuntos</title>
  </head>
  <?php
      $link = mysqli_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysqli_error($link));
      //tenta se conectar ao banco de dados MatematicaVirtual
      mysqli_select_db($link, 'matvirtual') or trigger_error(mysqli_error($link));
      $query = "DELETE FROM materias where id=". $_POST['assunto'];
      $delete = mysqli_query($link, $query);
      $query2 = "DELETE FROM questoes where assunto='" . $_POST['assuntoNome'] . "'";
      $delete2= mysqli_query($link, $query2);
      header('location: cadastroAssuntosForm.php');
    ?>
  <body>
  </body>
</html>
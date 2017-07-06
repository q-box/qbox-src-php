<! doctype>
<html>
  <head>
    <title>Editar assuntos</title>
  </head>
  <?php
      $link = mysqli_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysqli_error($link));
      //tenta se conectar ao banco de dados MatematicaVirtual
      mysqli_select_db($link, 'matvirtual') or trigger_error(mysqli_error($link));
      $nomeAssunto = mysqli_real_escape_string($link, $_POST['nomeAssunto']);
      $descricaoAssunto = mysqli_real_escape_string($link, $_POST['descricaoAssunto']);
      $linkFotoAssunto = mysqli_real_escape_string($link, $_POST['linkFotoAssunto']);
      $query = "UPDATE `materias` SET `nome`='".$nomeAssunto."',`descricao`='".$descricaoAssunto."',`linkfoto`='".$linkFotoAssunto."' WHERE id =".$_POST['update'];
      //UPDATE `materias` SET `nome`='" .$nomeAssunto. "',`descricao`='" .$descricaoAssunto. "',`linkfoto`='" .$linkFotoAssunto. "' WHERE id=". $_POST['update'];
      $update = mysqli_query($link, $query);
      header('location: cadastroAssuntosForm.php');
    ?>
  <body>
  </body>
</html>
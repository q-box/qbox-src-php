<! doctype>
<html>
  <head>
    <title>Editar assuntos</title>
  </head>
  <?php
    $link = mysqli_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysql_error());
      //tenta se conectar ao banco de dados MatematicaVirtual
      mysqli_select_db($link, 'matvirtual') or trigger_error(mysqli_error($link));
      $assuntoUpdate = mysqli_real_escape_string($link, $_POST['assuntoUpdate']);
      $nivelUpdate = mysqli_real_escape_string($link, $_POST['nivelUpdate']);
      $enunciadoUpdate = mysqli_real_escape_string($link, $_POST['enunciadoUpdate']);
      $linkVideoUpdate = mysqli_real_escape_string($link, $_POST['linkVideoUpdate']);
      $query = "UPDATE `questoes` SET `enunciado`='".$enunciadoUpdate."',`linkVideo`='".$linkVideoUpdate."',`assunto`='".$assuntoUpdate."',`nivel`='".$nivelUpdate."' WHERE id =".$_POST['update'];
      //UPDATE `materias` SET `nome`='" .$nomeAssunto. "',`descricao`='" .$descricaoAssunto. "',`linkfoto`='" .$linkFotoAssunto. "' WHERE id=". $_POST['update'];
      $update = mysqli_query($link, $query);
      //header('location: cadastroQuestoesForm.php');
    ?>
  <body>
  </body>
</html>
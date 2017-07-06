<html>
  <head>
    <meta charset="utf-8">
  </head>
    <?php
      // Checa se usuário está logado como admin
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
    // Tenta se conectar ao servidor MySQL
        $link = mysqli_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysqli_error());
      // Tenta se conectar a um banco de dados MySQL
        mysqli_select_db($link, 'matvirtual') or trigger_error(mysqli_error($link));
    //guarda os dados da questão em variáveis
      $nome = mysqli_real_escape_string($link, $_POST['nome']);
      $descricao = mysqli_real_escape_string($link, $_POST['descricao']);
      //verifica se foi enviado um foto
    if ( isset( $_FILES[ 'foto' ][ 'name' ] ) && $_FILES[ 'fotoPerfilCadastro' ][ 'error' ] == 0 ) 
    {
          $foto_tmp = $_FILES[ 'foto' ][ 'tmp_name' ];
          $nomeFoto = $_FILES[ 'foto' ][ 'name' ];
          // Pega a extensão
          $extensao = pathinfo ( $nomeFoto, PATHINFO_EXTENSION );
          // Converte a extensão para minúsculo
          $extensao = strtolower ( $extensao );
          // Somente imagens, .jpg;.jpeg;.gif;.png
          // Aqui eu enfileiro as extensões permitidas e separo por ';'
          // Isso serve apenas para eu poder pesquisar dentro desta String
        if ( strstr ( '.jpg;.jpeg;.gif;.png', $extensao ) ) {
            // Cria um nome único para esta imagem
            // Evita que duplique as imagens no servidor.
            // Evita nomes com acentos, espaços e caracteres não alfanuméricos
            $novoNome = uniqid ( time () ) . "." . $extensao;
            // Concatena a pasta com o nome
            $destino = 'css/imagens/' . $novoNome;
            // tenta mover o foto para o destino
              if ( @move_uploaded_file ( $foto_tmp, $destino ) ) {
                echo 'foto salvo com sucesso em : <strong>' . $destino . '</strong><br />';
                echo ' <img src = "' . $destino . '" />';
              }
              else
              echo 'Erro ao salvar o foto. Aparentemente você não tem permissão de escrita.<br />';
          }
          else
          echo 'Você poderá enviar apenas fotos "*.jpg;*.jpeg;*.gif;*.png"<br />';
        }
        else
        {
            $destino = mysqli_real_escape_string($link, $_POST['linkfoto']);
            var_dump($destino);
        }
    //insere questão no banco de dados
      $sql = "INSERT INTO materias (`nome`, `descricao`, `linkfoto`) VALUES('".$nome."', '".$descricao."', '".$destino."')";
      $insert = mysqli_query($link, $sql);
      if(!$insert){
        echo "Não foi possível cadastrar matéria";
      }
      if ($insert){
        header("location: index.php");
      }
    ?>
</html>
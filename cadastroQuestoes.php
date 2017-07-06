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
      $enunciado = mysqli_real_escape_string($link, $_POST['enunciado']);
      $assunto = mysqli_real_escape_string($link, $_POST['assunto']);
      $nivel = mysqli_real_escape_string($link, $_POST['nivel']);
    //guarda nível da questão em uma variável de sessão
      $_SESSION['nivel'] = mysqli_real_escape_string($link, $_POST['nivel']);
      $_SESSION['assunto'] = mysqli_real_escape_string($link, $_POST['assunto']);
      $linkVideo = mysqli_real_escape_string($link, $_POST['linkVideo']);
      $alternativaVerdadeira = mysqli_real_escape_string($link, $_POST['alternativaVerdadeira']);
      $alternativaFalsa1 = mysqli_real_escape_string($link, $_POST['alternativaFalsa1']);
      $alternativaFalsa2 = mysqli_real_escape_string($link, $_POST['alternativaFalsa2']);
      $alternativaFalsa3 = mysqli_real_escape_string($link, $_POST['alternativaFalsa3']);
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
      if ($linkVideo == "") 
        $sql = "INSERT INTO questoes (`enunciado`, `assunto`, `nivel`, `linkFoto`) VALUES('".$enunciado."', '".$assunto."', '".$nivel."', '".$destino."')";
      else
        $sql = "INSERT INTO questoes (`enunciado`, `assunto`, `nivel`, `linkVideo`, `linkFoto` ) VALUES('".$enunciado."', '".$assunto."', '".$nivel."', '".$linkVideo."', '".$destino."')";
      $insert = mysqli_query($link, $sql);
      if(!$insert){
        echo "Não foi possível cadastrar questão";
      }
    //seleciona id da questão inserida  
      $sql = "SELECT id FROM questoes ORDER BY id DESC LIMIT 1";
      $select = mysqli_query($link, $sql);
      $resultado = mysqli_fetch_assoc($select);
      $questaoId = $resultado['id'];
      if (!$select) {
        echo "Ocorreu um erro na busca de dados no sevidor";
      }
    //insere alternativas da respectiva questão através do campo id
      $sql = "INSERT INTO alternativas (`questaoId`, `alternativa`, `tipo`) VALUES ('".$questaoId."', '".$alternativaVerdadeira."', '1'),('".$questaoId."', '".$alternativaFalsa1."', '0'),('".$questaoId."', '".$alternativaFalsa2."', '0'),('".$questaoId."', '".$alternativaFalsa3."', '0')";
      $insert = mysqli_query($link, $sql);
      if (!$insert) {
        echo "A questão não pode ser inserida";
      }
      //header("location: cadastroQuestoesForm.php");
    ?>
</html>
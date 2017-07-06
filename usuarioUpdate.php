<?php
  //Iniciando sessão
  if(!isset($_SESSION)) SESSION_start();
  //validação de dados de usuário
    if (!empty($_POST) and (empty($_POST['usuario']) or empty($_POST['senha']) or empty($_POST['nome']) or empty($_POST['email'])))
    {
      $_SESSION['validarCadastro'] = 0;
      header("location: index.php");
      exit;
    }
    include('m2brimagem.class.php');
  //tenta se conectar ao servidos MySQL
    $link = mysqli_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysqli_error());
  //tenta se conectar ao banco de dados MatematicaVirtual
    mysqli_select_db($link, 'matvirtual') or trigger_error(mysqli_error($link));
  //guarda os nomes de usuario, senha, nome e email em variáveis
    $usuario = mysqli_real_escape_string($link, $_POST['usuario']);
    $senha = mysqli_real_escape_string($link, $_POST['senha']);
    $nome = mysqli_real_escape_string($link, $_POST['nome']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
  //validação de dados inseridos
    $query = "SELECT Usuario FROM usuarios WHERE Usuario = '".$usuario."'";
    $select = mysqli_query($link, $query);
    $array = mysqli_fetch_array($select, MYSQLI_NUM);
    $logarray = $array[0];
    $mopau = '';
    //verifica se foi enviado um foto
    if ( isset( $_FILES[ 'fotoPerfilCadastro' ][ 'name' ] ) && $_FILES[ 'fotoPerfilCadastro' ][ 'error' ] == 0 ) 
    {
          $foto_tmp = $_FILES[ 'fotoPerfilCadastro' ][ 'tmp_name' ];
          $nomeFoto = $_FILES[ 'fotoPerfilCadastro' ][ 'name' ];
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
            $destino = 'imagens/' . $novoNome;
            $mopau = $destino;
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
            echo 'Você não enviou nenhum foto!';
        }
        if ($logarray == $usuario) {
            $_SESSION['validarUsuario'] = 0;
      header('location: index.php');
      die();
    }

    //insere os dados de ususario na tabela
    else{
      $query = "INSERT INTO usuarios (usuario, senha, nome, email, linkFoto, tipo) VALUES ('".$usuario."', '".$senha."', '".$nome."', '".$email."', '".$destino."', '".$destino."')";
      $insert = mysqli_query($link, $query);
    }
    echo 'mopau: '; var_dump($mopau);
    $oImg = new m2brimagem($mopau);
    $valida = $oImg->valida();
    if ($valida == 'OK') {
      $oImg->redimensiona(200,200,'crop');
      $oImg->grava($mopau, 80);
    } else {
      die($valida);
    }
   
    if ($insert) {
    // Salva os dados do usuário
      $_SESSION['validarCadastro'] = 1;
      $sql = "SELECT `id`, `nome`, `ativo` FROM `usuarios` WHERE (`usuario` = '".$usuario."') AND (`senha` = '".$senha."') AND (`ativo` = '1') LIMIT 1";
      $select = mysqli_query($link, $sql);
      $resultado = mysqli_fetch_assoc($select);
      // Inicia uma sessão se ela não existir
        if(!isset($_SESSION))
          session_start();
          //salva dados da sessão
          $_SESSION['usuarioId'] = $resultado['id'];
          $_SESSION['usuarioNome'] = $resultado['nome'];
          //mensagem de erro caso não seja possível cadastrar
      
    
    //redireciona o usuário
        $_SESSION['validarCadastro'] = 1;
        $_SESSION['validarUsuario'] = 1;
        header("location: index.php"); exit;
    }
  ?>
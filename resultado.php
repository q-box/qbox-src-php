<html>
<head>
  <meta charset="UTF-8">
  <title>Resultado</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="css/imagens/LOGO.ico" type="image/x-icon" />
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
  <script src="https://use.fontawesome.com/627396afb1.js"></script>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/css.css">
  <script src="javascript/jquery-3.1.0.min.js"></script>  
</head>
  <?php
    // inicia sessão
        if(!isset($_SESSION)) session_start();
        // Tenta se conectar ao servidor MySQL
        mysql_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysql_error());
        // Tenta se conectar a um banco de dados MySQL
        mysql_select_db('matvirtual') or trigger_error(mysql_error());
        //insere numero de erros e acertos em variáveis 
    $acertos = $_SESSION['contadorAcertos'];
    $erros = $_SESSION['contadorErros'];
    //validação de alternativas
        if (isset($_POST['alt'])){
          if ($_POST['alt'] == 1) {
                $acertos += 1;
              }
              else
                $erros += 1;
        }
        else
          $erros += 1;
        //calculando porcentagem acertos/total
        $porcentagem = $acertos/($acertos + $erros) * 100;
        //inserindo/atualizando desempenho no banco de dados
        $sql = "SELECT * FROM `desempenho` WHERE (`usuarioId` = '".$_SESSION['usuarioId']."') AND (`materiaNome` = '".$_POST['nomeAssunto']."') LIMIT 1";
        $consulta = mysql_query($sql);
        if (mysql_num_rows($consulta) == 0) {
            $sql = "INSERT INTO desempenho (usuarioId, materiaNome, acertos, erros) VALUES ('".$_SESSION['usuarioId']."', '".$_POST['nomeAssunto']."', '".$acertos."', '".$erros."')";
            $insert = mysql_query($sql);
            if(!$insert){
          echo "Desculpe, não foi possível guardar seus dados de desempenho". mysql_error();
        }
        }          
        else{
            $sql = "UPDATE desempenho SET acertos = '".$acertos."', erros = '".$erros."' WHERE usuarioId = '".$_SESSION['usuarioId']."' AND materiaNome = '".$_POST['nomeAssunto']."'";
            $update = mysql_query($sql);
            if(!$update){
          echo "Desculpe, não foi possível atualizar seus dados de desempenho";
        }  
        } 
  ?>
<body>
  <div class="container-fluid">
        <div class="row" id="topoAtividade">
            <div class="col-md-1">
                <a href="index.php">   <img id="logoAtividade" src="css/imagens/LOGO.png" onmouseover="this.src='css/imagens/LOGOhover.png'" onmouseout="this.src='css/imagens/LOGO.png'"></a>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-4">
                <div id="statusAtividade">
                    <div id="titAtividade"><?php echo $_POST['nomeAssunto']; ?></div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    <div id="cardResultado">
      <h1>Lista de exercícios terminada!</h1><br>
      <img src="imagens/exercicioConcluido.png"><br><br>
      <h1><b>Resultado:</b><br><br>
      <i class="fa fa-check-circle" aria-hidden="true" style="color:#1790a1;"></i> <?php echo $acertos ?><br>
      <i class="fa fa-times-circle" aria-hidden="true" style="color:silver;"></i> <?php echo $erros ?><br>
      <hr>
      Aproveitamento: <h1 id="porcentagemResultado"><?php echo number_format($porcentagem,2,',',''); ?>%</h1><br></h1>
      <h3><a href="index.php" class="linkVoltar">voltar</a> | <a href="desempenho.php" class="linkVoltar">Desempenho</a></h3><br>
      <?php 
        //Reseta contadores
        $_SESSION['contadorAcertos'] = 0;
        $_SESSION['contadorErros'] = 0;
      ?>
    </div>
  </div>
</body>
</html>
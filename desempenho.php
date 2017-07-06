<!DOCTYPE html>
  <html>
    <head>
      <meta charset="UTF-8">
      <title>Desempenho</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="shortcut icon" href="css/imagens/LOGO.ico" type="image/x-icon" />
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
      <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
      <link rel="stylesheet" href="css/css.css">
      <script src="javascript/jquery-3.1.0.min.js"></script>  
    </head>
    <body>
    <?php
      //inicia a sessão
      if (!isset($_SESSION)) session_start();
      // Tenta se conectar ao servidor MySQL
          $link = mysqli_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysqli_error($link));
          // Tenta se conectar a um banco de dados MySQL
          mysqli_select_db($link, 'matvirtual') or trigger_error(mysqli_error($link));
    ?>
      <div id="containerAll">
      <div class="container-fluid">
        <div class="row" id="containerConteudo">
          <!-- Sidebar -->
          <div class="col-md-2" id="containerSidebar"> 
            <div id="sidebar">
              <a href="index.php" id="home">
                <div id="containerIcone">
                  MATEMÁTICA VIRTUAL
                  <div id="logo"></div>
                </div>
              </a>
              <ul id="listaSidebar">
                <li id="menuHamburger">
                  <img src="css/imagens/menu.png" id="hamburger">
                </li>
                <div id="menuItens">
                  <li id="dropdown">
                    Turmas
                  </li>
                    <ul id="dropdownTurmas">
                    <?php 
                            //Iniciando sessão
                      if(!isset($_SESSION)) SESSION_start();
                            $sql = "SELECT id, turmaNome, codigoTurma FROM turmas ORDER BY turmaNome";
                            $select = mysqli_query($link, $sql);
                            if (!$select) {
                              echo "Ocorreu um erro no carregamento de turmas". mysqli_error($link);
                            }
                            if ($_SESSION['usuarioId'] != null){
                            while($row = mysqli_fetch_assoc($select)) {
                                $sql2 = "SELECT id FROM alunos WHERE turmas_id=".$row['id']." and usuarios_id=".$_SESSION['usuarioId'];
                                $select2 = mysqli_query($link, $sql2);
                                $row2 = mysqli_fetch_assoc($select2);
                                if($row2['id'] != null){
                                  echo "<li><form action=\"turma.php\" method=\"post\">
                                          <input type=\"submit\" value='".$row['turmaNome']."' name=\"nomeTurma\" id=\"inputTurmaNomeDropdown\">
                                        </form></li>";
                                }
                              }
                            }
                          ?>
                    </ul>
                  <li>
                    <a href="desempenho.php">Desempenho</a>
                  </li>
                  <?php
                  if (isset($_SESSION['usuarioTipo'])) {
                  if ($_SESSION['usuarioTipo'] == 1) { 
                    echo "<li>
                    <a href=\"cadastroQuestoesForm.php\">Cadastrar questões</a>
                    </li>
                    <li>
                    <a href=\"cadastroAssuntosForm.php\">Cadastrar assuntos</a>
                    </li>";
                  }
                }
                  ?>
                </div>
              </ul>
            </div>
          </div>
          <!-- Área de disposição dos cards da página -->
          <div class="col-md-10" id="containerCards"> 
            <div class="row">
              <!-- Card de informações de perfil -->
              <?php 
             $userid = $_SESSION['usuarioId'];
             $sql1 = "SELECT * FROM usuarios WHERE id = '$userid'";
             $query = mysqli_query($link, $sql1) or die(mysqli_error($link));
             $resultado = mysqli_fetch_array($query);
             $mostraimagem = $resultado['linkFoto']; 
             if (!isset($_SESSION['usuarioId'])) { 
                echo "<div id=\"cardLogar\" onmouseover=\"mostrarLogin()\" onmouseout=\"esconderLogin()\"> <!-- MUDAR PARA ...infoPerfil -->
                <a href=\"#\"><div id=\"botaoLogin\">Fazer login</div></a>
                <div id=\"formLogin\">
                  <form action=\"validacao.php\" method=\"POST\">
                    Usuário
                    <input type=\"text\" id=\"usuarioLogar\" name=\"usuario\">
                    Senha
                    <input type=\"password\" id=\"senhaLogar\" name=\"senha\">
                    <div id=\"formLoginCadastro\"><a onclick=\"abrirJanelaCadastro('modalCadastro')\">Cadastre-se</a></div><button id=\"botaoLogar\" type=\"submit\">Entrar</button>
                  </form>
                </div>
              </div>";
              }
              else{
                echo "<div id=\"cardPerfil\"> <!-- MUDAR PARA ...infoPerfil -->
                <div id=\"fotoPerfil\">
                  <img src="; echo  $mostraimagem; echo ">
                </div>
                <div id=\"textoPerfil\">
                  ";  
                  echo $_SESSION['usuarioNome']; 
                  echo "<br>
                  <a href=\"perfil.php\">Perfil</a> | <a href=\"logout.php\">Sair</a>  
                </div>
              </div>";
              }
      
              ?>
            </div>
              <!-- Cards de desempenho -->
              <div class="col-md-12" id="containerDesempenho"> 
                    <div id="desempenhoTitle">
                      <h1>Desempenho</h1>  
                    </div>
                    <?php 
                    if (isset($_SESSION['usuarioId'])) {
                    $userid = $_SESSION['usuarioId'];
                    $sql = "SELECT acertos, erros, materiaNome FROM desempenho WHERE usuarioId = '$userid'";
                    $select = mysqli_query($link, $sql) or die(mysqli_error($link));
                    if (!$select || mysqli_num_rows($select) == 0) {
                      echo "<h2>Não há dados de desempenho disponíveis</h2>";
                    }
                    else{
                    $contador = 1;
                    while($row = mysqli_fetch_assoc($select)) {
                    $contador ++;
                    $porcAcertos = $row['acertos']/($row['acertos']+$row['erros'])*100;
                    $porcErros = 100 - $porcAcertos; 
                    if ($porcAcertos<50)$textoResultado = "Você precisa praticar mais..."; 
                    elseif ($porcAcertos<=70)$textoResultado = "Pratique um pouco mais, você está quase lá!";
                    elseif ($porcAcertos>70)$textoResultado = "Parabéns! Você tem um bom domínio da materia!";             
                    echo "
                    <div class=\"row\" id=\"barraConteudo".$contador."\" onclick=\"alteraImagem('botaoDesempenho".$contador."')\">
                      <div class=\"col-md-11\">
                        ".$row['materiaNome']."
                      </div>
                      <div class=\"col-md-1\">
                        <a href=\"#\">
                          <img src=\"css/imagens/botaoDesempenhoDesativado.png\" class=\"botaoDesempenho\" id=\"botaoDesempenho".$contador."\">
                        </a>
                      </div>
                    </div>
                    <div class=\"barraConteudoEspecificacoes".$contador."\" style=\"text-align: center;\">
                      <div class=\"row\" id=\"barraConteudoAtividade\">                        
                        <div class=\"col-md-6\" id=\"barraConteudoAtividadePerf\">Acertos ".$row['acertos']."</div>        
                        <div class=\"col-md-6\" id=\"barraConteudoAtividadeErros\">Erros ".$row['erros']."</div>
                      </div>
                      <div class=\"row\" id=\"barraConteudoResultados\">                        
                        <div class=\"col-md-6\" id=\"barraConteudoResultadosPerf\">".number_format($porcAcertos,2,',','')."%</div>
                        <div class=\"col-md-6\" id=\"barraConteudoResultadosErros\">".number_format($porcErros,2,',','')."%</div>      
                      </div>
                      <div class=\"row\" id=\"barraConteudoComentarios\">
                        <div class=\"col-md-12\">
                          <h2>".$textoResultado."</h2>
                          <h3>Continue praticando em casa para manter o conteúdo fresco em sua cabeça</h3>              
                          <iframe src=\"css/circle/".number_format($porcAcertos,0,',','')."p.html\"></iframe>
                        </div>
                      </div>
                    </div>";
                    }
                    }
                  }
                  else
                    echo "<h2>Dados de desempenho não disponíveis,<br> faça login ou cadastre-se para visualiza-los</h2>";
                    ?>
                    <!-- Janela Modal - Cadastro -->
      <div id="fundoModalCadastro" onclick="fecharJanelaCadastro('modalCadastro')">
      </div>
      <div id="modalCadastro">
        <form action="cadastro.php" enctype="multipart/form-data" method="post">
          <label for="nome">Nome</label></br>
          <input id="nome" type="text" name="nome" size="20"></br>
          <label for="usuario">Usuário</label></br>
          <input id="usuario" type="text" name="usuario" size="20"></br>
          <label for="email">E-mail</label></br>
          <input id="email" type="email" name="email" size="20"></br>
          <label for="senha">Senha</label></br>
          <input id="senha" type="password" name="senha" size="20"></br>
          <label for="confirmarSenha">Confirmar senha</label></br>
          <input id="confirmarSenha" type="password" name="confirmarSenha" size="20"></br>
          <label for="fotoPerfilCadastro">Selecione uma imagem</label></br>
          <input id="modalCadastroFile" type="file" name="fotoPerfilCadastro"></br>
          <button id="botaoCadastro">Cadastrar</button>
        </form>
        <a class="fechar" onclick="fecharJanelaCadastro('modalCadastro')">x</a>
      </div> 
      
              <!-- Fim das barras de desemepenho -->
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </body>
    <script src="javascript/jquery-3.1.0.min.js"></script>  
        <script type="text/javascript">
        $(document).ready(
              function() {
                $("#dropdown").click(MostrarEsconderTurmas);
              }
            );
            function MostrarEsconderTurmas(){
              $("#dropdownTurmas").slideToggle(500);
            }
        <?php 
        for ($contadorJs = 1; $contadorJs<=$contador; $contadorJs++) {
        echo "
            $(document).ready(
        function() {
          $(\"#barraConteudo".$contadorJs."\").click(MostrarEsconder".$contadorJs.");
        }
      );
            function MostrarEsconder".$contadorJs."(){
                $(\".barraConteudoEspecificacoes".$contadorJs."\").slideToggle(600);
            }";
        }
        ?>
      function alteraImagem(img) {
        var src = document.getElementById(img).src.substr(document.getElementById(img).src.lastIndexOf('/') + 1, document.getElementById(img).src.length);

        if (src == 'botaoDesempenhoDesativado.png') {
          document.getElementById(img).src = 'css/imagens/botaoDesempenhoAtivado.png'; //
        } else { 
          document.getElementById(img).src = 'css/imagens/botaoDesempenhoDesativado.png';
        }   
      }
      $(document).ready(
            function() {
              $("#menuHamburger").click(MostrarEsconder1);
            }
          );
          function MostrarEsconder1(){
            $("#menuItens").slideToggle(500);
          }
        function fun(){
          if ($(window).width() > 991) {
             document.getElementById('menuItens').style.display = "block";
             document.getElementById('hamburger').style.display = "none";
          }
          else{
            document.getElementById('hamburger').style.display = "block";
            document.getElementById('menuItens').style.display = "none";
          }
        }
        function mostrarLogin()
        {
          document.getElementById('formLogin').style.display = 'block';
        }
        function esconderLogin()
        {
          document.getElementById('formLogin').style.display = 'none';
        }
    function abrirJanelaCadastro(x){
      document.getElementById(x).style.display = 'block';
      document.getElementById('fundoModalCadastro').style.display = 'block';
    }
    function fecharJanelaCadastro(x){
      document.getElementById(x).style.display = 'none';
      document.getElementById('fundoModalCadastro').style.display = 'none';
    }
    </script>
  </html>

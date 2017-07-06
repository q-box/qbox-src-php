<!DOCTYPE html>
  <html>
    <head>
      <title><?php echo $_POST['nomeTurma'];?></title>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="shortcut icon" href="css/imagens/LOGO.ico" type="image/x-icon" />
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
      <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
      <link rel="stylesheet" href="css/css.css">
      <script src="javascript/jquery-3.1.0.min.js"></script>
    </head>
    <body onresize="fun()" onload="fun()">
    <?php
      //inicia a sessão
      if (!isset($_SESSION)) session_start();
      // Tenta se conectar ao servidor MySQL
          mysql_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysql_error());
          // Tenta se conectar a um banco de dados MySQL
          mysql_select_db('matvirtual') or trigger_error(mysql_error());
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
                            $select = mysql_query($sql);
                            if (!$select) {
                              echo "Ocorreu um erro no carregamento de turmas";
                            }
                            if ($_SESSION['usuarioId'] != null){
                            while($row = mysql_fetch_assoc($select)) {
                                $sql2 = "SELECT id FROM alunos WHERE turmas_id=".$row['id']." and usuarios_id=".$_SESSION['usuarioId'];
                                $select2 = mysql_query($sql2);
                                $row2 = mysql_fetch_assoc($select2);
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
               $query = mysql_query($sql1);
               $resultado = mysql_fetch_array($query);
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
            <div class="row">
              <div id="turmaTitle">
                <h1><?php echo $_POST['nomeTurma'];?></h1>
                <?php
                  $sql1 = "SELECT codigoTurma FROM turmas WHERE turmaNome = '". $_POST['nomeTurma']."'";
                  $select1 = mysql_query($sql1);
                  $resultado1 = mysql_fetch_array($select1);
                  if (isset($_SESSION['usuarioTipo'])) {
                  if ($_SESSION['usuarioTipo'] == 1) { 
                      echo "<h4>Código da turma: ".$resultado1['codigoTurma']."</h4>";
                    }
                  }
                ?>    
              </div>
            </div>
            <div class="row" id="opcoesTurma">
              <div class="col-md-3 col-sm-2  col-xs-6"> 
                <a onclick="alterarDiv('membrosTurma')"><h3>Membros</h3></a>
              </div>
              <div class="col-md-3 col-sm-2 col-xs-6">  
                <a onclick="alterarDiv('desempenhoTurma')"><h3>Desempenho</h3></a>
              </div>
            </div>
              
            <div id="membrosTurma">
              
            <?php
              $sql1 = "SELECT id FROM turmas WHERE turmaNome = '". $_POST['nomeTurma']."'";
              $select1 = mysql_query($sql1);
              $resultado1 = mysql_fetch_array($select1);

              $sql2 = "SELECT * FROM alunos WHERE turmas_id = '".$resultado1['id']."' ORDER BY professor DESC";
              $select2 = mysql_query($sql2);

              $turmaIntegrante = 0;

              while($row = mysql_fetch_assoc($select2))
              {
                $sql3 = "SELECT * FROM usuarios WHERE id = '" .$row['usuarios_id']."'";
                $select3 = mysql_query($sql3);
                $resultado3 = mysql_fetch_array($select3);
                echo '<div class="row" id="infoUsuarioTurma">
                        <div class="col-md-1 col-sm-1">
                          <img src="'.$resultado3['linkFoto'].'">
                        </div>
                        <div class="col-md-5 col-sm-5">
                          <div class="nomeUsuarioTurma">
                            '.$resultado3['Usuario'].'
                          </div>
                        </div>';
                if ($turmaIntegrante == 0)
                {
                  echo '<div class="col-md-6 col-sm-6">
                          <div class="statusUsuarioTurmaProf">
                            Professor
                          </div>
                        </div>';
                }
                else
                {
                  echo '<div class="col-md-6 col-sm-6">
                          <div class="statusUsuarioTurmaAluno">
                            Aluno
                          </div>
                        </div>';
                }
                echo '</div>';
                $turmaIntegrante += 1;
              }
            ?>
                
                
              
            </div>
            <div id="desempenhoTurma">
              <h1>Gráficos</h1>
            </div>
          </div>
        </div>
      </div>
    </div>    
  </body>
  <script type="text/javascript">
      $(document).ready(
            function() {
              $("#dropdown").click(MostrarEsconderTurmas);
            }
          );
          function MostrarEsconderTurmas(){
            $("#dropdownTurmas").slideToggle(500);
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
        function alterarDiv(x){
          if(x == 'membrosTurma'){
            document.getElementById(x).style.display = "block";
            document.getElementById('desempenhoTurma').style.display = "none";
          }
          if(x == 'desempenhoTurma') {
            document.getElementById('desempenhoTurma').style.display = "block";
            document.getElementById('membrosTurma').style.display = "none";
          }
        }
      </script>
    </html>
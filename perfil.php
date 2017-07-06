<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">
			<title>Perfil</title>
      		<meta name="viewport" content="width=device-width, initial-scale=1">
      		<meta name="viewport" content="width=device-width, initial-scale=1">
			<link rel="shortcut icon" href="css/imagens/LOGO.ico" type="image/x-icon" />
			<link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
			<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
			<link rel="stylesheet" href="css/css.css">
		</head>
		<script src="javascript/jquery-3.1.0.min.js"></script>  
		<body onresize="fun()">

			<div id="containerAll">
		    <?php
		      //inicia a sessão
		      if (!isset($_SESSION)) session_start();
		      // Tenta se conectar ao servidor MySQL
		      $link = mysqli_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysqli_error($link));
		      // Tenta se conectar a um banco de dados MySQL
		      mysqli_select_db($link, 'matvirtual') or trigger_error(mysqli_error($link));
		      //niveis de dominio
		      //obtem soma de acertos
		      $query = "SELECT SUM(acertos) AS somaAcertos FROM desempenho WHERE (usuarioId = '".$_SESSION['usuarioId']."')";
		      $selectSumAcertos = mysqli_query($link, $query);
		      $row = mysqli_fetch_assoc($selectSumAcertos);
		      $somaAcertos = $row['somaAcertos'];
		      //obtem soma de erros
		      $query = "SELECT SUM(erros) AS somaErros FROM desempenho WHERE (usuarioId = '".$_SESSION['usuarioId']."')";
		      $selectSumErros = mysqli_query($link, $query);
		      $row = mysqli_fetch_assoc($selectSumErros);
		      $somaErros = $row['somaErros'];
		      if (!$selectSumAcertos && !$selectSumErros) {
		      	$dominio = 999;
		      }
		      //porcentagem de dominio
		      if ($somaAcertos != 0 && $somaErros != 0) $dominio = $somaAcertos/($somaAcertos+$somaErros)*100;
		    ?>
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
				                    <a href=\"cadastroQuestoesForm.php\">Administrar questões</a>
				                    </li>
				                    <li>
				                    <a href=\"cadastroAssuntosForm.php\">Administrar assuntos</a>
				                    </li>
				                    <!--<li>
				                    <a href=\"cadastroTurmasForm.php\">Administrar turmas</a>
				                    </li>-->";
				                  }
				                }
				                  ?>
				                </div>
				            </ul>
						</div>
					</div>
					<!-- Área de disposição dos cards da página -->
					<div class="col-md-10" id="containerPerfil"> 
						<div class="row">
							<div class="col-md-4 col-sm-4">
								<div id="cardInfoUsuario">
									 <?php 
						             $userid = $_SESSION['usuarioId'];
						             $sql1 = "SELECT * FROM usuarios WHERE id = '$userid'";
						             $query = mysqli_query($link, $sql1) or die(mysqli_error($link));
						             $resultado = mysqli_fetch_array($query);

									 echo "<h3>".$resultado['nome']."</h3>";
						             $mostraimagem = $resultado['linkFoto'];
						                echo "<img src="; echo  $mostraimagem; echo ">";      
						              ?>
									<br>

									<a onclick="abrirJanelaEditarPerfil('')" id="abrirModalEditarPerfil">Editar perfil</a>
								</div>
							</div>
							<div class="col-md-4 col-sm-4">
								<div id="cardDominioPerfil">
									<h4>Nível de domínio</h4>
									<h3 <?php if($dominio>50) echo "style=\"display:none;\"";?>>Básico</h3> <br>
									<img src="css/imagens/iconenoob.png" <?php if($dominio>50) echo "style=\"display:none;\"";?>> <br>
									<h3 <?php if($dominio<=50 || $dominio>70) echo "style=\"display:none;\"";?>>Intermediário</h3> <br>
									<img src="css/imagens/iconenaotaonoob.png" <?php if($dominio<50 || $dominio>70) echo "style=\"display:none;\"";?>> <br>
									<h3 <?php if($dominio<=70 || $dominio = 999) echo "style=\"display:none;\"";?>>Avançado</h3> <br>
									<img src="css/imagens/iconepro.png" <?php if($dominio<=70 || $dominio = 999) echo "style=\"display:none;\"";?>>  <br>
									<h3 <?php if($dominio != 999) echo "style=\"display:none;\"";?>>Não iniciado</h3> <br>
									<img src="css/imagens/iconexampson.png" <?php if($dominio != 999) echo "style=\"display:none;\"";?>> <br>
								</div>
							</div>
							<div class="col-md-4 col-sm-4">
								<div id="cardTurmasPerfil">
									<h4>Turmas</h4>
									<table id="perfilTurmasTable">
							            <?php 
							              //Iniciando sessão
										  if(!isset($_SESSION)) SESSION_start();
							              $sql = "SELECT id, turmaNome, codigoTurma FROM turmas ORDER BY turmaNome";
							              $select = mysqli_query($link, $sql);
							              if (!$select) {
							                echo "Ocorreu um erro no carregamento de turmas". mysqli_error($link);
							              }
							              $i = 0;
							              while($row = mysqli_fetch_assoc($select)) {
									            if (!isset($_SESSION['getIdEditar']))
									            {
									              $_SESSION['getIdEditar' + $i] = $row['id'];
									            }
								                $sql2 = "SELECT id FROM alunos WHERE turmas_id=".$row['id']." and usuarios_id=".$_SESSION['usuarioId'];
								                $select2 = mysqli_query($link, $sql2);
								                $row2 = mysqli_fetch_assoc($select2);
                								if($row2['id'] != null){
                									echo "<tr>
                											<td id=\"tdPerfilNomeTurma\">
		                										<form action=\"turma.php\" method=\"post\">
														          <input type=\"submit\" value='".$row['turmaNome']."' name=\"nomeTurma\" id=\"inputTurmaNome\">
														          <input type=\"hidden\" value='".$row['codigoTurma']."' name=\"codigoTurma\">
														        </form>
														    </td>";
														    if (isset($_SESSION)){
														    	if($_SESSION['usuarioTipo'] == 1){
																    echo "<td>
																        <form action=\"perfil.php#modalEditarTurma\" method=\"post\">
																          <input type=\"submit\" value=\"\" id=\"inputTurmaUpdate\" name=\"nomeEditarTurma\" onclick=\"abrirJanelaEditarTurma('modalEditarTurma')\">
																          <input type=\"hidden\" name=\"editarTurmas\" value='".$row['id']."' id=\"idEditarTurmaPerfil\"> <br>
																        </form>
																    </td>

																    <td>
																		<form action=\"turmasDelete.php\" method=\"post\">
																           <input type=\"submit\" value=\"X\" id=\"inputTurmaDelete\">
																           <input type=\"hidden\" name=\"turmas\" value='".$row['id']."'> 
																           <br>
															            </form>
															        </td>
													              </tr>
													              ";
											              		}
											          	  	}
											    }
											$i += 1;
							              }
							            ?><br>
							        </table>
									<table>
										<tr>
											<td>
												<a class="aPerfilTurma" onclick="abrirJanelaEntrarTurma('modalEntrarTurma')" id="aEntrarTurma">	Entrar em turma</a> &nbsp&nbsp
											</td>
											<?php
												if(isset($_SESSION['usuarioTipo']))
												{
													if($_SESSION['usuarioTipo'] == 1)
													{
														echo '<td>
															<a class="aPerfilTurma"  onclick="abrirJanelaCriarTurma(\'modalCriarTurma\')" id="aCriarTurma">Adicionar turma</a>
															</td>';
													}
												}
											?>
										</tr>
									</table>
								</div>
							</div>
						</div>	
					</div>
				</div>
				<!-- Janela Modal - Edição de perfil -->
				<div id="fundoModalEditarPerfil" onclick="fecharJanelaEditarPerfil('modalEditarPerfil')">
				</div>
				<div id="modalEditarPerfil" method="post" action="usuarioUpdate.php">
					<form>
						<?php
							if(isset($_SESSION))
							{
								$sql = "SELECT * FROM `usuarios` WHERE id = ".$_SESSION['usuarioId']."";
						        $select = mysqli_query($link, $sql);
						        $row = mysqli_fetch_assoc($select);
								echo 'Nome <br>
								<input id="editarNome" type="text" size="20" name="nomeUpdate" value="'.$row['nome'].'"> <br>
								Usuário <br>
								<input id="editarUsuario" type="text" name="usuarioUpdate" value="'.$row['Usuario'].'"> <br>
								E-mail <br>
								<input id="editarEmail" type="email" name="emailUpdate" value="'.$row['email'].'"> <br>
		          				<label for="fotoPerfilCadastro">Selecione uma imagem</label></br>
		          				<input id="fotoPerfilCadastro" type="file" name="fotoPerfilCadastro"></br>
								<input type="submit" id="botaoEditarPerfil" value="Editar dados">';
							}
						?>
					</form>
					<a class="fechar" onclick="fecharJanelaEditarPerfil('modalEditarPerfil')">x</a>
				</div>
				<!-- Janela Modal - Entrar em turma -->
				<div id="fundoModalEntrarTurma" onclick="fecharJanelaEntrarTurma('modalEntrarTurma')">
				</div>
				<div id="modalEntrarTurma" class="row">
					<form action="cadastroAlunos.php" method="post">
						Código da Turma <br>
						<input id="codigoEntrarTurma" type="text" size="20" name="codigoEntrarTurma"> <br>
						<input type="submit" id="botaoEntrarTurma" value="Entrar em turma">
					</form>
					<a class="fechar" onclick="fecharJanelaEntrarTurma('modalEntrarTurma')">x</a>
				</div>
				<!-- Janela Modal - Criar turma -->
				<div id="fundoModalCriarTurma" onclick="fecharJanelaCriarTurma('modalCriarTurma')">
				</div>
				<div id="modalCriarTurma" class="row">
					<form action="cadastroTurmas.php" method="post">
						Nome da Turma <br>
						<input id="nomeCriarTurma" type="text" size="20" name="nomeTurma"> <br>
						<input type="submit" id="botaoCriarTurma" value="Criar Turma">
					</form>
					<a class="fechar" onclick="fecharJanelaCriarTurma('modalCriarTurma')">x</a>
				</div>

				<!-- Janela Modal - Editar turma -->
				<!--<div id="fundoModalEditarTurma" onclick="fecharJanelaEditarTurma('modalEditarTurma')">
				</div>
				<div id="modalEditarTurma" class="row">
					<form action="editarTurmas.php" method="post">
						Novo nome da Turma <br>
						<input id="nomeEditarTurma" type="text" size="20" name="nomeTurma"> <br>
						<input type="submit" id="botaoEditarTurma" value="Editar Nome">
						<?php echo '<input id="idEditarTurma" type="button" name="editarTurmas value=\'$_SESSION["getIdEditar"]\'>';  echo $_SESSION["getIdEditar" + 1];?>
					</form>
					<a class="fechar" onclick="fecharJanelaEditarTurma('modalEditarTurma')">x</a>
				</div>-->
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
			function abrirJanelaEditarPerfil(x){
				document.getElementById(x).style.display = 'block';
				document.getElementById('fundoModalEditarPerfil').style.display = 'block';
			}
			function fecharJanelaEditarPerfil(x){
				document.getElementById(x).style.display = 'none';
				document.getElementById('fundoModalEditarPerfil').style.display = 'none';
			}
			
			function abrirJanelaEntrarTurma(x){
				document.getElementById(x).style.display = 'block';
				document.getElementById('fundoModalEntrarTurma').style.display = 'block';
			}
			function fecharJanelaEntrarTurma(x){
				document.getElementById(x).style.display = 'none';
				document.getElementById('fundoModalEntrarTurma').style.display = 'none';
			}
			
			function abrirJanelaCriarTurma(x){
				document.getElementById(x).style.display = 'block';
				document.getElementById('fundoModalCriarTurma').style.display = 'block';
			}
			function fecharJanelaCriarTurma(x){
				document.getElementById(x).style.display = 'none';
				document.getElementById('fundoModalCriarTurma').style.display = 'none';
			}
			
			function abrirJanelaEditarTurma(x){
				document.getElementById(x).style.display = 'block';
				document.getElementById('fundoModalEditarTurma').style.display = 'block';
				//document.getElementById('idEditarTurma').value = document.getElementById('idEditarTurmaPerfil').value;
			}
			function fecharJanelaEditarTurma(x){
				document.getElementById(x).style.display = 'none';
				document.getElementById('fundoModalEditarTurma').style.display = 'none';
			}
			
			$(document).ready(
					function() {
						$("#menuHamburger").click(MostrarEsconderMenu);
					}
				);
				function MostrarEsconderMenu(){
					$("#menuItens").slideToggle(500);
				}
			function fun(){
				if ($(window).width() > 990) {
				   document.getElementById('menuItens').style.display = "block";
				}
				else{
					document.getElementById('menuItens').style.display = "none";
				}
			}
		</script>
	</html>
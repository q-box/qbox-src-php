<html>
        <head>
  <meta charset="UTF-8">
        <link rel="shortcut icon" href="css/imagens/LOGO.ico" type="image/x-icon" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/css.css">
        <script src="javascript/jquery-3.1.0.min.js"></script>  
  </head>
  <script>
      function confirmacao(){
        window.alert('Assunto cadastrado com sucesso!');
      }

      function selecionar1(){
          document.getElementById('foto').disabled = false;
          document.getElementById('linkfoto').disabled = true;
      }

      function selecionar2(){
        document.getElementById('foto').disabled = true;
          document.getElementById('linkfoto').disabled = false;
      }

  </script>
        <body>
        <?php
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
        $link = mysqli_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysql_error());
        //tenta se conectar ao banco de dados MatematicaVirtual
        mysqli_select_db($link, 'matvirtual') or trigger_error(mysqli_error($link));
        $query = "SELECT nome, descricao, linkfoto, id FROM materias";
        $select = mysqli_query($link, $query);
        ?>
        <div class="container-fluid">
        <div class="row" id="topoAtividade">
            <div class="col-md-1">
                <a href="index.php">   <img id="logoAtividade" src="css/imagens/LOGO.png" onmouseover="this.src='css/imagens/LOGOhover.png'" onmouseout="this.src='css/imagens/LOGO.png'"></a>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-4">
                <div id="statusAtividade">
                    <div id="titAtividade">Cadastro de Matéria</div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
        </div>
        <div class="container-fluid">
  <form action="cadastroAssuntos.php" method="post" enctype="multipart/form-data">
  <legend><h2>Insira os dados da Matéria:</h2></legend>
        <h4>Nome:</h4><br><input type="text" name="nome" maxlength="25" /><br>
      <h4>Descrição:</h4><br><textarea name="descricao" maxlength="60"></textarea><br><br>
      <h4>Como irá cadastrar a imagem do assunto? (Recomendado usar imagens da proporção 200x166</h4>
      <input name="radio" onclick="selecionar1();" type="radio" id="radio1" />Usar Foto do computador<br>
        <input name="radio" onclick="selecionar2();" type="radio" id="radio2" />Usar link da internet<br>
      <div class="primeiro" id="primeiro">
        Link da imagem:<br><input type="text" name="linkfoto" maxlength="100" id="linkfoto"><br>
      </div><br>
      <div class="segundo" id="segundo">
        <label for="foto">Selecione uma imagem:</label><br><input type="file" name="foto" maxlength="100" disabled="disabled" id="foto"><br>
      </div><br>
        <input type="submit" value="Cadastrar Matéria" id="botaoConfirmar" onclick="confirmacao()"/>
        </form>
        <br>
        <h3>Assuntos cadastrados:</h3>
        <table>
            <thead>
              <tr>
                  <th>Nome</th>
                  <th>Descrição</th>
                  <th>LinkFoto</th>
              </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_row($select)) { ?>
              <tr>
                <td><?php echo $row[0]; ?></td>
                <td><?php echo $row[1]; ?></td>
                <td><?php echo $row[2]; ?></td>
                <td><form action="assuntosDelete.php" enctype="multipart/form-data" method="post"><input type="submit" class="botaoDeleteAssunto" value='Deletar' /><input type="hidden" name='assunto' value='<?php echo $row[3]; ?>'><input type="hidden" name='assuntoNome' value='<?php echo $row[0]; ?>'></form></td>
                <td><a onclick="abrirJanelaEditarAssuntos('modalEditarAssunto')" id="aEditarAssunto"> Editar Assuntos </a></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
        <div id="fundoModalEditarAssunto" onclick="fecharJanelaEditarAssuntos('modalEditarAssunto')">
        </div>
        <div id="modalEditarAssunto" class="row">
          <form action="assuntosUpdate.php" method="post">
            Nome do Assunto <br>
            <input id="nomeAssunto" type="text" size="20" name="nomeAssunto"> <br>
            Descricao <br>
            <input id="descricaoAssunto" type="text" size="20" name="descricaoAssunto"> <br>
            Link da foto <br>
            <input id="LinkFotoAssunto" type="text" size="20" name="linkFotoAssunto"> <br>
            <?php
            $query2 = "SELECT nome, descricao, linkfoto, id FROM materias";
            $select2 = mysqli_query($link, $query2); 
            while($row2 = mysqli_fetch_row($select2)) { ?>
            <input type="submit" id="botaoEditarAssunto" value="Substituir"><input type="hidden" name="update" value="<?php echo $row2[3]; ?>">
            <?php echo $row2[0]; 
            }
            ?>
          </form>
          <a class="fechar" onclick="fecharJanelaEditarAssuntos('modalEditarAssunto')">x</a>
        </div>
        <script type="text/javascript">
        function abrirJanelaEditarAssuntos(x){
          document.getElementById(x).style.display = 'block';
          document.getElementById('fundoModalEditarAssunto').style.display = 'block';
        }
        function fecharJanelaEditarAssuntos(x){
          document.getElementById(x).style.display = 'none';
          document.getElementById('fundoModalEditarAssunto').style.display = 'none';
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
        </body>
</html>
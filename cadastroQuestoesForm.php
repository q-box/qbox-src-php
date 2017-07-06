<html>
        <head>
  <meta charset="UTF-8">
        <link rel="shortcut icon" href="css/imagens/LOGO.ico" type="image/x-icon" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="css/bootsfap.min.css">
        <link rel="stylesheet" href="css/css.css">
        <script src="javascript/jquery-3.1.0.min.js"></script>  
  </head>
        <body>
        <script type="text/javascript">
          function selecionar1(){
          document.getElementById('foto').disabled = false;
          document.getElementById('linkfoto').disabled = true;
      }

      function selecionar2(){
        document.getElementById('foto').disabled = true;
          document.getElementById('linkfoto').disabled = false;
      }
        </script>
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
        // Tenta se conectar ao servidor MySQL
        mysql_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysql_error());
        // Tenta se conectar a um banco de dados MySQL
        mysql_select_db('matvirtual') or trigger_error(mysql_error());
        //seta valor default de nível
        if(isset($_SESSION['nivel']))
        $nivelDefault = $_SESSION['nivel'];
        else
        $nivelDefault = 1;
        if(isset($_SESSION['assunto']))
        $assuntoDefault = $_SESSION['assunto'];
        else
        $assuntoDefault = "";
        //seleciona lista de materias
        $sql = "SELECT nome FROM materias";
        $select = mysql_query($sql);
        $sql2 = "SELECT enunciado, linkVideo, assunto, nivel, id FROM questoes ORDER BY questoes.assunto";
        $select2 = mysql_query($sql2);
        if (!$select) {
            echo "Ocorreu um erro no carregamento de materias ". mysql_error();
        }
        ?>
        <div class="container-fluid">
        <div class="row" id="topoAtividade">
            <div class="col-md-1">
                <a href="index.php">   <img id="logoAtividade" src="css/imagens/LOGO.png" onmouseover="this.src='css/imagens/LOGOhover.png'" onmouseout="this.src='css/imagens/LOGO.png'"></a>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-4">
                <div id="statusAtividade">
                    <div id="titAtividade">Cadastro de questões</div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
        </div>
        <div class="container-fluid">
  <form action="cadastroQuestoes.php" method="post">
  <legend><h2>Insira os dados da nova questão:</h2></legend>
    Assunto:<br>
    <select name="assunto">
    <?php 
        while($row = mysql_fetch_assoc($select)) {     
            if($assuntoDefault == $row['nome'])$enable = "selected";
            else $enable = "macaco";                         
            echo "<option value = \"".$row['nome']."\" ".$enable.">".$row['nome']."</option>";
        } 
    ?>
    </select><br>
    Nível:<br>
    <select name="nivel">
      <option value="1" <?php if($nivelDefault == '1'){echo("selected");}?>>1</option>
      <option value="2" <?php if($nivelDefault == '2'){echo("selected");}?>>2</option>
      <option value="3" <?php if($nivelDefault == '3'){echo("selected");}?>>3</option>
    </select><br>
  Enunciado:<br><textarea rows='5' cols='100' name="enunciado"></textarea><br>
        Alternativa VERDADEIRA:<br><textarea rows='5' cols='100' name="alternativaVerdadeira"></textarea><br>
        Alternativa FALSA 1:<br><textarea rows='5' cols='100' name="alternativaFalsa1"></textarea><br>
        Alternativa FALSA 2:<br><textarea rows='5' cols='100' name="alternativaFalsa2"></textarea><br>
        Alternativa FALSA 3:<br><textarea rows='5' cols='100' name="alternativaFalsa3"></textarea><br>
        Link de vídeo(Disponível em youtube.com > Compartilhar > Inconrporar):<br><input type="text" name="linkVideo" size="62" /><br>
        Selecione o método pelo qual irá enviar a imagem:<br>
        <input name="radio" onclick="selecionar1();" type="radio" id="radio1" />Usar Foto do computador<br>
        <input name="radio" onclick="selecionar2();" type="radio" id="radio2" />Usar link da internet<br>
      <div class="primeiro" id="primeiro">
        Link da imagem:<br><input type="text" name="linkfoto" maxlength="100" id="linkfoto"><br>
      </div><br>
      <div class="segundo" id="segundo">
        <label for="foto">Selecione uma imagem:</label><br><input type="file" name="foto" maxlength="100" disabled="disabled" id="foto"><br>
      </div><br>
        <br>
        <input type="submit" value="Cadastrar Questão" id="botaoConfirmar" />
        <input type="reset" value="Limpar" id="botaoConfirmar" />    
        </form>
        <h3>Questões cadastrados:</h3><hr>
        <!-- busca de questoes -->
        <input type="text" id="inputBusca" onkeyup="buscaQuestao()" placeholder="buscar questao por assunto..." size="100">
        <table  style="border-collapse: collapse;" id="tabelaQuestoes">
            <thead>
              <tr>
                  <th>ID</th>
                  <th>Enunciado</th>
                  <th>Assunto</th>
                  <th>Nível</th>
              </tr>
              
            </thead>
            <tbody>
            <?php while($row2 = mysql_fetch_row($select2)) { ?>
              <tr style="border: 1px solid; border-color: #ccc;">
                <td style="border: 1px solid; border-color: #ccc;"><?php echo $row2[4]; ?></td>
                <td style="border: 1px solid; border-color: #ccc;"><?php echo $row2[0]; ?></td>
                <td style="border: 1px solid; border-color: #ccc;"><?php echo $row2[2]; ?></td>
                <td style="border: 1px solid; border-color: #ccc;"><?php echo $row2[3]; ?></td>
                <td><form action="questoesDelete.php" enctype="multipart/form-data" method="post"><input type="submit" value='Deletar' /><input type="hidden" name='questoes' value='<?php echo $row2[4]; ?>'></form></td>
                <td style="border: 1px solid; border-color: #ccc;"><a onclick="abrirJanelaEditarQuestão('modalEditarQuestão')" id="aEditarQuestão"> Editar Questão </a></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
        <div id="fundoModalEditarQuestão" onclick="fecharJanelaEditarQuestão('modalEditarQuestão')">
        </div>
        <div id="modalEditarQuestão" class="row">
          <form action="questoesUpdate.php" method="post">
            Assunto:
            <select name="assuntoUpdate">
            <?php 
               $sql3 = "SELECT nome FROM materias";
               $select3 = mysql_query($sql3);
               while($row3 = mysql_fetch_assoc($select3)) {                               
               echo "<option value = \"".$row3['nome']."\">".$row3['nome']."</option>";
               } 
            ?></select><br>
            Nível: <br>
            <select name="nivelUpdate">
            <option value="1" <?php if($nivelDefault == '1'){echo("selected");}?>>1</option>
            <option value="2" <?php if($nivelDefault == '2'){echo("selected");}?>>2</option>
            <option value="3" <?php if($nivelDefault == '3'){echo("selected");}?>>3</option>
            </select> <br>
            Enunciado:<br><input type="text" name="enunciadoUpdate"></input><br>
            Link de mídia(Disponível em youtube.com > Compartilhar > Inconrporar):<br><input type="text" name="linkVideoUpdate" size="62" /><br><br>
            <?php
            $query4 = "SELECT `id`, `enunciado` FROM `questoes`";
            $select4 = mysql_query($query4); 
            while($row4 = mysql_fetch_row($select4)) { 
             echo "ID: ".$row4[0]; 
            ?>
            <input type="submit" id="botaoEditarAssunto" value="Substituir"><input type="hidden" name="update" value="<?php echo $row4[0]; ?>"><br>
            <?php
            }
            ?>
          </form>
          <a class="fechar" onclick="fecharJanelaEditarQuestão('modalEditarQuestão')">x</a>
        </div>
        </body>

        <script type="text/javascript">
        function abrirJanelaEditarQuestão(x){
          document.getElementById(x).style.display = 'block';
          document.getElementById('fundoModalEditarQuestão').style.display = 'block';
        }
        function fecharJanelaEditarQuestão(x){
          document.getElementById(x).style.display = 'none';
          document.getElementById('fundoModalEditarQuestão').style.display = 'none';
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
        //barra de pesquisa
        function buscaQuestao() {
  // Declara variaveis 
  var input, filter, table, tr, td, i;
  input = document.getElementById("inputBusca");
  filter = input.value.toUpperCase();
  table = document.getElementById("tabelaQuestoes");
  tr = table.getElementsByTagName("tr");

  // circula por todas as linhas da tabela e esconde os itens nao correspondentes
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}
        </script>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="css/imagens/LOGO.ico" type="image/x-icon" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/css.css">
  <title>Exercícios</title>
</head>
<?php
  // Tenta se conectar ao servidor MySQL
      mysql_connect('matvirtual.mysql.dbaas.com.br', 'matvirtual', 'natal1010') or trigger_error(mysql_error());
    // Tenta se conectar a um banco de dados MySQL
      mysql_select_db('matvirtual') or trigger_error(mysql_error());
    // inicia sessão
        if(!isset($_SESSION)) session_start();
    // contador que determina numero da questão
            /*//contador de acertos e erros
            $contadorAcertos = 0;
            $contadorErros = 0;*/
        if(isset($_POST['contador'])){
            if (!isset($_SESSION['contadorAcertos']) && !isset($_SESSION['contadorErros'])) {
              $_SESSION['contadorAcertos'] = 0;
              $_SESSION['contadorErros'] = 0;
            }
            $_POST['contador'] += 1;
            $rowsQuestoes = $_SESSION['rowsQuestoes'];
            $validarQuestaoAnterior = 'nada';
            //validação de alternativas
            $progressao = $_SESSION['progressao'];
            if (isset($_POST['alt'])) {
              if ($_POST['alt'] == 1)
              {
                $validarQuestaoAnterior = 'acertou';
                $_SESSION['contadorAcertos'] += 1;
                $progressao[$_POST['contador']-1] = 'done'; 
              }
              else
              {
                $validarQuestaoAnterior = 'errou';
                $_SESSION['contadorErros'] += 1;
                $progressao[$_POST['contador']-1] = 'failed';
              }
            }
            else{
              $_SESSION['contadorErros'] += 1;
              $progressao[$_POST['contador']-1] = 'failed';
            }
            $_SESSION['progressao'] = $progressao;
            /*if (!isset($_SESSION['contadorAcertos']) && !isset($_SESSION['contadorErros'])) {
              $_SESSION['contadorAcertos'] = 0;
              $_SESSION['contadorErros'] = 0;
            }
            $_SESSION['contadorAcertos'] += $contadorAcertos;
            $_SESSION['contadorErros'] += $contadorErros;*/
        }
        else{
            $_POST['contador'] = 0;
            //declara array de progressao de questoes
            $_SESSION['progressao'] = array('todo','todo','todo','todo','todo','todo','todo','todo','todo','todo');
            // Seleciona 5 questoes aleatórias no db relacionadas ao assunto com nível 1 de dificuldade
            $sql = "SELECT id, enunciado, linkVideo, linkFoto FROM questoes WHERE assunto = '".$_POST['nomeAssunto']."' AND nivel = 1 ORDER BY rand() LIMIT 3";
            $select = mysql_query($sql);
            // Mensagem de erro caso não seja possível selecionar questões
            if(!$select){
                echo "Não foi possível obter uma lista de exercícios";
                die();
            }
            // guarda lista de quesões dentro de um array
            $rowsQuestoes = array();
            while($row=mysql_fetch_assoc($select)){
                $rowsQuestoes[] = $row;
            }
            // Seleciona 3 questoes aleatórias no db relacionadas ao assunto com nível 2 de dificuldade
            $sql = "SELECT id, enunciado, linkVideo, linkFoto FROM questoes WHERE assunto = '".$_POST['nomeAssunto']."' AND nivel = 2 ORDER BY rand() LIMIT 4";
            $select = mysql_query($sql);
            // Mensagem de erro caso não seja possível selecionar questões
            if(!$select){
                echo "Não foi possível obter uma lista de exercícios";
                die();
            }
            // guarda lista de quesões dentro de um array
            while($row=mysql_fetch_assoc($select)){
                $rowsQuestoes[] = $row;
            }
            // Seleciona 2 questoes aleatórias no db relacionadas ao assunto com nível 3 de dificuldade
            $sql = "SELECT id, enunciado, linkVideo, linkFoto FROM questoes WHERE assunto = '".$_POST['nomeAssunto']."' AND nivel = 3 ORDER BY rand() LIMIT 3";
            $select = mysql_query($sql);
            // Mensagem de erro caso não seja possível selecionar questões
            if(!$select){
                echo "Não foi possível obter uma lista de exercícios";
                die();
            }
            // guarda lista de quesões dentro de um array
            while($row=mysql_fetch_assoc($select)){
                $rowsQuestoes[] = $row;
            }
            $_SESSION['rowsQuestoes'] = $rowsQuestoes;
        }
    //determina enunciado da questão
        $enunciado = $rowsQuestoes[$_POST['contador']]['enunciado'];
        $linkVideo = $rowsQuestoes[$_POST['contador']]['linkVideo'];
        $linkFoto = $rowsQuestoes[$_POST['contador']]['linkFoto'];
    //seleciona 4 alternativas de acordo com a questao selecionada
        $sql = "SELECT * FROM alternativas WHERE questaoId = ".$rowsQuestoes[$_POST['contador']]['id']." ORDER BY rand()";
        $select = mysql_query($sql);
        // Mensagem de erro caso não seja possível selecionar alternativas
        if(!$select){
            echo "Não foi possível obter alternativas da questão";
            die();
        }
        // guarda alternativas dentro de um array
        $rowsAlternativas = array();
        while($row=mysql_fetch_assoc($select)){
            $rowsAlternativas[] = $row;
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
                    <div id="titAtividade"><?php echo $_POST['nomeAssunto'];?></div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div id="cardQuestao" class="row">
            <form action="<?php 
            if ($_POST['contador'] == 9) {
                echo "resultado.php";
            } 
            else echo "exercicios.php";    
            ?>" method="post">
                <ol class="progtrckr" data-progtrckr-steps="5">
                    <li class="progtrckr-<?php if(isset($progressao)){if ($progressao[0] == 'done') echo "done"; elseif ($progressao[0] == 'failed') echo "failed"; else echo "todo";} else echo "todo"; ?>"></li>
                    <li class="progtrckr-<?php if(isset($progressao)){if ($progressao[1] == 'done') echo "done"; elseif ($progressao[1] == 'failed') echo "failed"; else echo "todo";} else echo "todo"; ?>"></li>
                    <li class="progtrckr-<?php if(isset($progressao)){if ($progressao[2] == 'done') echo "done"; elseif ($progressao[2] == 'failed') echo "failed"; else echo "todo";} else echo "todo"; ?>"></li>
                    <li class="progtrckr-<?php if(isset($progressao)){if ($progressao[3] == 'done') echo "done"; elseif ($progressao[3] == 'failed') echo "failed"; else echo "todo";} else echo "todo"; ?>"></li>
                    <li class="progtrckr-<?php if(isset($progressao)){if ($progressao[4] == 'done') echo "done"; elseif ($progressao[4] == 'failed') echo "failed"; else echo "todo";} else echo "todo"; ?>"></li>
                    <li class="progtrckr-<?php if(isset($progressao)){if ($progressao[5] == 'done') echo "done"; elseif ($progressao[5] == 'failed') echo "failed"; else echo "todo";} else echo "todo"; ?>"></li>
                    <li class="progtrckr-<?php if(isset($progressao)){if ($progressao[6] == 'done') echo "done"; elseif ($progressao[6] == 'failed') echo "failed"; else echo "todo";} else echo "todo"; ?>"></li>
                    <li class="progtrckr-<?php if(isset($progressao)){if ($progressao[7] == 'done') echo "done"; elseif ($progressao[7] == 'failed') echo "failed"; else echo "todo";} else echo "todo"; ?>"></li>
                    <li class="progtrckr-<?php if(isset($progressao)){if ($progressao[8] == 'done') echo "done"; elseif ($progressao[8] == 'failed') echo "failed"; else echo "todo";} else echo "todo"; ?>"></li>
                    <li class="progtrckr-<?php if(isset($progressao)){if ($progressao[9] == 'done') echo "done"; elseif ($progressao[9] == 'failed') echo "failed"; else echo "todo";} else echo "todo"; ?>"></li>
                </ol>
                <br>
                <h1 id="questaoTitle">Questão <?php echo $_POST['contador']+1; ?></h1>
                <p id="enunciadoExercicios"><?php echo $enunciado; ?></p>
                <?php if (isset($linkVideo)) {
                    echo "<p align=\"center\"><iframe src=\"$linkVideo\" width=\"420\" height=\"345\" allowfullscreen></iframe></p><br>";
              } 
                if (isset($linkFoto)){
                    echo "<p align=\"center\"><img id=\"imagemEnunciado\" src=\"$linkFoto\"></p>";
                }
                ?>
                <table id="alternativas">
                    <input type="hidden" name="contador" value="<?php echo $_POST['contador']?>">
                    <tr class="alternativa" id="alternativaA" onclick="alteraCor('alternativaA')">
                        <td class="letra">
                            &nbsp &nbsp A ) &nbsp 
                        </td>
                        <td class="alternativaInput">
                            <input type="radio" name="alt" id="A" value="<?php echo $rowsAlternativas[0]['tipo'] ?>">    
                        </td>
                        <td>
                            <?php echo $rowsAlternativas[0]['alternativa']; ?><br>
                        </td>
                    </tr>

                    <tr class="alternativa" id="alternativaB" onclick="alteraCor('alternativaB')">
                        <td class="letra">
                            &nbsp &nbsp B ) &nbsp 
                        </td>
                        <td class="alternativaInput">
                            <input type="radio" name="alt" id="B" value="<?php echo $rowsAlternativas[1]['tipo'] ?>"> 
                        </td>
                        <td>
                            <?php echo $rowsAlternativas[1]['alternativa']; ?><br>
                        </td>
                    </tr>

                    <tr class="alternativa" id="alternativaC" onclick="alteraCor('alternativaC')">
                        <td class="letra">
                            &nbsp &nbsp C ) &nbsp 
                        </td>
                        <td class="alternativaInput">
                            <input type="radio" name="alt" id="C" value="<?php echo $rowsAlternativas[2]['tipo'] ?>">  
                        </td>
                        <td>
                            <?php echo $rowsAlternativas[2]['alternativa']; ?><br>
                        </td>
                    </tr>

                    <tr id="alternativaD" onclick="alteraCor('alternativaD')">
                        <td class="letra">
                            &nbsp &nbsp D ) &nbsp 
                        </td>
                        <td class="alternativaInput">
                            <input type="radio" name="alt" id="D" value="<?php echo $rowsAlternativas[3]['tipo'] ?>"> 
                        </td>
                        <td>   
                            <?php echo $rowsAlternativas[3]['alternativa']; ?><br>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="nomeAssunto" value="<?php echo $_POST['nomeAssunto'] ?>">
                <br>
                <div id="cardQuestaoBotoes">
                    <input type="submit" id="botaoConfirmar" value="<?php $_POST['contador'] == 9 ? print_r("Pronto!") : print_r("Próxima") ?>"onclick="revalidarQuestao()">
                    <input type="button" id="botaoVerificar" class="botaoExercicios" value="Verificar" onclick="verificarQuestao()">
                </div>
            </form>

                <?php /* echo "Contador: ",$_POST['contador']; ?>
                <?php echo "Contador acertos: ". $_SESSION['contadorAcertos']; ?>
                <?php echo "Contador erros: ". $_SESSION['contadorErros']; ?>
                <?php echo "Questão anterior: ". $validarQuestaoAnterior; */ ?>
        </div>
    </div>
</body>
<script type="text/javascript">
    function setColorRadioGroup(radioGroup) { 
        var radio = radioGroup;   
        if (radio[0].style) {   
            for (var r = 0; r < radio.length; r++)    
                if (radio[r].checked) swapColor(radio[r]);    
                else swapColor(radio[r]); 
        }
    } 
    function swapColor(oCheckbox) {  
    var pop = oCheckbox, checkedcolor = '#549A98';   
    while (pop.nodeType != 1 || pop.nodeName.toLowerCase() != 'tr')                             
    pop = pop.parentNode;        
        pop.style.backgroundColor = (oCheckbox.checked) ? checkedcolor : '#FFFFFF';
    }
    function alteraCor(x) {
        document.getElementById('botaoVerificar').style.display = "block";
        if(x == 'alternativaA' && document.getElementById('A').disabled == false){
            document.getElementById(x).style.backgroundColor = "#549A98";
            document.getElementById('alternativaB').style.backgroundColor = "#fff";
            document.getElementById('alternativaC').style.backgroundColor = "#fff";
            document.getElementById('alternativaD').style.backgroundColor = "#fff";
            document.getElementById('A').checked = true;
            document.getElementById('B').checked = false;
            document.getElementById('C').checked = false;
            document.getElementById('D').checked = false;
        }
        if(x == 'alternativaB' && document.getElementById('B').disabled == false){
            document.getElementById('alternativaA').style.backgroundColor = "#fff";
            document.getElementById(x).style.backgroundColor = "#549A98";
            document.getElementById('alternativaC').style.backgroundColor = "#fff";
            document.getElementById('alternativaD').style.backgroundColor = "#fff";
            document.getElementById('A').checked = false;
            document.getElementById('B').checked = true;
            document.getElementById('C').checked = false;
            document.getElementById('D').checked = false;
        }
        if(x == 'alternativaC' && document.getElementById('C').disabled == false){
        document.getElementById('alternativaA').style.backgroundColor = "#fff";
            document.getElementById('alternativaB').style.backgroundColor = "#fff";
            document.getElementById(x).style.backgroundColor = "#549A98";
            document.getElementById('alternativaD').style.backgroundColor = "#fff";
            document.getElementById('A').checked = false;
            document.getElementById('B').checked = false;
            document.getElementById('C').checked = true;
            document.getElementById('D').checked = false;
        }
        if(x == 'alternativaD' && document.getElementById('D').disabled == false){
            document.getElementById('alternativaA').style.backgroundColor = "#fff";
            document.getElementById('alternativaB').style.backgroundColor = "#fff";
            document.getElementById('alternativaC').style.backgroundColor = "#fff";
            document.getElementById(x).style.backgroundColor = "#549A98";
            document.getElementById('A').checked = false;
            document.getElementById('B').checked = false;
            document.getElementById('C').checked = false;
            document.getElementById('D').checked = true;
        }
    }

    function verificarQuestao()
    {
        var alternativaA = false;
        var alternativaB = false;
        var alternativaC = false;
        var alternativaD = false;
        if(document.getElementById('A').checked == true)
        {
            alternativaA = true;
        }
        if(document.getElementById('B').checked == true)
        {
            alternativaB = true;
        }
        if(document.getElementById('C').checked == true)
        {
            alternativaC = true;
        }
        if(document.getElementById('D').checked == true)
        {
            alternativaD = true;
        }
        document.getElementById('A').disabled = true;
        document.getElementById('B').disabled = true;
        document.getElementById('C').disabled = true;
        document.getElementById('D').disabled = true;
        if (alternativaA == true){ document.getElementById('A').checked = true; }
        if (alternativaB == true){ document.getElementById('B').checked = true; }
        if (alternativaC == true){ document.getElementById('C').checked = true; }
        if (alternativaD == true){ document.getElementById('D').checked = true; }
        document.getElementById('alternativaA').style.cursor = "default";
        document.getElementById('alternativaB').style.cursor = "default";
        document.getElementById('alternativaC').style.cursor = "default";
        document.getElementById('alternativaD').style.cursor = "default";
        if (document.getElementById('A').value == 1)
        {
            document.getElementById('alternativaA').style.backgroundColor = "#66BB6A";
        }
        if (document.getElementById('B').value == 1)
        {
            document.getElementById('alternativaB').style.backgroundColor = "#66BB6A";
        }
        if (document.getElementById('C').value == 1)
        {
            document.getElementById('alternativaC').style.backgroundColor = "#66BB6A";
        }
        if (document.getElementById('D').value == 1)
        {
            document.getElementById('alternativaD').style.backgroundColor = "#66BB6A";
        }
    }
    function revalidarQuestao()
    {
        document.getElementById('A').disabled = false;
        document.getElementById('B').disabled = false;
        document.getElementById('C').disabled = false;
        document.getElementById('D').disabled = false;
    }
</script>

</html>
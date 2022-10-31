<?php
########################################################################################################################################################
# Programa..> PA Recursivo consultando uma tabela em uma base de dados
# Descrição.> Desenvolvimento de PA em PHP, usando a função de ambiente ISSET() e os comandos de desvio condicional: IF () THEN...ELSE e SWITCH...CASE.
# Autor.....> JMH
# Observação> O código final será disponibilizado no repositório de códigos do site da disciplina
#             Referencia boa para estudo do PHP: http://www.php.net
# Criacao...> 2022-10-17
# Alteração.> 2022-10-20 - Incorporação dos PA das turmas da tarde e noite para postagem no repositório.
########################################################################################################################################################


# o comando IF anterior pode ser trocado por um operador ternário na forma:
$bloco= ( ISSET($_REQUEST['bloco']) ) ? $_REQUEST['bloco'] : 1 ;
# Estabelecendo a conexão do PA com a Base de Dados
$link=mysqli_connect("localhost","root","","ilp540"); # retorna o número de uma conexão.
# Depois que acontece a conexão pode-se executar comandos de leitura nas tabelas da Base de Dados.
# Um Guia (resumo) de comandos SQL está disponível em:
# https://www.fatecourinhos.edu.br/disciplinas/ilp540/exercprojs/projeto2-php/exfnts/2022.2-Guia(curto)SQL.sql
# iniciando as tags da página
printf("<html>\n<head>\n<title>PA-Rec</title>\n</head>\n<body>\n");
switch (TRUE)
{
  case ( $bloco==1 ):
  { # bloco #1 - Lendo uma tabela 'projetando' campos para montar um form com uma caixa de seleção para escolher UM a ser exibido 'case 2'.
    # escrevendo o comando SQL em uma variável
    $cmdsql='select idempresa, txnomeusual from empresas order by txnomeusual';
    # 'Executando' a variável no SGBD
    $execcmd=mysqli_query($link,$cmdsql);
    # A Função de Ambiente PHP-MariaDB 'mysqli_query()' retorna um vetor estruturado com três partes:
    #   -- Nomes das tabelas, nomes dos campos e endereços dos registros processados no SQL.
    printf("<form action='empresasconsultar.php' method='POST'>\n");
    # Este form DEVE ter um campo 'oculto' que rece o valor 2 e nome 'bloco' para o PA poder ser recursivo.
    # Sendo assim...:
    printf("<input type='hidden' name='bloco' value='2'>\n");
    # Agora escrevemos o inicio da caixa de seleção (daqui em diante referencio este elemento HTML como 'picklist').
    printf("Escolha uma empresa: <select name='idempresa'>\n");
    # as linhas da picklist serão montadas com a TAG <option>...</option> e devem ser montadas 'lendo' as linhas do comando executado no BD.
    # A PHP dispõe da função mysqli_fetch_arry() que retona em um vetor os dados da linha apontada no primeiro endereço de registro de mysqli_query().
    # então podemos 'repetir' a leitura...
    while ( $rec=mysqli_fetch_array($execcmd) )
    { # neste laço montamos a TAG <option></option>
      printf("<option value='$rec[idempresa]'>$rec[txnomeusual]-($rec[idempresa])</ioption>");
    }
    # Agora encerramos a formatação da picklist 'fechando' a TAG <select> e montando os botões do form
    printf("</select>");
    # A TAG <button> permite que um form tenha mais de um botão 'submit' recebendo valores diferentes na mesma posição do vetor que lê a STD.
    printf("<button type='reset'  name='btreset' >Limpar form</button>");
    printf("<button type='submit' name='btenvio' value='2'>Escolher</button>");
    printf("</form>\n");
    break;
  }
  case ( $bloco==2 ):
  { # bloco #2 - Aqui vamos 'ler' os dados digitados nos campos do form do 'case 1'
    # A conexão já existe (foi criada 'fora' do SWITCH...CASE).
    # Lendo o registro 'inteiro' da tabela
    $cmdsql="SELECT * FROM empresas WHERE idempresa='$_REQUEST[idempresa]'";
    # Se quiser conferir o conteúdo desta variável, retire o comentário da linha seguinte:
    # printf("$cmdsql<br>\n");
    # Executando o comando e já montando o vetor com o registro lido
    $reg=mysqli_fetch_array(mysqli_query($link,$cmdsql));
    # Montando a apresentação dos dados em uma tabela.
    printf("<table>
            <tr><td>Código</td>          <td>$reg[idempresa]</td></tr>
            <tr><td>Nome da empresa</td>            <td>$reg[txnomeusual]</td></tr>
            <tr><td>Razão Social</td>             <td>$reg[txrazaosocial]</td></tr>
            <tr><td>Logradouro</td>   <td>$reg[logradouroid]</td></tr>
            <tr><td>Complemento</td><td>$reg[txcomplemento]</td></tr>
            <tr><td>CEP</td>    <td>$reg[nucepempresa]</td></tr>
            <tr><td>Setor de Atuação</td>  <td>$reg[setordeatuacaoid]</td></tr>
            <tr><td>Data de geração da empresa</td>     <td>$reg[dtcadempresa]</td></tr>
            <tr><td></td>                <td><button onclick='history.go(-1)'>Voltar</button></td></tr>
            </table>");
    printf("\n");
    break;
  }
}
# terminando as tags da página
printf("</body>\n</html>\n");
?>
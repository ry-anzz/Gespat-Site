<?php

include('../conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $chave = $_POST['chave'];

    // Monta a query diretamente (sem proteção contra SQL Injection)
    $sql = "SELECT * FROM usuario WHERE login = '$email' AND chave = '$chave'";
    
    // Executa a query
    $result = mysqli_query($con, $sql);

    // Verifica se a query foi executada corretamente
    if (!$result) {
        // Se houver erro na execução da query, exibe a mensagem de erro do MySQL
        die("Erro na consulta: " . mysqli_error($con));
    }

    // Verifica se retornou algum resultado
    if (mysqli_num_rows($result) > 0) { 
        // Gera um código aleatório de 6 dígitos
        $codigo_verificacao = rand(100000, 999999);

        // Atualiza o campo 'codigo_verificacao' no banco para o usuário correspondente
        $sql_update = "UPDATE usuario SET codigo_verificacao = '$codigo_verificacao' WHERE login = '$email'";
        mysqli_query($con, $sql_update);

        // Configurações do e-mail
        $para = $email;
        $assunto = "Código de Verificação";
        $mensagem = "Seu código de verificação é: $codigo_verificacao.";
        $cabecalhos = "From: noreply@seusite.com";

        // Envia o e-mail
        if (mail($para, $as sunto, $mensagem, $cabecalhos)) {
            echo "O e-mail com o código de verificação foi enviado.";
            // Redireciona para a página de confirmação do código
            header("Location: ../components-login/codigo.php");
            exit();
        } else {
            echo "Falha ao enviar o e-mail.";
        }
    } else {
        // Exibe mensagem de erro caso não encontre o e-mail ou chave correspondentes
        echo "E-mail ou código de acesso inválidos.";
    }

    // Fecha a conexão
    mysqli_close($con);
}
?>


  

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Esqueceu sua senha - GESPAT</title>
    <link rel="stylesheet" href="../styles/recuperaca.css" />
  </head>
  <body>
    <div class="container">
      <div class="login-box">
        <a href="../components-login/login.html" class="back-arrow">&larr;</a>
        <img src="../assets/logo.png" alt="GESPAT Logo" class="logo" />
        <h2>Esqueceu sua senha</h2>
        <p>Um código será enviado no seu email</p>
        <form action="" method="post">
          <div class="input-group">
            <label for="email">Email</label>
            <input
              type="text"
              id="email"
              name="email"
              placeholder="funcionario@gmail.com"
            />
          </div>
          <div class="input-group">
            <label for="access-code">código de acesso</label>
            <input
              type="text"
              id="access-code"
              name="chave"
              placeholder=""
            />
          </div>
          <button type="submit">Enviar</button>
        </form>
      </div>
    </div>
  </body>
</html>

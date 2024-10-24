<?php
include '../conexao.php'; 

$patrimonios = null; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "SELECT * FROM patrimonio";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $patrimonios = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "<p>Nenhum patrimônio cadastrado foi encontrado.</p>";
    }
}
$con->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gerar Relatório</title>
    <link rel="stylesheet" href="../styles/relatorio.css" />
  </head>
  <body>
    <div id="section-relatorio" class="content-section">
      <h2>Gerar relatório</h2>
      <p>+ Gere relatórios de patrimônios cadastrados</p>

      <!-- Formulário para gerar relatório -->
      <div class="form-content">
        <form method="POST" action="">
          <div class="form-group-patrimonio">
            <label for="tipo-relatorio">Relatório</label>
            <select id="tipo-relatorio" name="tipo-relatorio">
              <option value="todos">Todos os Patrimônios</option>
              <!-- Outras opções de filtros podem ser adicionadas aqui -->
            </select>
          </div>
          <button type="submit" class="btn-patrimonio">Gerar Relatório</button>
        </form>
      </div>

      <!-- Exibir a tabela de patrimônios cadastrados se houver dados -->
      <?php if ($patrimonios): ?>
        <div class="table-content">
          <table border="1">
            <thead>
              <tr>
                <th>Código</th>
                <th>Fabricante</th>
                <th>Cor</th>
                <th>Número de Série</th>
                <th>Descrição</th>
                <th>Departamento</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($patrimonios as $patrimonio): ?>
                <tr>
                  <td><?php echo $patrimonio['codigo']; ?></td>
                  <td><?php echo $patrimonio['fabricante']; ?></td>
                  <td><?php echo $patrimonio['cor']; ?></td>
                  <td><?php echo $patrimonio['n_serie']; ?></td>
                  <td><?php echo $patrimonio['descricao']; ?></td>
                  <td><?php echo $patrimonio['fk_departamento_nome']; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </body>
</html>

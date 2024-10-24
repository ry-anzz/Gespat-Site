<?php
include '../conexao.php'; 

$patrimonio = null;
$erro = null;
$message = ''; 

// Consulta ao patrimônio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo-consulta'])) {
    if (!empty($_POST['codigo-consulta'])) {
        $codigo = $_POST['codigo-consulta'];

        if ($con) {
            $sql = "SELECT * FROM patrimonio WHERE codigo = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $codigo);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $patrimonio = $result->fetch_assoc();
            } else {
                $erro = "Patrimônio não encontrado.";
            }
        } else {
            $erro = "Erro de conexão com o banco de dados.";
        }
    } else {
        $erro = "Por favor, insira um código válido.";
    }
}

// Atualização do patrimônio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
    $codigo = $_POST['codigo'];
    $fabricante = $_POST['fabricante'];
    $cor = $_POST['cor'];
    $numero_serie = $_POST['numero-serie'];
    $descricao = $_POST['descricao'];
    $departamento = $_POST['departamento'];

    $sql = "UPDATE patrimonio SET fabricante = ?, cor = ?, n_serie = ?, descricao = ?, fk_departamento_nome = ? WHERE codigo = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssss", $fabricante, $cor, $numero_serie, $descricao, $departamento, $codigo);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $message = "Patrimônio alterado com sucesso!";
        $patrimonio = null; 
    } else {
        $message = "Erro ao alterar patrimônio.";
    }
    $stmt->close();
}

if (isset($con)) {
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../styles/consultar.css" />
    <title>Consultar/Alterar</title>
    <style>
        .form-content {
            margin-bottom: 20px; /* Espaçamento entre os formulários */
        }
        .form-group-patrimonio {
            margin-bottom: 15px; /* Espaçamento entre os grupos de input */
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
        .result-title {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            width: 400px;
            text-align: center;
            border-radius: 10px;
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }
        .message.error {
            background-color: #ffdddd;
            color: #d8000c;
        }
        .message.success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .close-btn {
            background: none;
            border: none;
            color: inherit;
            font-size: 1.2em;
            position: absolute;
            right: 10px;
            top: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="section-consultar" class="content-section">
        <h2>Consultar / Alterar</h2>
        <p>+ Consulte ou altere um patrimônio existente</p>

        <!-- Mensagem de sucesso ou erro -->
        <?php if (!empty($message)): ?>
            <div class="message <?php echo (strpos($message, 'Erro') !== false) ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
                <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
            </div>
        <?php endif; ?>
        
        <?php if ($erro): ?>
            <div class="message error">
                <?php echo $erro; ?>
                <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
            </div>
        <?php endif; ?>

        <!-- Formulário para consulta -->
        <div class="form-content">
            <form method="POST" action="">
                <div class="form-group-patrimonio">
                    <label for="codigo-consulta">Código</label>
                    <input
                        type="number"
                        id="codigo-consulta"
                        name="codigo-consulta"
                        placeholder="Código do patrimônio"
                        required
                    />
                </div>
                <button type="submit" class="btn-patrimonio">Consultar</button>
            </form>
        </div>

        <?php if ($patrimonio): ?>
            <!-- Exibir os campos preenchidos com os dados do patrimônio -->
            <div class="form-content">
                <h3 class="result-title">Dados do Patrimônio Encontrado</h3> <!-- Título para a seção -->
                <form method="POST" action="">
                    <div class="form-group-patrimonio">
                        <label for="codigo">Código</label>
                        <input type="text" id="codigo" name="codigo" value="<?php echo $patrimonio['codigo']; ?>" readonly>
                    </div>
                    <div class="form-group-patrimonio">
                        <label for="fabricante">Fabricante</label>
                        <input type="text" id="fabricante" name="fabricante" value="<?php echo $patrimonio['fabricante']; ?>">
                    </div>
                    <div class="form-group-patrimonio">
                        <label for="cor">Cor</label>
                        <input type="text" id="cor" name="cor" value="<?php echo $patrimonio['cor']; ?>">
                    </div>
                    <div class="form-group-patrimonio">
                        <label for="numero-serie">Número de série</label>
                        <input type="text" id="numero-serie" name="numero-serie" value="<?php echo $patrimonio['n_serie']; ?>">
                    </div>
                    <div class="form-group-patrimonio">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao"><?php echo $patrimonio['descricao']; ?></textarea>
                    </div>
                    <div class="form-group-patrimonio">
                        <label for="departamento">Departamento</label>
                        <input type="text" id="departamento" name="departamento" value="<?php echo $patrimonio['fk_departamento_nome']; ?>">
                    </div>
                    <button type="submit" class="btn-patrimonio">Alterar</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

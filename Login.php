
<?php
session_start();

// Caminho do arquivo que guarda os usuários
$arquivoUsuarios = 'usuarios.txt';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $senha = trim($_POST['senha']);
    $tipo = $_POST['tipo']; // 'novo' ou 'existente'

    // Lê os usuários existentes
    $usuarios = file_exists($arquivoUsuarios) ? file($arquivoUsuarios, FILE_IGNORE_NEW_LINES) : [];
    $usuariosAssoc = [];

    foreach ($usuarios as $linha) {
        list($user, $pass) = explode('|', $linha);
        $usuariosAssoc[$user] = $pass;
    }

    if ($tipo === 'novo') {
        if (isset($usuariosAssoc[$nome])) {
            $erro = 'Usuário já existe!';
        } else {
            // Salva novo usuário
            file_put_contents($arquivoUsuarios, "$nome|$senha\n", FILE_APPEND);
            $_SESSION['usuario'] = $nome;
            header("Location: catalogo.php");
            exit;
        }
    } elseif ($tipo === 'existente') {
        if (isset($usuariosAssoc[$nome]) && $usuariosAssoc[$nome] === $senha) {
            $_SESSION['usuario'] = $nome;
            header("Location: catalogo.php");
            exit;
        } else {
            $erro = 'Usuário ou senha incorretos!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login/Cadastro</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-size: cover;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            
}
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('img/download (4).gif') no-repeat center center fixed;
            background-size: cover;
            filter: blur(5px);
            z-index: -1; /* Coloca atrás do conteúdo */
        }
        
        .container {
            background:rgba(12, 70, 24, 0.61);
            padding: 20px;
            border-radius: 10px;
            width: 300px;
        }
        input, select, button {
            width: 100%;
            max-width: 260px;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: none;
            display: block;
            margin-left: auto;
            margin-right: auto;
}
/* Placeholder mais suave */
input::placeholder {
    color:rgb(11, 50, 59);
}

/* Quando clica no campo */
input:focus, select:focus {
    border-color:rgb(71, 255, 80);
    outline: none;
    background-color: rgba(255, 255, 255, 0.2);
    color:rgb(0, 0, 0);
}

/* Botão com cor personalizada */
button {
    background-color:rgb(71, 145, 255);
    color: white;
    font-weight: bold;
    transition: background 0.3s ease;
}

button:hover {
    background-color: #ff6b81;
    cursor: pointer;
}
        
        .erro {
            color:rgb(255, 5, 5);
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Login / Cadastro</h2>
    <form method="POST">
        <select name="tipo" required>
            <option value="existente">Usuário Existente</option>
            <option value="novo">Novo Usuário</option>
        </select>
        <input type="text" name="nome" placeholder="Nome de usuário" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
    <?php if ($erro): ?>
        <div class="erro"><?= $erro ?></div>
    <?php endif; ?>
</div>
</body>
</html>

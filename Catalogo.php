<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: Login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
$arquivo = 'animes.json';

// Carrega todos os animes
$todosAnimes = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];

// Excluir anime (apenas do usuário atual)
if (isset($_GET['excluir'])) {
    $idExcluir = $_GET['excluir'];
    $todosAnimes = array_filter($todosAnimes, function ($anime) use ($idExcluir, $usuario) {
        return !($anime['id'] === $idExcluir && $anime['usuario'] === $usuario);
    });
    file_put_contents($arquivo, json_encode(array_values($todosAnimes), JSON_PRETTY_PRINT));
    header("Location: Catalogo.php");
    exit;
}

// Filtra os animes só do usuário logado
$animes = array_filter($todosAnimes, function($anime) use ($usuario) {
    return isset($anime['usuario']) && $anime['usuario'] === $usuario;
});

// Ver anime
$animeSelecionado = null;
if (isset($_GET['ver'])) {
    $idVer = $_GET['ver'];
    foreach ($animes as $anime) {
        if ($anime['id'] === $idVer) {
            $animeSelecionado = $anime;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Animes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('img/Nothing happened_.gif');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            color: white;
            margin: 0;
            padding: 0;
            position: relative;
            z-index: 0;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: -1;
        }

        header {
            background-color: #1f1f1f;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav {
            display: flex;
            gap: 15px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            background-color: #333;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #555;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .anime-card {
            background-color: #1f1f1f;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            width: 200px;
        }

        .anime-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 5px;
        }

        .botoes {
            margin-top: 10px;
        }

        .botoes a {
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            margin: 0 5px;
        }

        .ver {
            background-color: #3498db;
        }

        .excluir {
            background-color: #e74c3c;
        }

        .voltar {
            background-color: rgb(255, 230, 0);
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .voltar:hover {
            background-color: rgb(177, 153, 17);
            transform: scale(1.05);
        }

        .detalhes-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 30px;
        }

        .detalhes {
            background-color: #2c2c2c;
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            text-align: center;
        }

        h1 {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 12px 20px;
            border-radius: 8px;
            display: block;
            width: fit-content;
            margin: 30px auto;
            color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <strong>Bem-vindo, <?= htmlspecialchars($usuario) ?>!</strong>
        </div>
        <nav>
            <a href="Catalogo.php">Catálogo</a>
            <a href="Cadastrar.php">Cadastrar</a>
            <a href="Login.php">Sair</a>
        </nav>
    </header>

    <h1>Catálogo de Animes</h1>

    <?php if ($animeSelecionado): ?>
        <div class="detalhes-container">
            <div class="detalhes">
                <h2><?= htmlspecialchars($animeSelecionado['titulo']) ?></h2>
                <img src="<?= htmlspecialchars($animeSelecionado['imagem']) ?>" alt="Imagem do anime" style="width: 100%; max-width: 300px; height: 400px; object-fit: cover; border-radius: 5px;">
                <p><strong>Gênero:</strong> <?= htmlspecialchars($animeSelecionado['genero']) ?></p>
                <p><strong>Ano:</strong> <?= htmlspecialchars($animeSelecionado['ano']) ?></p>
                <p><strong>Produtor:</strong> <?= htmlspecialchars($animeSelecionado['produtor']) ?></p>
                <p><strong>Distribuidora:</strong> <?= htmlspecialchars($animeSelecionado['distribuidora']) ?></p>
                <p><strong>Nota:</strong> <?= str_repeat('★', (int)$animeSelecionado['nota']) ?></p>
                <p><strong>Descrição:</strong> <?= htmlspecialchars($animeSelecionado['descricao']) ?></p>
                <a href="Catalogo.php" class="voltar">Voltar ao Catálogo</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <?php foreach ($animes as $anime): ?>
            <?php if (!$animeSelecionado || $anime['id'] !== $animeSelecionado['id']): ?>
            <div class="anime-card">
                <h3><?= htmlspecialchars($anime['titulo']) ?></h3>
                <img src="<?= htmlspecialchars($anime['imagem']) ?>" alt="Imagem do anime">
                <div class="botoes">
                    <a class="ver" href="?ver=<?= $anime['id'] ?>">Ver</a>
                    <a class="excluir" href="?excluir=<?= $anime['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este anime?')">Excluir</a>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</body>
</html>

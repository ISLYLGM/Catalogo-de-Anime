<?php 
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: Login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
$arquivo = 'animes.json';

// Recarrega todos os animes do arquivo
$todosAnimes = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];

// Filtra apenas os animes do usuário logado
$animes = array_filter($todosAnimes, function($anime) use ($usuario) {
    return isset($anime['usuario']) && $anime['usuario'] === $usuario;
});

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoAnime = [
        'id' => uniqid(),
        'usuario' => $usuario,
        'titulo' => $_POST['titulo'],
        'genero' => $_POST['genero'],
        'ano' => $_POST['ano'],
        'produtor' => $_POST['produtor'],
        'distribuidora' => $_POST['distribuidora'],
        'nota' => $_POST['nota'],
        'descricao' => $_POST['descricao'],
        'imagem' => $_POST['imagem']
    ];

    // Adiciona o novo anime à lista geral
    $todosAnimes[] = $novoAnime;

    // Salva todos os animes de volta no arquivo
    file_put_contents($arquivo, json_encode($todosAnimes, JSON_PRETTY_PRINT));

    // Redireciona para o catálogo
    header("Location: Catalogo.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Anime</title>
    <style>
    * {
        box-sizing: border-box;
    }

    body {
            font-family: Arial, sans-serif;
            background-image: url('img/download (5).gif');
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
        padding: 40px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    h2 {
    text-align: center;
    margin-bottom: 30px;
    background-color: rgba(0, 0, 0, 0.6); /* fundo escuro com transparência */
    padding: 12px 20px;
    border-radius: 8px;
    display: inline-block;
    color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* opcional: sombra suave */
}


    form {
        width: 100%;
        max-width: 500px;
        background-color: #1c1c1c;
        padding: 30px;
        border-radius: 10px;
    }

    input, textarea, select {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: none;
    background-color: #2c2c2c;
    color: white;
    font-size: 14px;
    display: block;
    resize: none; /* impede o redimensionamento do textarea */
}
    input::placeholder,
    textarea::placeholder {
        color: #aaa;
    }

    select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color:rgb(112, 0, 146);
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    button:hover {
        background-color:rgb(166, 76, 189);
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

    <div class="container">
        <h2>Cadastrar Novo Anime</h2>
        <form method="post">
            <input type="text" name="titulo" placeholder="Nome do anime:" required>
            <input type="text" name="genero" placeholder="Gênero:" required>
            <input type="number" name="ano" placeholder="Ano:" required>
            <input type="text" name="produtor" placeholder="Produtor:" required>
            <input type="text" name="distribuidora" placeholder="Distribuidora:" required>
            <select name="nota" required>
                <option value="">Nota</option>
                <option value="1">★</option>
                <option value="2">★★</option>
                <option value="3">★★★</option>
                <option value="4">★★★★</option>
                <option value="5">★★★★★</option>
            </select>
            <textarea name="descricao" placeholder="Descrição:" rows="4" required></textarea>
            <input type="url" name="imagem" placeholder="Imagem (URL):" required>
            <button type="submit">Cadastrar Anime</button>
        </form>
    </div>
</body>
</html>

<?php 

    // incuir o auto load do composer para carregar automaticamente as classes utilizadas
    require_once __DIR__ . '/../vendor/autoload.php';

    // incluir o arquivo com as variaveis
    require_once __DIR__ . '/../config/config.php';

    session_start();

    // inserir a classe de autenticação
    use Services\Auth;

    // Inicializa a variável para mensagens de erro
    $mensagem = '';

    // instanciar a classe de autenticação
    $auth = new Auth();

    // verifica se já foi autenticado
    if (Auth::verificarLogin()) {
        echo "Usuário já autenticado. Redirecionando...";
        header('Location: index.php');
        exit;
    }

    // verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "Formulário enviado. Verificando login...";
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
    
        if ($auth->login($username, $password)) {
            echo "Login bem-sucedido. Redirecionando...";
            header('Location: index.php');
            exit;
        } else {
            $mensagem = 'Usuário ou senha incorretos!';
        }
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Login</title>
    <style>
        .login-container{
            max-width: 400px;
            margin: 100px auto;
        }
        .password-toggle{
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light">
    <div class="login-container">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Login</h4>
            </div>

            <div class="card-body">

                <?php if($mensagem): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($mensagem) ?></div>
                <?php endif; ?>

                <form action="" method="post" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="" class="form-label">Usuario:</label>
                        <input type="text" placeholder="Digite seu usuario" name="username" class="form-control" require autocomplete="off">
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Senha:</label>
                        <input type="password" placeholder="Digite sua senha" name="password" class="form-control" id="password" require>
                        <span class="password-toggle mt-3" onclick="togglePassword()"><i class="bi bi-eye-slash-fill" id="olho"></i></span>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            let passwordInput = document.getElementById('password');
            let olho = document.getElementById('olho');
            passwordInput.type = (passwordInput.type === 'password') ? 'text' : 'password';
            
            // Alterna entre as classes do ícone do olho
            olho.classList.toggle('bi-eye-slash-fill');
            olho.classList.toggle('bi-eye');
        }
    </script>
</body>
</html>
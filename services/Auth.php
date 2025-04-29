<?php
    // define espaço para organização do código
    namespace Services;

    class Auth{
        private array $usuarios = [];

        // método contrutor
        public function __construct(){
            $this->carregarUsuarios();
        }

        // método para carregar usuários do arquivo JSON
        private function carregarUsuarios(): void {
            // verificar se o arquivo existe
            if (!file_exists(ARQUIVO_USUARIOS)) {
                // lê o conteudo e decodifica o JSON para o array
                $conteudo = json_decode(file_get_contents(ARQUIVO_USUARIOS), true);

                // verifica se o conteudo é um array
                $this->usuarios = is_array($conteudo) ? $conteudo : [];
            } else {
                // criar o arquivo JSON com um array vazio se não existir
                $this->usuarios = [
                    [
                    'username' => 'admin',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT),
                    'perfil' => 'admin'
                    ],

                    [
                        'username' => 'user',
                        'password' => password_hash('usuario123', PASSWORD_DEFAULT),
                        'perfil' => 'usuario'
                    ],
                ];
                $this->salvarUsuarios();
            }
        }
        private function salvarUsuarios(): void {
            $dir = dirname(ARQUIVO_USUARIOS);
            // verifica se o diretório existe
            if (!is_dir($dir)) {
                // cria o diretório se não existir
                mkdir($dir, 0777, true);
            }
            // salva o array de usuários no arquivo JSON
            file_put_contents(ARQUIVO_USUARIOS, json_encode($this->usuarios, JSON_PRETTY_PRINT));
        }

        // Método para realizar login
        public function login(string $username, string $password): bool {
            // percorre o array de usuários
            foreach ($this->usuarios as $usuario) {
                // verifica se o usuário e a senha estão corretos
                if ($usuario['username'] === $username && password_verify($password, $usuario['password'])) {
                    // inicia a sessão e armazena os dados do usuário
                    $_SESSION['auth'] = [
                        'logado' => true,
                        'username' => $usuario['username'],
                        'perfil' => $usuario['perfil']
                    ];
                    return true; //login bem-sucedido
                }
            }
            return false; //login falhou
        }

        public function logout(): void {
            session_destroy();
        }

        // verifica se o usuário está logado
        public static function verificarLogin(): bool {
            return isset($_SESSION['auth']) && $_SESSION['auth']['perfil'] === true;
        }

        public static function isPerfil(string $perfil): bool {
            return isset($_SESSION['auth']) && $_SESSION['auth']['perfil'] === $perfil;
        }

        public static function isAdmin(): bool {
            return self::isPerfil('admin');
        }

        public static function getUsuario(): array {
            // retorna os dados da sessão ou nulo se não exixtir
            return $_SESSION['auth'] ?? null;
        }
    }
?>
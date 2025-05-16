<?php

require_once 'QueryBuilder.php';

class ApiController
{

    protected QueryBuilder $queryBuilder;

    public function __construct()
    {

        date_default_timezone_set("Europe/Lisbon");

        $this->queryBuilder = new QueryBuilder();
    }

    public function getUsers(): array
    {

        return $this->queryBuilder->table('Clientes')
            ->select(['id_cliente', 'nome_cliente', 'email_cliente'])
            ->order('id_cliente', 'DESC')
            ->get();
    }


    /*
    SELECT * FROM Produtos 
INNER JOIN Categorias ON Produtos.id_categoria = Categorias.id_categoria
INNER JOIN Dimensoes  ON Categorias.id_dimensao = Dimensoes.id_dimensao
INNER JOIN ProdutosVariantes ON Produtos.id_produto = ProdutosVariantes.id_produto
INNER JOIN Cores ON ProdutosVariantes.id_cor  = Cores.id_cor
WHERE Produtos.id_produto = 1

    */
    public function getFiltersProducts($categoria, $precoMinimo, $precoMaximo, $cor, $tamanho): array
    {
        return $this->queryBuilder->table('Produtos')
            ->select(['*'])
            ->join('Categorias', 'Produtos.id_categoria', '=', 'Categorias.id_categoria')
            ->join('Dimensoes', 'Categorias.id_dimensao', '=', 'Dimensoes.id_dimensao')
            ->join('ProdutosVariantes', 'Produtos.id_produto', '=', 'ProdutosVariantes.id_produto')
            ->join('Cores', 'ProdutosVariantes.id_cor', '=', 'Cores.id_cor')
            ->where('Categorias.titulo_categoria', 'IN', $categoria)
            ->where('Produtos.preco_produto', '>=', $precoMinimo)
            ->where('Produtos.preco_produto', '<=', $precoMaximo)
            ->where('Cores.nome_cor', '=', $cor)
            ->where('Dimensoes.tamanho', '=', $tamanho)
            ->order('id_produto', 'DESC')
            ->get();
    }

    public function getProducts(): array
    {

        return $this->queryBuilder->table('Produtos')
            ->select(['*'])
            ->order('id_produto', 'DESC')
            ->get();

    }

    public function getUserByID(int $userID): ?array
    {

        $result = $this->queryBuilder->table('Clientes')
            ->select(['id_cliente', 'nome_cliente', 'email_cliente'])
            ->where('id_cliente', '=', $userID)
            ->get();

        return $result[0] ?? null;
    }

    public function getUserByEmail(string $userEmail): ?array
    {

        $result = $this->queryBuilder->table('Clientes')
            ->select(['id_cliente', 'email_cliente', 'pass_cliente'])
            ->where('email_cliente', '=', $userEmail)
            ->get();

        return $result[0] ?? null;
    }

    public function getAdminByEmail(string $adminEmail): ?array
    {

        $result = $this->queryBuilder->table('Admins')
            ->select(['id_admin', 'email_admin', 'pass_admin'])
            ->where('email_admin', '=', $adminEmail)
            ->get();

        return $result[0] ?? null;
    }

    public function getEncomendas(): array
    {

        return $this->queryBuilder->table('Encomendas')
            ->select(['id_encomenda', 'id_carrinho', 'preco_total_encomenda'])
            ->get();
    }

    public function getCarrinhos(): array
    {

        return $this->queryBuilder->table('Carrinhos')
            ->select(['id_carrinho', 'id_cliente', 'ip_cliente'])
            ->get();
    }

    public function getEncomendasByUserID(int $userID): array
    {

        return $this->queryBuilder->table('Encomendas')
            ->select(['Clientes.nome_cliente, Carrinhos.id_carrinho, Encomendas.id_encomenda, Encomendas.preco_total_encomenda'])
            ->join('Carrinhos', 'Encomendas.id_carrinho', '=', 'Carrinhos.id_carrinho')
            ->join('Clientes', 'Carrinhos.id_cliente', '=', 'Clientes.id_cliente')
            ->where('Encomendas.status_encomenda', '=', 'pendente')
            ->where('Clientes.id_cliente', '=', $userID)
            ->get();
    }

    public function getProductsSoldByID(int $productID): ?array
    {

        return $this->queryBuilder->table('Encomendas')
            ->select(['Produtos.id_produto', 'COUNT(DISTINCT Encomendas.id_encomenda) AS total_encomendas'])
            ->join('Carrinhos', 'Encomendas.id_carrinho', '=', 'Carrinhos.id_carrinho')
            ->join('CarrinhoItens', 'Carrinhos.id_carrinho', '=', 'CarrinhoItens.id_carrinho')
            ->join('Produtos', 'CarrinhoItens.id_produto', '=', 'Produtos.id_produto')
            ->where('Produtos.id_produto', '=', $productID)
            ->groupBy('Produtos.id_produto')
            ->get();

    }

    public function insertUser(): array
    {

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!is_array($data)) {

            return ['success' => false, 'message' => 'Invalid JSON data received'];
        }

        $requiredFields = [
            'nome_cliente',
            'email_cliente',
            'pass_cliente'
        ];

        $missingFields = [];

        foreach ($requiredFields as $field) {

            if (empty($data[$field])) {

                $missingFields[] = $field;
            }
        }

        $dataCriacao = date("Y-m-d H:i:s");

        if (!empty($missingFields)) {

            return [
                'error' => 'Invalid data',
                'message' => 'Missing required fields: ' . implode(', ', $missingFields)
            ];
        }

        try {

            $this->queryBuilder->table('Clientes')
                ->insert([
                    'nome_cliente' => $data['nome_cliente'],
                    'email_cliente' => $data['email_cliente'],
                    'pass_cliente' => password_hash($data['pass_cliente'], PASSWORD_DEFAULT),
                    'contacto_cliente' => $data['contacto_cliente'] ?? null,
                    'morada_cliente' => $data['morada_cliente'] ?? null,
                    'nif_cliente' => $data['nif_cliente'] ?? null,
                    'ip_cliente' => $this->getClientIP(),
                    'imagem_cliente' => $data['imagem_cliente'] ?? null,
                    'id_gift' => $data['id_gift'] ?? null,
                    'id_favoritos' => $data['id_favoritos'] ?? null,
                    'id_boletim' => $data['id_boletim'] ?? null,
                    'id_google' => $data['id_google'] ?? null,
                    'id_facebook' => $data['id_facebook'] ?? null,
                    'data_criacao_cliente' => $dataCriacao,
                ]);

            return ['success' => 'User created'];
        } catch (PDOException $e) {

            error_log("Database error: " . $e->getMessage());

            return [
                'error' => 'Error creating the user',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }

    public function updateUser(): array
    {

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!is_array($data)) {

            return ['success' => false, 'message' => 'Invalid JSON data received'];
        }

        $key_first_element = array_key_first($data);

        $value_first_element = $data[$key_first_element];

        unset($data[$key_first_element]);

        try {

            $this->queryBuilder->table('Clientes')
                ->update($data)
                ->where($key_first_element, '=', $value_first_element)
                ->execute();

            return ['success' => 'User updated'];
        } catch (PDOException $e) {

            error_log("Database error: " . $e->getMessage());

            return [
                'error' => 'Error updating the user',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }

    public function deleteUserByID($userID): array
    {

        try {

            $this->queryBuilder->table('Clientes')
                ->delete()
                ->where('id_cliente', '=', $userID)
                ->execute();

            return ['success' => 'User deleted'];
        } catch (PDOException $e) {

            error_log("Database error: " . $e->getMessage());

            return [
                'error' => 'Error deleting the user',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }

    public function getClientIP(): string
    {
        $ipHeaders = [
            'HTTP_CLIENT_IP',
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($ipHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                return $_SERVER[$header];
            }
        }

        return '0.0.0.0';
    }

    public function insertFeedback(): array
    {

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!is_array($data)) {
            return ['success' => false, 'message' => 'Invalid JSON data received'];
        }

        $requiredFields = [
            'id_cliente',
            'id_categoria',
            'comentario',
            'classificacao',
            'data_review',
            'recommend'
        ];

        try {
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    return [
                        'error' => 'Invalid data',
                        'message' => "Missing required field: $field"
                    ];
                }
            }
            $this->queryBuilder->table('Reviews')
                ->insert([
                    'id_cliente' => $data['id_cliente'],
                    'id_categoria' => $data['id_categoria'],
                    'comentario' => $data['comentario'],
                    'classificacao' => $data['classificacao'],
                    'data_review' => $data['data_review'],
                    'recommend' => $data['recommend']
                ]);

            return ['success' => 'Feedback inserted'];
        } catch (PDOException $e) {

            error_log("Database error: " . $e->getMessage());

            return [
                'error' => 'Error inserting feedback',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }

    public function getFeedbacks(): array
    {

        return $this->queryBuilder->table('Reviews')
            ->select(['id_cliente', 'id_produto', 'feedback', 'data_feedback', 'recommend'])
            ->order('data_feedback', 'DESC')
            ->get();
    }
    public function getOrders(): array
    {
        return $this->queryBuilder->table('Encomendas')
            ->select([
                'Encomendas.id_encomenda',
                'Encomendas.preco_total_encomenda',
                'Encomendas.fatura',
                'Encomendas.status_encomenda',
                'Encomendas.data_criacao_encomenda',
                'Encomendas.data_rececao_encomenda',
                'Clientes.id_cliente',
                'Clientes.nome_cliente',
                'Clientes.email_cliente'
            ])
            ->join('Carrinhos', 'Encomendas.id_carrinho', '=', 'Carrinhos.id_carrinho')
            ->join('Clientes', 'Carrinhos.id_cliente', '=', 'Clientes.id_cliente')
            ->order('Encomendas.data_criacao_encomenda', 'DESC')
            ->get();
    }

    public function insertProduct(): array
    {

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!is_array($data)) {

            return ['success' => false, 'message' => 'Invalid JSON data received'];

        }

        $requiredFields = [
            'id_categoria',
            'titulo_produto',
            'descricao_produto',
            'preco_produto',
            'stock_produto',
            'keywords_produto'
        ];

        $missingFields = [];

        foreach ($requiredFields as $field) {

            if (empty($data[$field])) {

                $missingFields[] = $field;

            }
        }

        $dataCriacao = date("Y-m-d H:i:s");

        if (!empty($missingFields)) {

            return [
                'error' => 'Invalid data',
                'message' => 'Missing required fields: ' . implode(', ', $missingFields)
            ];
        }

        try {

            $this->queryBuilder->table('produtos')
                ->insert([
                    'id_categoria' => $data['id_categoria'] ?? null,
                    'titulo_produto' => $data['titulo_produto'] ?? null,
                    'modelo3d_produto' => $data['modelo3d_produto'],
                    'descricao_produto' => $data['descricao_produto'] ?? null,
                    'imagem_principal' => $data['imagem_principal'] ?? null,
                    'preco_produto' => $data['preco_produto'] ?? null,
                    'stock_produto' => $data['stock_produto'] ?? null,
                    'keywords_produto' => $data['keywords_produto'] ?? null,
                    'status_produto' => $data['status_produto'],
                    'data_criacao_produto' => $dataCriacao,
                ]);

            return ['success' => 'Product created'];

        } catch (PDOException $e) {

            error_log("Database error: " . $e->getMessage());

            return [
                'error' => 'Error creating the product',
                'message' => 'Database error: ' . $e->getMessage()
            ];

        }
    }

    public function updateProduct(): array
    {

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!is_array($data)) {

            return ['success' => false, 'message' => 'Invalid JSON data received'];

        }

        $key_first_element = array_key_first($data);

        $value_first_element = $data[$key_first_element];

        unset($data[$key_first_element]);

        try {

            $this->queryBuilder->table('Produtos')
                ->update($data)
                ->where($key_first_element, '=', $value_first_element)
                ->execute();

            return ['success' => 'Product updated'];

        } catch (PDOException $e) {

            error_log("Database error: " . $e->getMessage());

            return [
                'error' => 'Error updating the product',
                'message' => 'Database error: ' . $e->getMessage()
            ];

        }

    }

    public function deleteProductByID($productID): array
    {

        try {

            $this->queryBuilder->table('Produtos')
                ->delete()
                ->where('id_produto', '=', $productID)
                ->execute();

            return ['success' => 'Product deleted'];

        } catch (PDOException $e) {

            error_log("Database error: " . $e->getMessage());

            return [
                'error' => 'Error deleting the product',
                'message' => 'Database error: ' . $e->getMessage()
            ];

        }

    }


}

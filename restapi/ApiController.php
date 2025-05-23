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

    public function getServerTimeStamp(): string {

        return $this->queryBuilder->getServerTimeStamp();

    }

    public function getUsers(): array
    {

        return $this->queryBuilder->table('Clientes')
            ->select(['id_cliente', 'nome_cliente', 'email_cliente'])
            ->order('id_cliente', 'DESC')
            ->get();
    }

    public function getFiltersProducts($categoria, $precoMinimo, $precoMaximo, $cor, $tamanho): array
    {
        // Se vier '_', tratar como vazio
        $categoria = ($categoria === '_') ? [] : explode(',', $categoria);
        $cor = ($cor === '_') ? [] : explode(',', $cor);
        $tamanho = ($tamanho === '_') ? [] : explode(',', $tamanho);

        $qb = $this->queryBuilder->table('Produtos')
            ->select(['Produtos.*'])
            ->join('Categorias', 'Produtos.id_categoria', '=', 'Categorias.id_categoria')
            ->join('Dimensoes', 'Produtos.id_produto', '=', 'Dimensoes.id_produto')
            ->join('ProdutosVariantes', 'Produtos.id_produto', '=', 'ProdutosVariantes.id_produto')
            ->join('Cores', 'ProdutosVariantes.id_cor', '=', 'Cores.id_cor');

        if (!empty($categoria)) {
            $qb->where('Categorias.titulo_categoria', 'IN', $categoria);
        }

        if (!empty($precoMinimo)) {
            $qb->where('Produtos.preco_produto', '>=', $precoMinimo);
        }

        if (!empty($precoMaximo) && $precoMaximo < 999999) {
            $qb->where('Produtos.preco_produto', '<=', $precoMaximo);
        }

        if (!empty($cor)) {
            $qb->where('Cores.nome_cor', 'IN', $cor);
        }

        if (!empty($tamanho)) {
            $qb->where('Dimensoes.tamanho', 'IN', $tamanho);
        }

        $qb->order('Produtos.id_produto', 'DESC');
        return $qb->get();
    }

    public function getCategories(): array
    {

        return $this->queryBuilder->table('Categorias')
            ->select(['*'])
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
            ->select(['nome_cliente'])
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

    public function getEncomendasChart(): array {

        return $this->queryBuilder->table('Encomendas')
        ->select(["DATE_FORMAT(data_criacao_encomenda, '%m') AS Month", "status_encomenda", "COUNT(*) AS total"])
        ->groupBy("Month, status_encomenda")
        ->order("Month", "ASC")
        ->order("status_encomenda", "ASC")
        ->get();
    }

    public function getRevenuePerMonth(): array {

        return $this->queryBuilder->table('Encomendas')
        ->select(["DATE_FORMAT(data_criacao_encomenda, '%m') AS Month", "SUM(preco_total_encomenda) AS total"])
        ->groupBy("Month")
        ->order("Month", "ASC")
        ->get();

    }

    public function getTopProducts(): array {

        return $this->queryBuilder->table('Produtos p')
        ->select(["p.titulo_produto AS nome_produto", "COUNT(DISTINCT ei.id_encomenda) AS total_encomendas", "SUM(ei.quantidade * ei.preco) AS total_dinheiro"])
        ->join('EncomendaItens ei', 'p.id_produto', '=', 'ei.id_produto')
        ->groupBy("p.id_produto, p.titulo_produto")
        ->order("total_dinheiro", "DESC")
        ->limit(10)
        ->get();

    }

    public function numberOfOrdersFilteredByDay($day): array {

        return $this->queryBuilder->table('Encomendas')
        ->select(["COUNT(id_encomenda) AS NumberOfOrders"])
        ->where("DATE_FORMAT(data_criacao_encomenda, '%d')", "=", $day)
        ->get();

    }

    public function numberOfOrdersFilteredByMonth($month): array {

        return $this->queryBuilder->table('Encomendas')
        ->select(["COUNT(id_encomenda) AS NumberOfOrders"])
        ->where("DATE_FORMAT(data_criacao_encomenda, '%M')", "=", $month)
        ->get();

    }

    public function numberOfOrdersFilteredByYear($year): array {

        return $this->queryBuilder->table('Encomendas')
        ->select(["COUNT(id_encomenda) AS NumberOfOrders"])
        ->where("DATE_FORMAT(data_criacao_encomenda, '%Y')", "=", $year)
        ->get();

    }

    public function revenueFilteredByDay($day): array {

        return $this->queryBuilder->table('Encomendas')
        ->select(["SUM(preco_total_encomenda) AS Revenue"])
        ->where("DATE_FORMAT(data_criacao_encomenda, '%d')", "=", $day)
        ->get();

    }

    public function revenueFilteredByMonth($month): array {

        return $this->queryBuilder->table('Encomendas')
        ->select(["SUM(preco_total_encomenda) AS Revenue"])
        ->where("DATE_FORMAT(data_criacao_encomenda, '%M')", "=", $month)
        ->get();
        
    }

    public function revenueFilteredByYear($year): array {

        return $this->queryBuilder->table('Encomendas')
        ->select(["SUM(preco_total_encomenda) AS Revenue"])
        ->where("DATE_FORMAT(data_criacao_encomenda, '%Y')", "=", $year)
        ->get();
        
    }

    public function getNumberOfClientsByDay($day): array {

        return $this->queryBuilder->table('Clientes')
        ->select(["COUNT(id_cliente) AS NumberOfClients"])
        ->where("DATE_FORMAT(data_criacao_cliente, '%d')", "=", $day)
        ->get();
        
    }

    public function getNumberOfClientsByMonth($month): array {

        return $this->queryBuilder->table('Clientes')
        ->select(["COUNT(id_cliente) AS NumberOfClients"])
        ->where("DATE_FORMAT(data_criacao_cliente, '%M')", "=", $month)
        ->get();
        
    }

    public function getNumberOfClientsByYear($year): array {

        return $this->queryBuilder->table('Clientes')
        ->select(["COUNT(id_cliente) AS NumberOfClients"])
        ->where("DATE_FORMAT(data_criacao_cliente, '%Y')", "=", $year)
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

            $lastId = $this->queryBuilder->getLastInsertId();

            return [
                'success' => true,
                'message' => 'User created',
                'id_cliente' => $lastId
            ];

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
            ->select(['id_cliente', 'id_categoria', 'comentario', 'classificacao', 'data_review', 'recommend'])
            ->order('data_review', 'DESC')
            ->get();
    }

    public function getClassificationFeedback(): ?array {

        return $this->queryBuilder->table('Reviews')
            ->select(['classificacao', 'COUNT(*) AS Sum'])
            ->groupBy('classificacao')
            ->get();

    }

    public function getOrders(): array
    {
        try {
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
        } catch (PDOException $e) {
            error_log("Database error in getOrders: " . $e->getMessage());
            throw $e;
        } catch (Exception $e) {
            error_log("General error in getOrders: " . $e->getMessage());
            throw $e;
        }

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

            $this->queryBuilder->table('Produtos')
                ->insert([
                    'id_categoria' => $data['id_categoria'],
                    'titulo_produto' => $data['titulo_produto'],
                    'modelo3d_produto' => $data['modelo3d_produto'] ?? null,
                    'descricao_produto' => $data['descricao_produto'],
                    'imagem_principal' => $data['imagem_principal'],
                    'preco_produto' => $data['preco_produto'],
                    'stock_produto' => $data['stock_produto'] ?? 0,
                    'keywords_produto' => $data['keywords_produto'],
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
    public function getCarrinhoItens($userID): array
    {
        return $this->queryBuilder->table('CarrinhoItens')
            ->select([
                'CarrinhoItens.id_carrinho_item',
                'CarrinhoItens.quantidade',
                'CarrinhoItens.preco',
                'Produtos.titulo_produto',
                'Produtos.imagem_principal',
                'Produtos.preco_produto',
                'Carrinhos.id_carrinho'
            ])
            ->join('Carrinhos', 'CarrinhoItens.id_carrinho', '=', 'Carrinhos.id_carrinho')
            ->join('Produtos', 'CarrinhoItens.id_produto', '=', 'Produtos.id_produto')
            ->where('Carrinhos.id_cliente', '=', $userID)
            ->order('CarrinhoItens.id_carrinho_item', 'DESC')
            ->get();
    }

}

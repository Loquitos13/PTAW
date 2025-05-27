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

    public function getServerTimeStamp(): string
    {

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

    public function getProductByID(int $productID): array
    {
        $rows = $this->queryBuilder->table('Produtos')
            ->select([
                'Produtos.id_produto AS id_produto',
                'Produtos.id_categoria',
                'Produtos.data_criacao_produto',
                'Produtos.titulo_produto',
                'Produtos.modelo3d_produto',
                'Produtos.descricao_produto',
                'Produtos.imagem_principal',
                'Produtos.preco_produto',
                'Produtos.stock_produto',
                'Produtos.keywords_produto',
                'Produtos.status_produto',
                'Cores.id_cor',
                'Cores.hex_cor',
                'Cores.nome_cor',
                'Dimensoes.dimensao_tipo',
                'Dimensoes.tamanho',
                'ImagemProdutos.id_imagem_extra',
                'ImagemProdutos.imagem_extra',
                'ImagemProdutos.imagem_extra_2',
                'ImagemProdutos.imagem_extra_3'
            ])
            ->leftJoin('ProdutosVariantes', 'Produtos.id_produto', '=', 'ProdutosVariantes.id_produto')
            ->leftJoin('Cores', 'ProdutosVariantes.id_cor', '=', 'Cores.id_cor')
            ->leftJoin('Dimensoes', 'Produtos.id_produto', '=', 'Dimensoes.id_produto')
            ->leftJoin('ImagemProdutos', 'Produtos.id_produto', '=', 'ImagemProdutos.id_produto')
            ->where('Produtos.id_produto', '=', $productID)
            ->get();

        if (empty($rows)) {
            return [];
        }

        // Montar produto base
        $produto = [
            'id_produto' => $rows[0]['id_produto'],
            'id_categoria' => $rows[0]['id_categoria'],
            'data_criacao_produto' => $rows[0]['data_criacao_produto'],
            'titulo_produto' => $rows[0]['titulo_produto'],
            'modelo3d_produto' => $rows[0]['modelo3d_produto'],
            'descricao_produto' => $rows[0]['descricao_produto'],
            'imagem_principal' => $rows[0]['imagem_principal'],
            'preco_produto' => $rows[0]['preco_produto'],
            'stock_produto' => $rows[0]['stock_produto'],
            'keywords_produto' => $rows[0]['keywords_produto'],
            'status_produto' => $rows[0]['status_produto'],
            'cores' => [],
            'dimensoes' => [],
            'imagens_extras' => []
        ];

        // Agrupar cores, dimensões e imagens extras
        foreach ($rows as $row) {
            // Cores
            if ($row['id_cor'] && !in_array($row['id_cor'], array_column($produto['cores'], 'id_cor'))) {
                $produto['cores'][] = [
                    'id_cor' => $row['id_cor'],
                    'hex_cor' => $row['hex_cor'],
                    'nome_cor' => $row['nome_cor']
                ];
            }
            // Dimensões
            if ($row['tamanho'] && !in_array($row['tamanho'], array_column($produto['dimensoes'], 'tamanho'))) {
                $produto['dimensoes'][] = [
                    'dimensao_tipo' => $row['dimensao_tipo'],
                    'tamanho' => $row['tamanho']
                ];
            }
            // Imagens extras
            if ($row['id_imagem_extra'] && !in_array($row['id_imagem_extra'], array_column($produto['imagens_extras'], 'id_imagem_extra'))) {
                $produto['imagens_extras'][] = [
                    'id_imagem_extra' => $row['id_imagem_extra'],
                    'imagem_extra' => $row['imagem_extra'],
                    'imagem_extra_2' => $row['imagem_extra_2'],
                    'imagem_extra_3' => $row['imagem_extra_3']
                ];
            }
        }

        return $produto;
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

    public function getEncomendasChart(): array
    {

        return $this->queryBuilder->table('Encomendas')
            ->select(["DATE_FORMAT(data_criacao_encomenda, '%m') AS Month", "status_encomenda", "COUNT(*) AS total"])
            ->groupBy("Month, status_encomenda")
            ->order("Month", "ASC")
            ->order("status_encomenda", "ASC")
            ->get();
    }

    public function getRevenuePerMonth(): array
    {

        return $this->queryBuilder->table('Encomendas')
            ->select(["DATE_FORMAT(data_criacao_encomenda, '%m') AS Month", "SUM(preco_total_encomenda) AS total"])
            ->groupBy("Month")
            ->order("Month", "ASC")
            ->get();

    }

    public function getTopProducts(): array
    {

        return $this->queryBuilder->table('Produtos p')
            ->select(["p.titulo_produto AS nome_produto", "COUNT(DISTINCT ei.id_encomenda) AS total_encomendas", "SUM(ei.quantidade * ei.preco) AS total_dinheiro"])
            ->join('EncomendaItens ei', 'p.id_produto', '=', 'ei.id_produto')
            ->groupBy("p.id_produto, p.titulo_produto")
            ->order("total_dinheiro", "DESC")
            ->limit(10)
            ->get();

    }

    public function numberOfOrdersFilteredByDay($day): array
    {

        return $this->queryBuilder->table('Encomendas')
            ->select(["COUNT(id_encomenda) AS NumberOfOrders"])
            ->where("DATE_FORMAT(data_criacao_encomenda, '%d')", "=", $day)
            ->get();

    }

    public function numberOfOrdersFilteredByMonth($month): array
    {

        return $this->queryBuilder->table('Encomendas')
            ->select(["COUNT(id_encomenda) AS NumberOfOrders"])
            ->where("DATE_FORMAT(data_criacao_encomenda, '%M')", "=", $month)
            ->get();

    }

    public function numberOfOrdersFilteredByYear($year): array
    {

        return $this->queryBuilder->table('Encomendas')
            ->select(["COUNT(id_encomenda) AS NumberOfOrders"])
            ->where("DATE_FORMAT(data_criacao_encomenda, '%Y')", "=", $year)
            ->get();

    }

    public function revenueFilteredByDay($day): array
    {

        return $this->queryBuilder->table('Encomendas')
            ->select(["SUM(preco_total_encomenda) AS Revenue"])
            ->where("DATE_FORMAT(data_criacao_encomenda, '%d')", "=", $day)
            ->get();

    }

    public function revenueFilteredByMonth($month): array
    {

        return $this->queryBuilder->table('Encomendas')
            ->select(["SUM(preco_total_encomenda) AS Revenue"])
            ->where("DATE_FORMAT(data_criacao_encomenda, '%M')", "=", $month)
            ->get();

    }

    public function revenueFilteredByYear($year): array
    {

        return $this->queryBuilder->table('Encomendas')
            ->select(["SUM(preco_total_encomenda) AS Revenue"])
            ->where("DATE_FORMAT(data_criacao_encomenda, '%Y')", "=", $year)
            ->get();

    }

    public function getNumberOfClientsByDay($day): array
    {

        return $this->queryBuilder->table('Clientes')
            ->select(["COUNT(id_cliente) AS NumberOfClients"])
            ->where("DATE_FORMAT(data_criacao_cliente, '%d')", "=", $day)
            ->get();

    }

    public function getNumberOfClientsByMonth($month): array
    {

        return $this->queryBuilder->table('Clientes')
            ->select(["COUNT(id_cliente) AS NumberOfClients"])
            ->where("DATE_FORMAT(data_criacao_cliente, '%M')", "=", $month)
            ->get();

    }

    public function getNumberOfClientsByYear($year): array
    {

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

    public function getClassificationFeedback(): ?array
    {

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
                    'Encomendas.data_atualizacao_encomenda',
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

    //obter id da order

    public function getOrderID($id): array{
        return $this->queryBuilder->table('Encomendas')
            ->select(['*'])
            ->where('id_encomenda', '=', $id)
            ->get();
    }

    public function getOrderById(int $orderId): ?array
    {
        $result = $this->queryBuilder->table('Encomendas')
            ->select([
                'Encomendas.id_encomenda',
                'Encomendas.preco_total_encomenda',
                'Encomendas.fatura',
                'Encomendas.status_encomenda',
                'Encomendas.data_criacao_encomenda',
                'Encomendas.data_atualizacao_encomenda',
                'Encomendas.transportadora',
                'Encomendas.numero_seguimento',
                'Encomendas.notas_encomenda',
                'Clientes.id_cliente',
                'Clientes.nome_cliente',
                'Clientes.email_cliente',
                'Clientes.contacto_cliente',
                'Clientes.morada_cliente',
                'Clientes.nif_cliente',
                'Clientes.data_criacao_cliente'
            ])
            ->join('Carrinhos', 'Encomendas.id_carrinho', '=', 'Carrinhos.id_carrinho')
            ->join('CarrinhoItens', 'Carrinhos.id_carrinho', '=', 'CarrinhoItens.id_carrinho')
            ->join('Clientes', 'Carrinhos.id_cliente', '=', 'Clientes.id_cliente')
            ->where('Encomendas.id_encomenda', '=', $orderId)
            ->get();

        return $result[0] ?? null;
    }

    /**
     * Obter itens de uma encomenda específica
     */
    public function getOrderItems(int $orderId): array
    {
        try {
            error_log("Getting order items for order ID: $orderId");

            // Debug the SQL query
            $query = $this->queryBuilder->table('EncomendaItens')
                ->select([
                    'EncomendaItens.id_encomenda_item',
                    'EncomendaItens.quantidade',
                    'EncomendaItens.preco',
                    'EncomendaItens.id_cor',
                    'EncomendaItens.id_dimensao',
                    'EncomendaItens.personalizado',
                    'Produtos.id_produto',
                    'Produtos.titulo_produto',
                    'Produtos.descricao_produto',
                    'Produtos.preco_produto'
                ])
                ->join('Produtos', 'EncomendaItens.id_produto', '=', 'Produtos.id_produto')
                ->where('EncomendaItens.id_encomenda', '=', $orderId)
                ->order('EncomendaItens.id_encomenda_item', 'DESC');
            $result = $query->get();
            error_log("Found " . count($result) . " items for order ID: $orderId");
            return $result;
        } catch (Exception $e) {
            error_log("Error in getOrderItems: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            // Return empty array instead of throwing exception to prevent complete failure
            return [];
        }
    }

    /**
     * Obter informações de pagamento de uma encomenda
     */
    public function getOrderPaymentInfo(int $orderId): ?array
    {
        $result = $this->queryBuilder->table('Pagamento')
            ->select([
                'Pagamento.id_pagamento',
                'Pagamento.id_metodo_pagamento',
                'Pagamento.valor_pago',
                'Pagamento.data_pagamento',
            ])
            ->where('Pagamento.id_encomenda', '=', $orderId)
            ->get();

        return $result[0] ?? null;
    }


    public function getCompleteOrderInfo(int $orderId): array
    {
        try {
            error_log("Getting complete order info for order ID: $orderId");

            $orderInfo = $this->getOrderById($orderId);

            if ($orderInfo == null) {
                error_log("Order not found for ID: $orderId");
                return [
                    'success' => false,
                    'message' => 'Encomenda não encontrada'
                ];
            }

            $orderItems = $this->getOrderItems($orderId);
            $paymentInfo = $this->getOrderPaymentInfo($orderId);

            // Calcular subtotal dos itens
            $subtotal = 0;
            foreach ($orderItems as $item) {
                $subtotal += $item['quantidade'] * $item['preco'];
            }

            error_log("Successfully retrieved complete order info for ID: $orderId");
            return [
                'success' => true,
                'order' => $orderInfo,
                'items' => $orderItems,
                'payment' => $paymentInfo,
                'subtotal' => $subtotal,
                'shipping_cost' => $orderInfo['preco_total_encomenda'] - $subtotal
            ];

        } catch (PDOException $e) {
            error_log("Database error in getCompleteOrderInfo: " . $e->getMessage());
            error_log("SQL State: " . $e->getCode());
            error_log("Stack trace: " . $e->getTraceAsString());
            return [
                'success' => false,
                'message' => 'Erro ao obter informações da encomenda',
                'error' => $e->getMessage()
            ];
        } catch (Exception $e) {
            error_log("General error in getCompleteOrderInfo: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return [
                'success' => false,
                'message' => 'Erro ao obter informações da encomenda',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Atualizar status de uma encomenda
     */
    public function updateOrderStatus(int $orderId, string $status): array
    {
        try {
            $this->queryBuilder->table('Encomendas')
                ->update([
                    'status_encomenda' => $status,
                    'data_atualizacao_encomenda' => date('Y-m-d H:i:s')
                ])
                ->where('id_encomenda', '=', $orderId)
                ->execute();

            return [
                'success' => true,
                'message' => 'Status da encomenda atualizado'
            ];

        } catch (PDOException $e) {
            error_log("Database error in updateOrderStatus: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao atualizar status da encomenda',
                'error' => $e->getMessage()
            ];
        }
    }
    public function getDadosClientePorCarrinho($userID): array
    {
        return $this->queryBuilder->table('Carrinhos')
            ->select([
                'Clientes.email_cliente',
                'Clientes.nome_cliente',
                'Clientes.contacto_cliente',
                'Clientes.morada_cliente',
                'Clientes.cidade_cliente',
                'Clientes.state_cliente',
                'Clientes.cod_postal_cliente',
                'Clientes.nif_cliente'
            ])
            ->join('Clientes', 'Carrinhos.id_cliente', '=', 'Clientes.id_cliente')
            ->where('Carrinhos.id_cliente', '=', $userID)
            ->get();
    }
}

?>
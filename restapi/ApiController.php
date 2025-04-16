<?php

require_once 'QueryBuilder.php';

class ApiController {
    protected QueryBuilder $queryBuilder;

    public function __construct() {

        $this->queryBuilder = new QueryBuilder();

    }

    public function getUsers(): array {

        return $this->queryBuilder->table('clientes')
            ->select(['id_cliente', 'nome_cliente', 'email_cliente'])
            ->order('id_cliente', 'DESC')
            ->get();

    }

    public function getUserByID(int $userID): ?array {

        $result = $this->queryBuilder->table('clientes')
            ->select(['id_cliente', 'nome_cliente', 'email_cliente'])
            ->where('id_cliente', '=', $userID)
            ->get();

        return $result[0] ?? null;

    }  

    public function getEncomendas(): array {

        return $this->queryBuilder->table('encomendas')
            ->select(['id_encomenda', 'id_carrinho', 'preco_total_encomenda'])
            ->get();

    }

    public function getCarrinhos(): array {

        return $this->queryBuilder->table('carrinhos')
            ->select(['id_carrinho', 'id_cliente', 'ip_cliente'])
            ->get();
            
    }

    public function getEncomendasByUserID(int $userID): array {

        return $this->queryBuilder->table('encomendas')
            ->select(['clientes.nome_cliente, carrinhos.id_carrinho, encomendas.id_encomenda, encomendas.preco_total_encomenda'])
            ->join('carrinhos', 'encomendas.id_carrinho', '=', 'carrinhos.id_carrinho')
            ->join('clientes', 'carrinhos.id_cliente', '=', 'clientes.id_cliente')
            ->where('encomendas.status_encomenda', '=', 'pendente')
            ->where('clientes.id_cliente', '=', $userID)
            ->get();

    }

    public function insertUser(): array {

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
    
        if (!is_array($data)) {

            return ['success' => false, 'message' => 'Invalid JSON data received'];

        }
    
        $requiredFields = [
            'nome_cliente', 'email_cliente', 'pass_cliente',
            'contacto_cliente', 'morada_cliente', 'nif_cliente',
            'ip_cliente', 'data_criacao_cliente'
        ];

        $missingFields = [];
    
        foreach ($requiredFields as $field) {

            if (empty($data[$field])) {

                $missingFields[] = $field;

            }
        }
    
        if (!empty($missingFields)) {

            return [
                'success' => 'Invalid data',
                'message' => 'Missing required fields: ' . implode(', ', $missingFields)
            ];
        }
    
        try {

            $this->queryBuilder->table('clientes')
                ->insert([
                    'nome_cliente' => $data['nome_cliente'],
                    'email_cliente' => $data['email_cliente'],
                    'pass_cliente' => $data['pass_cliente'],
                    'contacto_cliente' => $data['contacto_cliente'],
                    'morada_cliente' => $data['morada_cliente'],
                    'nif_cliente' => $data['nif_cliente'],
                    'ip_cliente' => $data['ip_cliente'],
                    'imagem_cliente' => $data['imagem_cliente'] ?? null,
                    'id_gift' => $data['id_gift'] ?? null,
                    'id_favoritos' => $data['id_favoritos'] ?? null,
                    'id_boletim' => $data['id_boletim'] ?? null,
                    'id_google' => $data['id_google'] ?? null,
                    'id_facebook' => $data['id_facebook'] ?? null,
                    'data_criacao_cliente' => $data['data_criacao_cliente']
                ]);
    
            return ['success' => 'User created'];
    
        } catch (PDOException $e) {

            error_log("Database error: " . $e->getMessage());

            return [
                'success' => 'Error creating the user',
                'message' => 'Database error: ' . $e->getMessage()
            ];

        }
    }

    public function updateUser(): array {

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
    
        if (!is_array($data)) {

            return ['success' => false, 'message' => 'Invalid JSON data received'];

        }

        $key_first_element = array_key_first($data);

        $value_first_element = $data[$key_first_element];

        unset($data[$key_first_element]);

        try {

            $this->queryBuilder->table('clientes')
                ->update($data)
                ->where($key_first_element, '=', $value_first_element)
                ->execute();

            return ['success' => 'User updated'];
    
        } catch (PDOException $e) {

            error_log("Database error: " . $e->getMessage());

            return [
                'success' => 'Error updating the user',
                'message' => 'Database error: ' . $e->getMessage()
            ];

        }

    }

    public function deleteUserByID($userID): array {

        try {

            $this->queryBuilder->table('clientes')
                ->delete()
                ->where('id_cliente', '=', $userID)
                ->execute();

            return ['success' => 'User deleted'];
    
        } catch (PDOException $e) {

            error_log("Database error: " . $e->getMessage());

            return [
                'success' => 'Error deleting the user',
                'message' => 'Database error: ' . $e->getMessage()
            ];

        }

    }
}
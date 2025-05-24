<?php
session_start();
require_once '../../restapi/Database.php';
require_once '../../restapi/QueryBuilder.php';
require_once '../../restapi/ApiController.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar se o ID da encomenda foi fornecido
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: orders.php?error=invalid_id');
    exit;
}

$orderId = (int)$_GET['id'];

// Add debugging information
error_log("Attempting to fetch order ID: $orderId");

// Conectar à base de dados e obter informações da encomenda
try {
    Database::connect();
    $controller = new ApiController();
    
    // Add debug output before calling API
    error_log("About to call getCompleteOrderInfo for order ID: $orderId");
    
    $orderData = $controller->getCompleteOrderInfo($orderId);
    
    // Add debug output after API call
    error_log("API response: " . print_r($orderData, true));
    
    if (!isset($orderData['success']) || !$orderData['success']) {
        // Log the exact error
        error_log("Order not found or API error. Order ID: $orderId, Response: " . print_r($orderData, true));
        header('Location: orders.php?error=order_not_found&details=' . urlencode(json_encode($orderData)));
        exit;
    }
    
    $order = $orderData['order'];
    $items = $orderData['items'];
    $payment = $orderData['payment'] ?? null;
    $subtotal = $orderData['subtotal'] ?? 0;
    $shippingCost = $orderData['shipping_cost'] ?? 0;
    
} catch (Exception $e) {
    // Log the full error with stack trace
    error_log("Erro ao carregar informações da encomenda: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    header('Location: orders.php?error=database_error&message=' . urlencode($e->getMessage()));
    exit;
}

// Função para formatar status com cores melhoradas
function getStatusBadge($status) {
    $statusMap = [
        'pendente' => ['class' => 'bg-warning text-dark', 'text' => 'Pendente'],
        'processando' => ['class' => 'bg-info text-white', 'text' => 'A ser processada'],
        'enviado' => ['class' => 'bg-primary', 'text' => 'Enviado'],
        'entregue' => ['class' => 'bg-success', 'text' => 'Entregue'],
        'cancelado' => ['class' => 'bg-danger', 'text' => 'Cancelado'],
        'reembolsado' => ['class' => 'bg-secondary', 'text' => 'Reembolsado']
    ];
    
    $statusLower = strtolower(trim($status));
    $statusInfo = $statusMap[$statusLower] ?? ['class' => 'bg-secondary', 'text' => ucfirst($status)];
    
    return '<span class="badge ' . $statusInfo['class'] . '">' . htmlspecialchars($statusInfo['text']) . '</span>';
}

// Função para formatar data com melhor tratamento de timezone
function formatDate($date) {
    if (empty($date)) return 'N/A';
    
    try {
        $dateTime = new DateTime($date);
        return $dateTime->format('d/m/Y H:i');
    } catch (Exception $e) {
        return 'Data inválida';
    }
}

// Função para formatar moeda
function formatCurrency($amount) {
    return '€' . number_format((float)$amount, 2, ',', '.');
}

// Função para obter ícone do método de pagamento
function getPaymentIcon($method) {
    $icons = [
        'cartao' => 'bi-credit-card',
        'paypal' => 'bi-paypal',
        'mbway' => 'bi-phone',
        'transferencia' => 'bi-bank',
        'multibanco' => 'bi-credit-card-2-front'
    ];
    
    $methodLower = strtolower(trim($method));
    return $icons[$methodLower] ?? 'bi-cash';
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Encomenda #PG-<?php echo htmlspecialchars($order['id_encomenda']); ?> | Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      margin: 0;
      padding: 0;
      overflow: auto;
      align-items: center;
      background-color: #f8f9fa;
    }
    
    .order-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 2rem 0;
      margin-bottom: 2rem;
    }
    
    .card {
      border: none;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      transition: box-shadow 0.15s ease-in-out;
    }
    
    .card:hover {
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      padding-top: 60px;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
      backdrop-filter: blur(5px);
    }

    .modal-content {
      background-color: #fff;
      margin: auto;
      padding: 20px;
      border-radius: 12px;
      width: 90%;
      max-width: 800px;
      position: relative;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .close-button {
      color: #aaa;
      position: absolute;
      top: 15px;
      right: 25px;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
      transition: color 0.3s;
    }

    .close-button:hover {
      color: #000;
    }

    .fixed-header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background-color: white;
      z-index: 999;
      display: flex;
      align-items: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .product-image {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 8px;
    }
    
    .status-timeline {
      display: flex;
      justify-content: space-between;
      margin: 1rem 0;
    }
    
    .timeline-step {
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      flex: 1;
    }
    
    .timeline-step:not(:last-child)::after {
      content: '';
      position: absolute;
      top: 15px;
      left: 50%;
      width: 100%;
      height: 2px;
      background-color: #e9ecef;
      z-index: -1;
    }
    
    .timeline-step.active::after {
      background-color: #28a745;
    }
    
    .timeline-icon {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background-color: #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 0.5rem;
    }
    
    .timeline-step.active .timeline-icon {
      background-color: #28a745;
      color: white;
    }

    @media (max-width: 1200px) {
      body {
        width: 100%;
        padding-top: 70px;
      }
    }
    
    @media (max-width: 768px) {
      .order-header {
        padding: 1rem 0;
      }
      
      .status-timeline {
        flex-wrap: wrap;
      }
      
      .timeline-step {
        flex-basis: 50%;
        margin-bottom: 1rem;
      }
    }
    
    .loading-spinner {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 3px solid #f3f3f3;
      border-top: 3px solid #3498db;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>

<body>
  <div class="d-flex">
    <!-- Menu lateral -->
    <?php include '../includes/header-desktop-admin.php'; ?>
    <!-- Menu mobile -->
    <?php include '../includes/header-mobile-admin.php'; ?>

    <!-- Conteudo principal -->
    <div class="flex-grow-1" id="main-content">
      <!-- Header da encomenda -->
      <div class="order-header">
        <div class="container">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h2 class="mb-1">Encomenda #PG-<?php echo htmlspecialchars($order['id_encomenda']); ?></h2>
              <p class="mb-0 opacity-75">
                Criada em <?php echo formatDate($order['data_criacao_encomenda']); ?>
              </p>
            </div>
            <div class="text-end">
              <?php echo getStatusBadge($order['status_encomenda']); ?>
              <div class="mt-2">
                <a href="orders.php" class="btn btn-light">
                  <i class="bi bi-arrow-left"></i> Voltar às Encomendas
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="container mb-5">
        <!-- Timeline de Status -->
        <div class="card mb-4">
          <div class="card-body">
            <h6 class="card-title">Estado da Encomenda</h6>
            <div class="status-timeline">
              <div class="timeline-step <?php echo in_array(strtolower($order['status_encomenda']), ['pendente', 'processando', 'enviado', 'entregue']) ? 'active' : ''; ?>">
                <div class="timeline-icon"><i class="bi bi-clock"></i></div>
                <small>Pendente</small>
              </div>
              <div class="timeline-step <?php echo in_array(strtolower($order['status_encomenda']), ['processando', 'enviado', 'entregue']) ? 'active' : ''; ?>">
                <div class="timeline-icon"><i class="bi bi-gear"></i></div>
                <small>Processando</small>
              </div>
              <div class="timeline-step <?php echo in_array(strtolower($order['status_encomenda']), ['enviado', 'entregue']) ? 'active' : ''; ?>">
                <div class="timeline-icon"><i class="bi bi-truck"></i></div>
                <small>Enviado</small>
              </div>
              <div class="timeline-step <?php echo strtolower($order['status_encomenda']) === 'entregue' ? 'active' : ''; ?>">
                <div class="timeline-icon"><i class="bi bi-check2"></i></div>
                <small>Entregue</small>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row">
          <!-- Informação da encomenda -->
          <div class="col-lg-8">
            <div class="card mb-4">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Informações da Encomenda</h6>
                <div class="btn-group" role="group">
                  <button type="button" class="btn btn-sm btn-outline-primary" onclick="updateOrderStatus()">
                    <i class="bi bi-pencil"></i> Atualizar Status
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printOrder()">
                    <i class="bi bi-printer"></i> Imprimir
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <p><strong>Status:</strong> <?php echo getStatusBadge($order['status_encomenda']); ?></p>
                    <p><strong>Data de Criação:</strong> <?php echo formatDate($order['data_criacao_encomenda']); ?></p>
                    <?php if ($order['data_atualizacao_encomenda']): ?>
                    <p><strong>Última Atualização:</strong> <?php echo formatDate($order['data_atualizacao_encomenda']); ?></p>
                    <?php endif; ?>
                  </div>
                  <div class="col-md-6">
                    <p><strong>Origem da Compra:</strong> <span class="badge bg-info">Loja Online</span></p>
                    <?php if (!empty($order['transportadora'])): ?>
                    <p><strong>Transportadora:</strong> <?php echo htmlspecialchars($order['transportadora']); ?></p>
                    <?php endif; ?>
                    <p><strong>Total:</strong> <span class="h5 text-success"><?php echo formatCurrency($order['preco_total_encomenda']); ?></span></p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Detalhes do produto -->
            <div class="card mb-4">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Itens da Encomenda</h6>
                <button class="btn btn-sm btn-primary" id="button-process">
                  <i class="bi bi-gear"></i> Processar Itens
                </button>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Produto</th>
                        <th>Tamanho</th>
                        <th>Cor</th>
                        <th>Qtd</th>
                        <th class="text-end">Preço</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($items as $item): ?>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <?php if (!empty($item['imagem_principal'])): ?>
                            <img src="<?php echo htmlspecialchars($item['imagem_principal']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['titulo_produto']); ?>" 
                                 class="product-image me-3">
                            <?php else: ?>
                            <div class="product-image me-3 bg-light d-flex align-items-center justify-content-center">
                              <i class="bi bi-image text-muted"></i>
                            </div>
                            <?php endif; ?>
                            <div>
                              <strong><?php echo htmlspecialchars($item['titulo_produto']); ?></strong>
                              <?php if (!empty($item['personalizacao'])): ?>
                              <br><small class="text-primary"><i class="bi bi-star"></i> <?php echo htmlspecialchars($item['personalizacao']); ?></small>
                              <?php endif; ?>
                            </div>
                          </div>
                        </td>
                        <td>
                          <?php if (!empty($item['tamanho'])): ?>
                            <span class="badge bg-secondary"><?php echo htmlspecialchars($item['tamanho']); ?></span>
                          <?php else: ?>
                            <span class="text-muted">N/A</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php if (!empty($item['cor'])): ?>
                            <span class="badge bg-secondary"><?php echo htmlspecialchars($item['cor']); ?></span>
                          <?php else: ?>
                            <span class="text-muted">N/A</span>
                          <?php endif; ?>
                        </td>
                        <td><span class="badge bg-light text-dark"><?php echo (int)$item['quantidade']; ?></span></td>
                        <td class="text-end"><?php echo formatCurrency($item['preco']); ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Pagamento -->
            <div class="card">
              <div class="card-header">
                <h6 class="mb-0">Resumo Financeiro</h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <table class="table table-sm">
                      <tr>
                        <td><strong>Subtotal:</strong></td>
                        <td class="text-end"><?php echo formatCurrency($subtotal); ?></td>
                      </tr>
                      <tr>
                        <td><strong>Envio:</strong></td>
                        <td class="text-end">
                          <?php echo $shippingCost > 0 ? formatCurrency($shippingCost) : '<span class="text-success">Gratuito</span>'; ?>
                        </td>
                      </tr>
                      <tr class="table-active">
                        <td><strong>Total:</strong></td>
                        <td class="text-end"><strong><?php echo formatCurrency($order['preco_total_encomenda']); ?></strong></td>
                      </tr>
                    </table>
                  </div>
                  
                  <?php if ($payment): ?>
                  <div class="col-md-6">
                    <h6>Informações de Pagamento</h6>
                    <p>
                      <i class="<?php echo getPaymentIcon($payment['metodo_pagamento']); ?> me-2"></i>
                      <strong><?php echo htmlspecialchars($payment['metodo_pagamento']); ?></strong>
                    </p>
                    <p><strong>Status:</strong> 
                      <span class="badge <?php echo strtolower($payment['status_pagamento']) === 'pago' ? 'bg-success' : 'bg-warning'; ?>">
                        <?php echo ucfirst($payment['status_pagamento']); ?>
                      </span>
                    </p>
                    <?php if (!empty($payment['referencia_pagamento'])): ?>
                    <p><strong>Referência:</strong> <code><?php echo htmlspecialchars($payment['referencia_pagamento']); ?></code></p>
                    <?php endif; ?>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

          <!-- Informação do cliente -->
          <div class="col-lg-4">
            <div class="card mb-4">
              <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person"></i> Cliente</h6>
              </div>
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="bi bi-person text-white"></i>
                  </div>
                  <div>
                    <h6 class="mb-1"><?php echo htmlspecialchars($order['nome_cliente']); ?></h6>
                    <small class="text-muted">Cliente desde <?php echo date('M Y', strtotime($order['data_criacao_cliente'])); ?></small>
                  </div>
                </div>
              </div>
            </div>

            <div class="card mb-4">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Contacto e Morada</h6>
                <button class="btn btn-sm btn-outline-secondary" onclick="editCustomerInfo()">
                  <i class="bi bi-pencil"></i>
                </button>
              </div>
              <div class="card-body">
                <p><strong>Email:</strong><br>
                  <a href="mailto:<?php echo htmlspecialchars($order['email_cliente']); ?>" class="text-decoration-none">
                    <?php echo htmlspecialchars($order['email_cliente']); ?>
                  </a>
                </p>
                
                <?php if (!empty($order['contacto_cliente'])): ?>
                <p><strong>Telefone:</strong><br>
                  <a href="tel:<?php echo htmlspecialchars($order['contacto_cliente']); ?>" class="text-decoration-none">
                    <?php echo htmlspecialchars($order['contacto_cliente']); ?>
                  </a>
                </p>
                <?php endif; ?>
                
                <?php if (!empty($order['morada_cliente'])): ?>
                <p><strong>Morada de Envio:</strong><br>
                  <address class="mb-0">
                    <?php echo nl2br(htmlspecialchars($order['morada_cliente'])); ?>
                  </address>
                </p>
                <?php endif; ?>
                
                <?php if (!empty($order['nif_cliente'])): ?>
                <p><strong>NIF:</strong> <?php echo htmlspecialchars($order['nif_cliente']); ?></p>
                <?php endif; ?>
                
                <p><small class="text-muted"><i class="bi bi-info-circle"></i> Faturação igual à morada de envio</small></p>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-chat-text"></i> Notas</h6>
              </div>
              <div class="card-body">
                <?php if (!empty($order['notas_encomenda'])): ?>
                <div class="alert alert-info">
                  <i class="bi bi-info-circle me-2"></i>
                  <?php echo nl2br(htmlspecialchars($order['notas_encomenda'])); ?>
                </div>
                <?php else: ?>
                <p class="text-muted mb-0"><i class="bi bi-chat-text me-2"></i>Sem notas do cliente</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div id="processModal" class="modal">
    <div class="modal-content">
      <span class="close-button">&times;</span>
      <div id="modal-body-content">
        <div class="text-center">
          <div class="loading-spinner"></div>
          <p class="mt-2">A carregar...</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const modal = document.getElementById('processModal');
      const closeBtn = document.querySelector('.close-button');
      const modalContent = document.getElementById('modal-body-content');

      document.getElementById('button-process').addEventListener('click', function () {
        modal.style.display = 'block';
        
        // Reset modal content
        modalContent.innerHTML = `
          <div class="text-center">
            <div class="loading-spinner"></div>
            <p class="mt-2">A carregar...</p>
          </div>
        `;

        fetch('ProcessItems.php?order_id=<?php echo $orderId; ?>')
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.text();
          })
          .then(data => {
            modalContent.innerHTML = data;
          })
          .catch(error => {
            modalContent.innerHTML = `
              <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Erro ao carregar conteúdo. Tente novamente.
              </div>
            `;
            console.error('Error:', error);
          });
      });

      closeBtn.addEventListener('click', function () {
        modal.style.display = 'none';
      });

      window.addEventListener('click', function (event) {
        if (event.target === modal) {
          modal.style.display = 'none';
        }
      });

      // Close modal with Escape key
      document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.style.display === 'block') {
          modal.style.display = 'none';
        }
      });
    });

    // Additional functions
    function updateOrderStatus() {
      // Implementation for updating order status
      alert('Funcionalidade de atualização de status em desenvolvimento');
    }

    function printOrder() {
      window.print();
    }

    function editCustomerInfo() {
      // Implementation for editing customer information
      alert('Funcionalidade de edição de informações do cliente em desenvolvimento');
    }

    // Auto-refresh order status every 30 seconds
    setInterval(function() {
      // You can implement auto-refresh logic here if needed
    }, 30000);
  </script>
</body>

</html>
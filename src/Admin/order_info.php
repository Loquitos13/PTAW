<?php

session_start();

$orderId = isset($_GET['id']) ? $_GET['id'] : null;



// Função para formatar moeda
function formatCurrency($amount)
{
  return '€' . number_format((float) $amount, 2, ',', '.');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/Orders.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <script src="js/OrderInfo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
</head>
<style>
  body {
    margin: 0;
    padding: 0;
    overflow: auto;
    align-items: center;
    background-color: #f8f9fa;
  }

  body {
    margin: 0;
    padding: 0;
    overflow: auto;
    align-items: center;
  }

  #menu-mobile {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 80%;
    height: 100%;
    background-color: white;
    transform: translateX(-10%);
    transition: transform 2000ms ease;
    overflow: hidden;
    z-index: 999;
    flex-direction: column-reverse;
    margin-top: 0;
    justify-content: flex-end;
    gap: 20px;
    align-items: center;
    padding-top: 100px;
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

  #menu-mobile.open {
    display: flex;
    transform: translateX(0);
  }

  #menu-mobile a {
    color: #4F46E5;
    text-decoration: none;
    font-size: 1.5rem;
    margin: 15px 0;
  }

  #menu-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1000;
    font-size: 2rem;
    background: none;
    border: none;
    color: black;
    cursor: pointer;
  }

  #logo-header-mobile {
    display: none;
    background: none;
    border: none;
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
    background-color: rgba(0, 0, 0, 0.5);
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
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
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

    .fixed-header {
      display: flex;
      align-items: center;
    }

    #div-menu.container.header-desktop {
      display: none !important;
    }

    #a-logo-header-mobile {
      display: flex;
      width: max-content;
      justify-content: center;
      padding-top: 20px;
      padding-bottom: 20px;
      width: 100%;

    }

    #logo-header-mobile {
      display: block;
      width: 100px;
      height: auto;
      margin: 0 auto;
    }

    #menu-toggle {
      display: block;
      align-self: center;
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
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
</style>

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
              <h2 class="mb-1">Encomenda #PG-<?php echo $_GET['id']; ?></h2>
              <p class="mb-0 opacity-75" data-creation-date>

              </p>
            </div>
            <div class="text-end" data-order-status>
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
              <div class="timeline-step ">
                <div class="timeline-icon"><i class="bi bi-clock"></i></div>
                <small>Pendente</small>
              </div>
              <div
                class="timeline-step <?php echo in_array(strtolower($order['status_encomenda']), ['processando', 'enviado', 'entregue']) ? 'active' : ''; ?>">
                <div class="timeline-icon"><i class="bi bi-gear"></i></div>
                <small>Processando</small>
              </div>
              <div
                class="timeline-step <?php echo in_array(strtolower($order['status_encomenda']), ['enviado', 'entregue']) ? 'active' : ''; ?>">
                <div class="timeline-icon"><i class="bi bi-truck"></i></div>
                <small>Enviado</small>
              </div>
              <div
                class="timeline-step <?php echo strtolower($order['status_encomenda']) === 'entregue' ? 'active' : ''; ?>">
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
                    <p data-order-status></p>
                    <p data-creation-date-card></p>
                    <p data-update-date></p>
                  </div>
                  <div class="col-md-6">
                    <p><strong>Origem da Compra:</strong> <span class="badge bg-info">Loja Online</span></p>
                    <?php if (!empty($order['transportadora'])): ?>
                      <p><strong>Transportadora:</strong> <?php echo htmlspecialchars($order['transportadora']); ?></p>
                    <?php endif; ?>
                    <p><strong>Total:</strong> <span
                        class="h5 text-success" data-order-total></span>
                    </p>
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
                    <tbody class="table-tbody">

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
                        <td class="text-end" data-subtotal></td>
                      </tr>
                      <tr>
                        <td><strong>Envio:</strong></td>
                        <td class="text-end">
                          <?php echo $shippingCost > 0 ? formatCurrency($shippingCost) : '<span class="text-success">Gratuito</span>'; ?>
                        </td>
                      </tr>
                      <tr class="table-active">
                        <td><strong>Total:</strong></td>
                        <td class="text-end">
                          <strong data-financial-total></strong>
                        </td>
                      </tr>
                    </table>
                  </div>

                  <div class="col-md-6">
                    <h6>Informações de Pagamento</h6>
                    <div data-payment-method>
                      <i class="me-2"></i>
                      <p></p>
                    </div>
                    <p><strong>Status:</strong>
                      <span
                        class="badge <?php echo strtolower($payment['status_pagamento']) === 'pago' ? 'bg-success' : 'bg-warning'; ?>">
                        <?php echo ucfirst($payment['status_pagamento']); ?>
                      </span>
                    </p>
                    <p data-payment-reference>
                    </p>
                  </div>
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
                  <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                    style="width: 50px; height: 50px;">
                    <i class="bi bi-person text-white"></i>
                  </div>
                  <div>
                    <h6 class="mb-1" data-customer-name></h6>
                    <small class="text-muted" data-member-since>
                    </small>
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
                <p data-customer-email><strong>Email:</strong><br>
                  <a href="mailto: data-customer-email"
                    class="text-decoration-none">

                  </a>
                </p>

                <?php if (!empty($order['contacto_cliente'])): ?>
                  <p><strong>Telefone:</strong><br>
                    <a href="tel:<?php echo htmlspecialchars($order['contacto_cliente']); ?>"
                      class="text-decoration-none">
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

                <p><small class="text-muted"><i class="bi bi-info-circle"></i> Faturação igual à morada de envio</small>
                </p>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-chat-text"></i> Notas</h6>
              </div>
              <div class="card-body">
                <div class="alert alert-info">
                  <i class="bi bi-info-circle me-2"></i>
                  <p class="text-muted mb-0" data-order-notes></p>
                </div>
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
    document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('processModal');
      const closeBtn = document.querySelector('.close-button');
      const modalContent = document.getElementById('modal-body-content');

      document.getElementById('button-process').addEventListener('click', function() {
        modal.style.display = 'block';

        // Reset modal content
        modalContent.innerHTML = `
        <div class="text-center">
          <div class="loading-spinner"></div>
          <p class="mt-2">A carregar...</p>
        </div>
      `;

        // Carregar formulário de ProcessItems.php - CORREÇÃO AQUI
        fetch('ProcessItems.php?id=<?php echo $_GET['id']; ?>')
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
      closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
      });

      window.addEventListener('click', function(event) {
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
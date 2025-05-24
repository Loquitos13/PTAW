<?php
session_start();
$adminId = $_SESSION['admin_id'] ?? null;
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


  /* Ajustes para telas menores */
  @media (max-width: 1200px) {
    body {
      width: 100%;
      padding-top: 70px;
    }

    .header-desktop {
      display: none;
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
      top: 0;
    }

    #menu-mobile {
      position: fixed;
      height: 100%;
      overflow: hidden;
    }

    #menu-mobile li {
      list-style: none;
    }

    #menu-mobile .social {
      display: flex;
      height: 100%;
      width: 100%;
      align-items: center;
      align-self: flex-end;
      justify-content: center;
    }

    .container1 {
      display: none;
    }

    .left,
    .right {
      width: 100%;
      justify-content: center;
    }

    .left a {
      width: auto;
    }

    #containerHeroe {
      flex-direction: column;
      height: auto;
      padding: 20px 20px;
      width: 100%;
    }

    .esqHeroe,
    .dirHeroe {
      width: 100%;
      text-align: center;
    }

    .dirHeroe img {
      position: static;
      height: auto;
      width: 80%;
    }

    #prodDestaques {
      flex-direction: column;
      align-items: center;
      justify-content: center;

    }

    .containerDestaques {
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 100%;
      gap: 20px;
    }

    .feedback-carousel-container {
      max-width: 90%;
      max-height: 400px;
    }

    #featureSection {
      padding: 5vh;
    }

    #containerFeatures {
      flex-direction: column;
    }

    .featureBox p {
      width: 90%;
    }

    #cta h3 {
      text-align: center;
      font-size: 100%;
    }

    #cta p {
      text-align: center;
      font-size: 90%;
    }

    footer {
      text-align: center;
    }

    #containerFooter {
      flex-direction: column;
      align-items: center;
      gap: 20px;
    }
  }
</style>


<body style="background-color: #E5E7EB;">
  <div class="d-flex">
    <!-- Menu lateral -->
    <?php include '../includes/header-desktop-admin.php'; ?>
    <!-- Menu mobile -->
    <?php include '../includes/header-mobile-admin.php'; ?>
    


    <!-- Conteudo principal -->
    <div class="flex-grow-1 p-4" id="main-content">

      <?php /*echo ($_SESSION['admin_email']); */ ?>

      <!--
      <a href="/PTAW/src/logout.php" class="nav-link">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5985E1">
          <path
            d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" />
        </svg>
      </a> -->

      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap py-3">
              <!-- Mensagem de boas vindas -->
              <div class="mb-2 mb-lg-0">
                <h1 class="mb-1">Orders</h1>
                <p class="mb-0">Welcome back, Admin!</p>
              </div>
              <!-- Admin info -->
              <div class="d-flex align-items-center">
                <img src="../../imagens/admin.png" alt="" id="img-admin"
                  style="width:40px; height:40px; object-fit:cover; border-radius:50%;">
                <h6 class="mb-0 ms-3">John Doe</h6>
              </div>
            </div>
          </div>
        </div>
      </div>

       <h2>Orders</h2>
    <div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>ID Encomenda</th>
        <th>Cliente</th>
        <th>Total</th>
        <th>Status</th>
        <th>Data</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody id="orders-table-body">
      <!-- Os dados serão carregados via JavaScript -->
    </tbody>
  </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
});

function loadOrders() {
    fetch('../../admin/orderEngine.php')
        .then(response => response.json())
        .then(orders => {
            const tbody = document.getElementById('orders-table-body');
            tbody.innerHTML = '';
            
            orders.forEach(order => {
                const row = document.createElement('tr');
                row.setAttribute('data-order-id', order.id_encomenda);
                row.style.cursor = 'pointer';
                row.classList.add('order-row');
                
                row.innerHTML = `
                    <td><strong>#PG-${order.id_encomenda}</strong></td>
                    <td>${order.nome_cliente}</td>
                    <td>€${parseFloat(order.preco_total_encomenda).toFixed(2)}</td>
                    <td><span class="badge ${getStatusClass(order.status_encomenda)}">${order.status_encomenda}</span></td>
                    <td>${formatDate(order.data_criacao_encomenda)}</td>
                    <td>
                        <button class="btn btn-sm btn-primary view-order-btn" data-order-id="${order.id_encomenda}">
                            <i class="bi bi-eye"></i> Ver
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
            
            // Adicionar event listeners para as linhas clicáveis
            addOrderClickListeners();
        })
        .catch(error => {
            console.error('Erro ao carregar encomendas:', error);
        });
}


function addOrderClickListeners() {
    // Listener para linhas da tabela
    const orderRows = document.querySelectorAll('.order-row');
    orderRows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Evitar clique duplo quando se clica no botão
            if (e.target.tagName !== 'BUTTON' && !e.target.closest('button')) {
                const orderId = this.getAttribute('data-order-id');
                if (orderId) {
                    console.log('Clique na linha - Order ID:', orderId);
                    console.log('Caminho atual:', window.location.pathname);
                    
                    const targetUrl = `order_info.php?id=${orderId}`;
                    console.log('Redirecionando para:', targetUrl);
                    window.location.href = targetUrl;
                }
            }
        });

    });    
    // Listener para botões específicos
    const viewButtons = document.querySelectorAll('.view-order-btn');
    viewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Evitar propagação do evento
            const orderId = this.getAttribute('data-order-id');
            if (orderId) {
                console.log('Clique no botão - Order ID:', orderId);
                
                const targetUrl = `order_info.php?id=${orderId}`;
                console.log('Redirecionando para:', targetUrl);
                
                // Redirecionar diretamente
                window.location.href = targetUrl;
            }
        });
    });
}

function getStatusClass(status) {
    switch(status.toLowerCase()) {
        case 'pendente':
            return 'bg-warning text-dark';
        case 'processando':
            return 'bg-info';
        case 'enviado':
            return 'bg-primary';
        case 'entregue':
            return 'bg-success';
        case 'cancelado':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-PT') + ' ' + date.toLocaleTimeString('pt-PT', {hour: '2-digit', minute: '2-digit'});
}

// Função de debug para verificar o caminho atual
function debugPagePath() {
    console.log('Caminho atual:', window.location.pathname);
    console.log('URL completa:', window.location.href);
}

// Função alternativa com caminho absoluto (se necessário)
function redirectToOrderInfo(orderId) {
    // Opção 1: Caminho relativo (se ambos os ficheiros estão na mesma pasta)
    const relativePath = `order_info.php?id=${orderId}`;
    
    // Opção 2: Caminho baseado na estrutura que mencionou
    // const absolutePath = `/src/Admin/order_info.php?id=${orderId}`;
    
    // Opção 3: Caminho dinâmico baseado na localização atual
    const currentPath = window.location.pathname;
    const basePath = currentPath.substring(0, currentPath.lastIndexOf('/'));
    const dynamicPath = `${basePath}/order_info.php?id=${orderId}`;
    
    console.log('Redirecionando para:', relativePath);
    window.location.href = relativePath;
}
</script>
<script src="js/Orders.js"></script>
</body>
</html>

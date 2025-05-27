document.addEventListener("DOMContentLoaded", function () {
  const ordersLink = document.querySelector("#link-orders");
  if (ordersLink) {
    ordersLink.innerHTML = `<li>
            <a href="#" class="nav-link active" aria-current="page" id="">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi me-2 bi-cart-fill" viewBox="0 0 16 16">
                    <path
                        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                </svg>
                Orders
            </a>
        </li>`;
  }

  const processButton = document.getElementById("button-process");
  if (processButton) {
    processButton.addEventListener("click", function () {
      window.open(
        "ProcessItems.php",
        "ProcessPopup",
        "width=600,height=600,resizable=yes,scrollbars=yes"
      );
    });
  }

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
                  getOrderId(orderId);
                    console.log('Clique na linha - Order ID:', orderId);
                    console.log('Caminho atual:', window.location.pathname);
                    
                    const targetUrl = `order_info.php?id=${orderId}`;
                    console.log('Redirecionando para:', targetUrl);
                    window.location.href = targetUrl;
                }
            }
        });

        function getOrderId(orderId){
          fetch('../../admin/orderInfoEngine.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: orderId })
          })
          return orderId;
        }

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

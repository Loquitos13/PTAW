document.addEventListener("DOMContentLoaded", function() {
    // Fix the navigation link
    const dashboardLink = document.querySelector("#link-dashboard");
    if (dashboardLink) {
        dashboardLink.innerHTML = `<li>
            <a href="#" class="nav-link active" aria-current="page" id="">
                <svg xmlns="http://www.w3.org/2000/svg"
                    style="stroke:currentColor; stroke-width:1; color: #4F46E5;" width="16" height="16"
                    fill="currentColor" class="bi pe-none me-2 bi-graph-up" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07" />
                </svg>
                Dashboard
            </a>
        </li>`;
    }

    // Get order ID from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('id');
    
    if (orderId) {
        getOrderInfo(orderId);
        getOrderItems(orderId);
    }

});

function getOrderInfo(orderId) {
    // Make POST request to orderInfoEngine.php with the order ID
    fetch('../../admin/orderInfoEngine.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: orderId
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            displayOrderInfo(data.message);
        } else {
            throw new Error(data.message || 'Failed to fetch order data');
        }
    })
    .catch(error => {
        console.error('Error fetching order info:', error);
        showErrorMessage('Erro ao carregar informações da encomenda: ' + error.message);
    });
}

function getOrderItems(orderId) {
    fetch('../../admin/orderItemsEngine.php', {
        method: 'POST',  // Changed from GET to POST
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: orderId
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            updateOrderItems(data.message.items || []);
        } else {
            throw new Error(data.message || 'Failed to fetch order items');
        }
    })
    .catch(error => {
        console.error('Error fetching order items:', error);
        showErrorMessage('Erro ao carregar itens da encomenda: ' + error.message);
    });
}

function displayOrderInfo(orderData) {
    try {
        // Update order date in header
        const orderDate = document.querySelector("#order-date");
        if (orderDate && orderData.data_criacao_encomenda) {
            orderDate.textContent = formatDate(orderData.data_criacao_encomenda);
        }

        // Update order status timeline
        updateStatusTimeline(orderData.status_encomenda);

        // Update order information section
        updateOrderInfoSection(orderData);

        // Update customer information
        updateCustomerInfo(orderData);

        // Update payment information
        updatePaymentInfo(orderData);

        // Update order items
        updateOrderItems(orderData.items || []);

        // Update financial summary
        updateFinancialSummary(orderData);

        // Update notes
        updateOrderNotes(orderData.notas_encomenda);

    } catch (error) {
        console.error('Error displaying order info:', error);
        showErrorMessage('Erro ao exibir informações da encomenda');
    }
}

function updateStatusTimeline(status) {
    const statusSteps = document.querySelectorAll('.timeline-step');
    const statusLower = status ? status.toLowerCase() : '';
    
    statusSteps.forEach((step, index) => {
        const stepText = step.querySelector('small').textContent.toLowerCase();
        
        if (stepText === 'pendente') {
            step.classList.add('active');
        } else if (stepText === 'processando' && ['processando', 'enviado', 'entregue'].includes(statusLower)) {
            step.classList.add('active');
        } else if (stepText === 'enviado' && ['enviado', 'entregue'].includes(statusLower)) {
            step.classList.add('active');
        } else if (stepText === 'entregue' && statusLower === 'entregue') {
            step.classList.add('active');
        }
    });
}

function updateOrderInfoSection(orderData) {
    // Update status badge
    const statusElements = document.querySelectorAll('[data-order-status]');
    statusElements.forEach(el => {
        el.innerHTML = 'Estado da Encomenda: ' + getStatusBadge(orderData.status_encomenda);
    });

    // Update creation date
    const creationDateEl = document.querySelector('[data-creation-date]');
    
    if (creationDateEl && orderData.data_criacao_encomenda ) {
        creationDateEl.textContent = 'Encomenda Criada a ' + formatDate(orderData.data_criacao_encomenda);

    }
    const creationDateCard = document.querySelector('[data-creation-date-card]');
    if (creationDateCard && orderData.data_criacao_encomenda) {
        creationDateCard.textContent = 'Encomenda criada a ' +  formatDate(orderData.data_criacao_encomenda);
    }

    // Update last update date
    const updateDateEl = document.querySelector('[data-update-date]');
    if (updateDateEl && orderData.data_atualizacao_encomenda) {
        updateDateEl.textContent = 'Última atualização: ' +  formatDate(orderData.data_atualizacao_encomenda);
    }

    // Update carrier
    const carrierEl = document.querySelector('[data-carrier]');
    if (carrierEl && orderData.transportadora) {
        carrierEl.textContent = orderData.transportadora;
    }

    // Update total
    const totalEl = document.querySelector('[data-order-total]');
    if (totalEl && orderData.preco_total_encomenda) {
        totalEl.textContent = formatCurrency(orderData.preco_total_encomenda);
    }
}

function updateCustomerInfo(orderData) {
    // Update customer name
    const customerNameEl = document.querySelector('[data-customer-name]');
    if (customerNameEl && orderData.nome_cliente) {
        customerNameEl.textContent = orderData.nome_cliente;
    }

    // Update customer email
    const emailEl = document.querySelector('[data-customer-email]');
    if (emailEl && orderData.email_cliente) {
        emailEl.textContent = orderData.email_cliente;
        emailEl.href = `mailto:${orderData.email_cliente}`;
    }

    // Update customer phone
    const phoneEl = document.querySelector('[data-customer-phone]');
    if (phoneEl && orderData.contacto_cliente) {
        phoneEl.textContent = orderData.contacto_cliente;
        phoneEl.href = `tel:${orderData.contacto_cliente}`;
    }

    // Update customer address
    const addressEl = document.querySelector('[data-customer-address]');
    if (addressEl && orderData.morada_cliente) {
        addressEl.innerHTML = orderData.morada_cliente.replace(/\n/g, '<br>');
    }

    // Update customer NIF
    const nifEl = document.querySelector('[data-customer-nif]');
    if (nifEl && orderData.nif_cliente) {
        nifEl.textContent = orderData.nif_cliente;
    }

    // Update member since date
    const memberSinceEl = document.querySelector('[data-member-since]');
    if (memberSinceEl && orderData.data_criacao_cliente) {
        const date = new Date(orderData.data_criacao_cliente);
        memberSinceEl.textContent = 'Cliente Desde: ' + date.toLocaleDateString('pt-PT', { 
            month: 'short', 
            year: 'numeric' 
        });
    }
}

function updatePaymentInfo(orderData) {
    if (orderData.payment) {
        const paymentMethodEl = document.querySelector('[data-payment-method]');
        if (paymentMethodEl) {
            paymentMethodEl.innerHTML = `
                <i class="${getPaymentIcon(orderData.payment.id_metodo_pagamento)} me-2"></i>
                <strong>${orderData.payment.id_metodo_pagamento}</strong>
            `;
        }

        const paymentStatusEl = document.querySelector('[data-payment-status]');
        if (paymentStatusEl) {
            const isPaid = orderData.payment.status_pagamento.toLowerCase() === 'pago';
            paymentStatusEl.innerHTML = `
                <span class="badge ${isPaid ? 'bg-success' : 'bg-warning'}">
                    ${orderData.payment.status_pagamento}
                </span>
            `;
        }

        const paymentRefEl = document.querySelector('[data-payment-reference]');
        if (paymentRefEl && orderData.payment.id_pagamento) {
            paymentRefEl.innerHTML = `<code>${orderData.payment.id_pagamento}</code>`;
        }
    }
}

function updateOrderItems(items) {
    const itemsTableBody = document.querySelector('.table-tbody');
    if (!itemsTableBody) {
        console.error('Table body element not found for order items');
    };

    itemsTableBody.innerHTML = '';
    console.log('Order Items:', items);
    items.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    ${item.imagem_principal ? 
                        `<img src="${item.imagem_principal}" alt="${item.titulo_produto}" class="product-image me-3">` :
                        `<div class="product-image me-3 bg-light d-flex align-items-center justify-content-center">
                            <i class="bi bi-image text-muted"></i>
                        </div>`
                    }
                    <div>
                        <strong>${item.titulo_produto}</strong>
                        ${item.personalizacao ? 
                            `<br><small class="text-primary"><i class="bi bi-star"></i> ${item.personalizacao}</small>` : 
                            ''
                        }
                    </div>
                </div>
            </td>
            <td>
                ${item.tamanho ? 
                    `<span class="badge bg-secondary">${item.tamanho}</span>` : 
                    `<span class="text-muted">N/A</span>`
                }
            </td>
            <td>
                ${item.cor ? 
                    `<span class="badge bg-secondary">${item.cor}</span>` : 
                    `<span class="text-muted">N/A</span>`
                }
            </td>
            <td><span class="badge bg-light text-dark">${item.quantidade}</span></td>
            <td class="text-end">${formatCurrency(item.preco)}</td>
        `;
        itemsTableBody.appendChild(row);
    });
}

function updateFinancialSummary(orderData) {
    // Update subtotal
    const subtotalEl = document.querySelector('[data-subtotal]');
    if (subtotalEl && orderData.subtotal) {
        subtotalEl.textContent = formatCurrency(orderData.subtotal);
    }

    // Update shipping cost
    const shippingEl = document.querySelector('[data-shipping]');
    if (shippingEl) {
        const shippingCost = orderData.shipping_cost || 0;
        shippingEl.innerHTML = shippingCost > 0 ? 
            formatCurrency(shippingCost) : 
            '<span class="text-success">Gratuito</span>';
    }

    // Update total
    const totalEl = document.querySelector('[data-financial-total]');
    if (totalEl && orderData.preco_total_encomenda) {
        totalEl.innerHTML = `<strong>${formatCurrency(orderData.preco_total_encomenda)}</strong>`;
    }
}

function updateOrderNotes(notes) {
    const notesContainer = document.querySelector('[data-order-notes]');
    if (!notesContainer) return;

    if (notes && notes.trim()) {
        notesContainer.innerHTML = `
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                ${notes.replace(/\n/g, '<br>')}
            </div>
        `;
    } else {
        notesContainer.innerHTML = `
            <p class="text-muted mb-0">
                <i class="bi bi-chat-text me-2"></i>Sem notas do cliente
            </p>
        `;
    }
}

// Utility functions
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-PT', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatCurrency(amount) {
    if (!amount && amount !== 0) return '';
    return new Intl.NumberFormat('pt-PT', {
        style: 'currency',
        currency: 'EUR'
    }).format(amount);
}

function getStatusBadge(status) {
    if (!status) return '';
    
    const statusClasses = {
        'pendente': 'bg-warning',
        'processando': 'bg-info',
        'enviado': 'bg-primary',
        'entregue': 'bg-success',
        'cancelado': 'bg-danger'
    };
    
    const badgeClass = statusClasses[status.toLowerCase()] || 'bg-secondary';
    return `<span class="badge ${badgeClass}">${status}</span>`;
}

function getPaymentIcon(method) {
    const icons = {
        'cartão de crédito': 'bi-credit-card',
        'cartão de débito': 'bi-credit-card-2-front',
        'paypal': 'bi-paypal',
        'mbway': 'bi-phone',
        'transferência bancária': 'bi-bank',
        'multibanco': 'bi-credit-card-fill'
    };
    
    return icons[method?.toLowerCase()] || 'bi-credit-card';
}

function showErrorMessage(message) {
    // Create or update error message display
    let errorContainer = document.querySelector('#error-message');
    if (!errorContainer) {
        errorContainer = document.createElement('div');
        errorContainer.id = 'error-message';
        errorContainer.className = 'alert alert-danger alert-dismissible fade show';
        errorContainer.style.position = 'fixed';
        errorContainer.style.top = '20px';
        errorContainer.style.right = '20px';
        errorContainer.style.zIndex = '9999';
        document.body.appendChild(errorContainer);
    }
    
    errorContainer.innerHTML = `
        <i class="bi bi-exclamation-triangle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (errorContainer && errorContainer.parentNode) {
            errorContainer.remove();
        }
    }, 5000);
}
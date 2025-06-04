document.addEventListener("DOMContentLoaded", async function () {

    const userIdInput = document.getElementById("userId");

    const cartBackBtn = document.getElementById('cart-back');
    if (cartBackBtn) {
        cartBackBtn.addEventListener('click', function () {
            window.location.href = 'produtos.php';
        });
    }

    const userId = userIdInput.value;
    try {
        const cartResp = await fetch('../client/checkout.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId: userId })
        });
        const cartData = await cartResp.json();
        if (cartData.status === 'success') {
            renderCarrinhoItens(cartData.data);
        } else {
            console.error(cartData.message);
        }

        const clientResp = await fetch('../client/CheckoutClienteDados.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId: userId })
        });
        const clientData = await clientResp.json();
        if (clientData.status === 'success') {
            PreencherDadosCliente(clientData.data);
        } else {
            console.error(clientData.message);
        }
    } catch (err) {
        console.error("Erro ao carregar dados:", err);
    }

    const steps = [
        { // Step 0: Delivery
            container: document.querySelector('.container-delivery'),
            insignia: document.querySelectorAll('.Insignia')[0],
            barraProgresso: document.querySelectorAll('.BarraProgresso')[0],
            barraNomes: document.querySelectorAll('.BarraNomes')[0],
            buttons: cartBackBtn ? cartBackBtn.parentElement : null,
            nextButton: document.getElementById('payment-button')
        },
        { // Step 1: Payment
            container: document.querySelector('.container-payment'),
            insignia: document.querySelectorAll('.Insignia')[1],
            barraProgresso: document.querySelectorAll('.BarraProgresso')[1],
            barraNomes: document.querySelectorAll('.BarraNomes')[1],
            buttons: document.getElementById('delivery-back')?.parentElement,
            nextButton: document.getElementById('review-button')
        },
        { // Step 2: Confirm
            container: document.querySelector('.container-shipping'),
            insignia: document.querySelectorAll('.Insignia')[2],
            barraProgresso: document.querySelectorAll('.BarraProgresso')[2],
            barraNomes: document.querySelectorAll('.BarraNomes')[2],
            buttons: document.getElementById('payment-back')?.parentElement,
            nextButton: document.getElementById('confirm-button')
        }
    ];

    let currentStep = 0;

    function hideAllSteps() {
        document.querySelectorAll('.container-delivery, .container-payment, .container-shipping, .Insignia, .BarraProgresso, .BarraNomes').forEach(el => {
            el.classList.remove('active');
        });
        steps.forEach(step => step.buttons.style.display = 'none');
    }

    function showStep(stepIndex) {
        const step = steps[stepIndex];

        step.container.classList.add('active');
        step.insignia.classList.add('active');
        step.barraProgresso.classList.add('active');
        step.barraNomes.classList.add('active');
		
        step.buttons.style.display = 'flex';
    }

    steps.forEach((step, index) => {
        if (step.nextButton) {
            step.nextButton.addEventListener('click', function (e) {
                e.preventDefault();
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    hideAllSteps();
                    showStep(currentStep);
                }
            });
        }
    });

    steps.forEach((step, index) => {
        if (step.buttons) {
            const backButton = step.buttons.querySelector('.btn-secondary');
            if (backButton) {
                backButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (currentStep > 0) {
                        currentStep--;
                        hideAllSteps();
                        showStep(currentStep);
                    }
                });
            }
        }
    });

    hideAllSteps();
    showStep(currentStep);
});

function renderCarrinhoItens(itens) {
    const container = document.querySelector('.container-items');
    container.innerHTML = ''; // limpa conteúdo anterior

    let html = '';
    itens.forEach(item => {
        html += `
            <div class='row w-100 mb-3'>
                <div class='col-6 d-flex flex-column justify-content-center'>
                    <h6 class='fw-bold'>${item.titulo_produto}</h6>
                    <p class='mb-1'>Quantidade: ${item.quantidade}</p>
                </div>
                <div class='col-6 d-flex align-items-center justify-content-end'>
                    <p class='fw-bold confirm-price'>${(item.preco)} €</p>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function selectCountryCode(pais_cliente) {

    const countryNameToCode = {
        "Portugal": "+351",
        "Spain": "+34",
        "France": "+33",
        "Italy": "+39",
        "Germany": "+49",
        "United Kingdom": "+44",
        "Netherlands": "+31",
        "Belgium": "+32",
        "Austria": "+43",
        "Denmark": "+45"
    };

    const select = document.getElementById("country-code");
    if (!select) return;
    const code = countryNameToCode[pais_cliente] || "+351";
    select.value = code;
}

function PreencherDadosCliente(cliente) {
    if (!cliente) return;
    const nomePartes = (cliente.nome_cliente).trim().split(" ");
    const primeiroNome = nomePartes[0];
    const apelido = nomePartes.slice(1).join(" ");

    document.getElementById("firstName").value = primeiroNome;
    document.getElementById("lastName").value = apelido;
    document.getElementById("email").value = cliente.email_cliente;
    selectCountryCode(cliente.pais_cliente);
    document.getElementById("phoneNumber").value = cliente.contacto_cliente || '';
    document.getElementById("address").value = cliente.morada_cliente || '';
    document.getElementById("city").value = cliente.cidade_cliente || '';
    document.getElementById("postalCode").value = cliente.cod_postal_cliente || '';
    document.getElementById("taxId").value = cliente.nif_cliente || '';
}


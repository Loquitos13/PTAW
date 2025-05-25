let infoMessage = document.getElementById('infoMessage');

document.addEventListener("DOMContentLoaded", async function () {
  const userId = 1;

  try {
    const response = await fetch('../client/checkout_cliente.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ userId: userId })
    });

    const data = await response.json();

    if (data.status === 'success') {
      renderDadosCliente(data.data);
    } else {
      console.error(data.message);
    }
  } catch (err) {
    console.error("Erro ao carregar dados do cliente:", err);
  }
});

function renderDadosCliente(cliente) {
  const container = document.querySelector('.container-cliente');
  container.innerHTML = ''; // limpa conteúdo anterior

  const html =function renderDadosCliente(cliente) {
  const container = document.querySelector('.container-cliente');
  container.innerHTML = ''; // limpa conteúdo anterior

  const html = `
    <div class="row w-100 align-items-start">
      <div class="col-6 d-flex flex-column justify-content-center">
        <p class="mb-1">Delivery Method:</p>
        <h6 class="fw-bold">Standard-Delivery (3-5 days)</h6>
      </div>
      <div class="col-6 d-flex flex-column justify-content-end">
        <p class="mb-1">Delivery Address:</p>
        <h6 class="fw-bold">${cliente.nome_cliente}</h6>
        <h6 class="fw-bold">${cliente.morada_cliente}</h6>
        <h6 class="fw-bold">${cliente.cidade_cliente}, ${cliente.cod_postal_cliente}</h6>
      </div>
    </div>
  `;
  container.innerHTML = html;
}
;
  container.innerHTML = html;
}

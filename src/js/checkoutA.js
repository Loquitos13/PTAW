let infoMessage = document.getElementById('infoMessage');

document.addEventListener("DOMContentLoaded", async function () {
  const userId = 1; 

  try {
    const response = await fetch('../client/CheckoutClienteDados.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json'

       },
      body: JSON.stringify({ userId: userId })
    });

    const data = await response.json();

    if (data.status === 'success') {
      renderCarrinhoItens(data.data);

    } else {
      console.error(data.message);
    }
  } catch (err) {
    console.error("Erro ao carregar itens do carrinho:", err);
  }
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
  container.innerHTML += html;

}

document.addEventListener("DOMContentLoaded", async function() {
  console.log("Carrinho.js carregado");

  const cartIdInput = document.getElementById("cartId");

    if (cartIdInput) {

        if (cartIdInput.value) {

        const cartItens = await getShoppingCartItens(cartIdInput.value);

        const container = document.querySelector('#cartProducts');

        container.innerHTML = '';

        const cartItensArray = Array.isArray(cartItens) ? cartItens : cartItens.message || [];

        let html = '';
        cartItensArray.forEach(element => {
            console.log(element);

            element.Size = element.Size.replaceAll('%20', ' ');

            html += `
                <div class="row align-items-start">
                    <div class="col-4">
                        <img src="${element.Image}" class="img-small" alt="produtos">
                    </div>
                    <div class="col-8 d-flex justify-content-between align-items-start cart-item">
                        <div>
                            <p class="product-info mb-1">${element.Name}</p>
                            <span class="product-info">Size: ${element.Size}</span>
                            <span class="product-info"> | </span>
                            <span class="product-info">Color: ${element.Color}</span>
                            <div class="d-flex align-items-center mt-2">
                                <button type="button" class="btn btn-outline-secondary me-2">-</button>
                                <span class="me-2">${element.Quantity}</span>
                                <button type="button" class="btn btn-outline-secondary me-3">+</button>
                                <span class="price">${Number(element.Price).toFixed(2)}€</span>
                            </div>
                        </div>
                        <div>
                            <button type="button" value="${element.ID}" class="btn btn-link p-0 delete-cart-btn">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
            `;
        });

        container.innerHTML = html;


        }

    }
  
  // garantir que o bootstrap esta carregado
  if (typeof bootstrap === 'undefined') {
      console.error("Bootstrap nao esta carregado! Certifique-se de incluir bootstrap.bundle.min.js antes deste script.");
      return;
  }
  
  // usando delegacao de eventos para o botao "continuar a comprar"
  document.body.addEventListener("click", async function(event) {
      if (event.target && event.target.id === "continuar-compra") {
          console.log("Botao continuar compra clicado");
          const carrinhoElement = document.getElementById("carrinho");
          
          if (carrinhoElement) {
              try {
                  const offcanvasBS = bootstrap.Offcanvas.getInstance(carrinhoElement);
                  if (offcanvasBS) {
                      offcanvasBS.hide();
                      console.log("Fechando offcanvas via Bootstrap API");
                  } else {
                      console.log("Criando nova instancia de offcanvas");
                      // se nao existe instancia, cria uma e depois fecha
                      const newOffcanvas = new bootstrap.Offcanvas(carrinhoElement);
                      newOffcanvas.hide();
                  }
              } catch (error) {
                  console.error("Erro ao manipular offcanvas:", error);
                  // fallback simples como ultimo recurso
                  carrinhoElement.classList.remove("show");
                  document.body.classList.remove("offcanvas-open");
                  document.querySelector(".offcanvas-backdrop")?.remove();
              }
          }
      }
      
      // usando delegacao para os botoes de exclusao tambem
      if (event.target && (event.target.classList.contains("delete-cart-btn") || (event.target.parentElement && event.target.parentElement.classList.contains("delete-cart-btn")))) {
          
          console.log("Botao de exclusao clicado");
          
          // garantir que temos o botao mesmo se o clique foi no icone dentro dele
          const deleteBtn = event.target.classList.contains("delete-cart-btn") ? event.target : event.target.parentElement;

          const itemId = deleteBtn.value;
          console.log("ID do item a excluir:", itemId);

          const deleteCartItem = await deleteCartItens(itemId);

          if (deleteCartItem.status === "success") {

            const row = deleteBtn.closest(".row");
            if (row) {
                // remover o hr apos o item
                const nextElement = row.nextElementSibling;
                if (nextElement && nextElement.tagName.toLowerCase() === "hr") {
                    nextElement.remove();
                }
                
                // remover o proprio item
                row.remove();
                
                // atualizar o contador no titulo
                updateCartCount();
                
                // verificar se o carrinho esta vazio
                checkEmptyCart();
            }

          }
      }
  });
  
  // funcao para atualizar o contador de itens
  function updateCartCount() {
      const itemRows = document.querySelectorAll("#carrinho .offcanvas-body .row");
      const titleElement = document.getElementById("offcanvasRightLabel");
      
      if (titleElement) {
          titleElement.textContent = `Shopping Cart (${itemRows.length})`;
          console.log(`Carrinho atualizado: ${itemRows.length} itens`);
      }
  }
  
  // funcao para verificar se o carrinho esta vazio
  function checkEmptyCart() {
      const itemRows = document.querySelectorAll("#carrinho .offcanvas-body .row");
      const containerElement = document.querySelector("#carrinho .offcanvas-body .container");
      
      if (itemRows.length === 0 && containerElement) {
          containerElement.innerHTML = `
              <div class="text-center py-4">
                  <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                  <p class="my-3">O seu carrinho esta vazio</p>
              </div>
          `;
          console.log("Carrinho vazio - mensagem exibida");
      }
  }
  
  // verificar inicialmente se temos botoes para configurar
  console.log("Botao continuar compra:", document.getElementById("continuar-compra") ? "encontrado" : "nao encontrado");
  console.log("Botoes de exclusao:", document.querySelectorAll(".delete-cart-btn").length);
});


async function getShoppingCartItens(id_carrinho) {

  try {
    const response = await fetch('/~ptaw-2025-gr4/client/getCarrinhoItens.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({id_carrinho: id_carrinho})
    });

    const data = await response.json();
    
    return data;
  
  } catch (error) {
    
    return null;
  
  }
}

async function deleteCartItens(id_carrinho_item) {

  try {
    const response = await fetch('/~ptaw-2025-gr4/client/deleteCarrinhoItem.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({id_carrinho_item: id_carrinho_item})
    });

    const data = await response.json();
    
    return data;
  
  } catch (error) {
    
    return null;
  
  }
}
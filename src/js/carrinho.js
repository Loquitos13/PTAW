document.addEventListener("DOMContentLoaded", function() {
  console.log("Carrinho.js carregado");
  
  // Garantir que o Bootstrap está carregado
  if (typeof bootstrap === 'undefined') {
      console.error("Bootstrap não está carregado! Certifique-se de incluir bootstrap.bundle.min.js antes deste script.");
      return;
  }
  
  // Usando delegação de eventos para o botão "Continuar a Comprar"
  document.body.addEventListener("click", function(event) {
      if (event.target && event.target.id === "continuar-compra") {
          console.log("Botão continuar compra clicado");
          const carrinhoElement = document.getElementById("carrinho");
          
          if (carrinhoElement) {
              try {
                  const offcanvasBS = bootstrap.Offcanvas.getInstance(carrinhoElement);
                  if (offcanvasBS) {
                      offcanvasBS.hide();
                      console.log("Fechando offcanvas via Bootstrap API");
                  } else {
                      console.log("Criando nova instância de offcanvas");
                      // Se não existe instância, cria uma e depois fecha
                      const newOffcanvas = new bootstrap.Offcanvas(carrinhoElement);
                      newOffcanvas.hide();
                  }
              } catch (error) {
                  console.error("Erro ao manipular offcanvas:", error);
                  // Fallback simples como último recurso
                  carrinhoElement.classList.remove("show");
                  document.body.classList.remove("offcanvas-open");
                  document.querySelector(".offcanvas-backdrop")?.remove();
              }
          }
      }
      
      // Usando delegação para os botões de exclusão também
      if (event.target && (event.target.classList.contains("delete-cart-btn") || 
          (event.target.parentElement && event.target.parentElement.classList.contains("delete-cart-btn")))) {
          
          console.log("Botão de exclusão clicado");
          
          // Garantir que temos o botão mesmo se o clique foi no ícone dentro dele
          const deleteBtn = event.target.classList.contains("delete-cart-btn") ? 
                            event.target : event.target.parentElement;
              
          const row = deleteBtn.closest(".row");
          if (row) {
              // Remover o HR após o item
              const nextElement = row.nextElementSibling;
              if (nextElement && nextElement.tagName.toLowerCase() === "hr") {
                  nextElement.remove();
              }
              
              // Remover o próprio item
              row.remove();
              
              // Atualizar o contador no título
              updateCartCount();
              
              // Verificar se o carrinho está vazio
              checkEmptyCart();
          }
      }
  });
  
  // Função para atualizar o contador de itens
  function updateCartCount() {
      const itemRows = document.querySelectorAll("#carrinho .offcanvas-body .row");
      const titleElement = document.getElementById("offcanvasRightLabel");
      
      if (titleElement) {
          titleElement.textContent = `Shopping Cart (${itemRows.length})`;
          console.log(`Carrinho atualizado: ${itemRows.length} itens`);
      }
  }
  
  // Função para verificar se o carrinho está vazio
  function checkEmptyCart() {
      const itemRows = document.querySelectorAll("#carrinho .offcanvas-body .row");
      const containerElement = document.querySelector("#carrinho .offcanvas-body .container");
      
      if (itemRows.length === 0 && containerElement) {
          containerElement.innerHTML = `
              <div class="text-center py-4">
                  <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                  <p class="my-3">O seu carrinho está vazio</p>
              </div>
          `;
          console.log("Carrinho vazio - mensagem exibida");
      }
  }
  
  // Verificar inicialmente se temos botões para configurar
  console.log("Botão continuar compra:", document.getElementById("continuar-compra") ? "encontrado" : "não encontrado");
  console.log("Botões de exclusão:", document.querySelectorAll(".delete-cart-btn").length);
});
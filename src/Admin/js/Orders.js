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

  getOrders();

  async function getOrders() {
    try {
      const response = await fetch("orderEngine.php");
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      const ordersTable = document.querySelector(".orders-table");

      if (!ordersTable) {
        return;
      }

      ordersTable.innerHTML = ""; 

      if (data.status === 'error') {
        ordersTable.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error loading orders: ${data.message}</td></tr>`;
        return;
      }

      if (!Array.isArray(data) || data.length === 0) {
        ordersTable.innerHTML = '<tr><td colspan="7" class="text-center">No orders found</td></tr>';
        return;
      }

      data.forEach((order) => {
        const row = document.createElement("tr");
        
        let statusBadgeClass = "bg-secondary";
        const status = order.status_encomenda || "Processing";
        
        if (status.toLowerCase() === "completed") statusBadgeClass = "bg-success";
        if (status.toLowerCase() === "pending") statusBadgeClass = "bg-warning text-dark";
        if (status.toLowerCase() === "cancelled") statusBadgeClass = "bg-danger";
        
        const isPaid = order.fatura && order.fatura.trim() !== "";
        const paymentBadgeClass = isPaid ? "bg-primary" : "bg-danger";
        const paymentStatus = isPaid ? "Paid" : "Unpaid";
        
        row.innerHTML = `
          <td><a href="order_info.php?id=${order.id_encomenda}">#${order.id_encomenda}</a></td>
          <td>${order.nome_cliente || "Unknown"}</td>
          <td>${order.data_criacao_encomenda || "N/A"}</td>
          <td><span class="badge ${statusBadgeClass}">${status}</span></td>
          <td><span class="badge ${paymentBadgeClass}">${paymentStatus}</span></td>
          <td>$${order.preco_total_encomenda || "0.00"}</td>
          <td><button class="btn btn-sm btn-outline-secondary">...</button></td>
        `;
        
        ordersTable.appendChild(row);
      });
    } catch (error) {
      const ordersTable = document.querySelector(".orders-table");
      if (ordersTable) {
        ordersTable.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Failed to load orders: ${error.message}</td></tr>`;
      }
    }
  }
});

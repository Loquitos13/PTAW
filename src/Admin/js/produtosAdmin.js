document.getElementById('productForm').addEventListener('submit', async function(e) {
  e.preventDefault();

    const formData = {
        id_categoria: 1,
        titulo_produto: "Camisola",
        modelo3d_produto: "",
        descricao_produto: "abacaxi",
        imagem_principal: "",
        preco_produto: "100",
        stock_produto: 1,
        keywords_produto: "fruta", 
        status_produto: 1,
        data_criacao_produto: "2025-05-19 00:00:00"
    }

  try {
    const response = await fetch('../../admin/insertProducts.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    });

    const data = await response.json();

    console.log(data);

  } catch (error) {

    console.error(error);

  }
});


/*document.getElementById("productForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    let titulo_produto = document.getElementById("productName").querySelector("input").value;
    let keywords_produto = document.getElementById("keywords").querySelector("input").value;
    let id_categoria = document.getElementById("category").querySelector("select").value;
    let preco_produto = parseFloat(document.getElementById("price").querySelector("input").value);
    let stock_produto = document.getElementById("stock").querySelector("input").value;
    let status_produto = document.getElementById("status").querySelector("select").value === "Active" ? 1 : 0;
    let descricao_produto = document.getElementById("description").querySelector("textarea").value;
    let imagem_principal = document.getElementById("image").files[0];
    let modelo3d_produto = document.getElementById("model3d").value;  

    console.log("Título:", titulo_produto);
    console.log("Keywords:", keywords_produto);
    console.log("Categoria:", id_categoria);
    console.log("Preço:", preco_produto);
    console.log("Stock:", stock_produto);
    console.log("Status:", status_produto);
    console.log("Descrição:", descricao_produto);

    if (!titulo_produto || !keywords_produto || !id_categoria || !preco_produto || !stock_produto || !status_produto || !descricao_produto) {
        alert("Por favor, preencha todos os campos obrigatórios.");
        return;

    } else {

        const formData = {
            titulo_produto: "titulo_produto",
            keywords_produto: "keywords_produto",
            id_categoria:  1,//id_categoria,
            preco_produto: 100,//preco_produto,
            stock_produto: 100,//stock_produto,
            status_produto: 1,//status_produto,
            descricao_produto: "descricao_produto",
            imagem_principal: "imagem_principal"
        };

        const productResult = await productInsertion(formData);

        if (productResult.status === 'success') {

            window.location.href = "/PTAW/src/Admin/Dashboard.php";

        } 
    }

    async function productInsertion(formData) {

        const response = await fetch("/PTAW/admin/insertProducts.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(formData),
        });
        return await response.json();
    }

}) */
/*
async function fetchAndDisplayProducts() {
    try {
        const response = await fetch("../admin/getProducts.php");
        const data = await response.json();

        const tbody = document.getElementById("productTableBody");
        tbody.innerHTML = ""; // limpa a tabela antes de atualizar

        if (Array.isArray(data)) {
            data.forEach(product => {
                const tr = document.createElement("tr");

                tr.innerHTML = `
                    <th scope="row">${product.titulo_produto}</th>
                    <td>${product.nome_categoria}</td>
                    <td>${parseFloat(product.preco_produto).toFixed(2)}€</td>
                    <td>${product.stock_produto}</td>
                    <td>${product.stock_produto > 0 ? "In Stock" : "Out of Stock"}</td>
                    <td>
                        <button type="button" data-id="${product.id}" data-bs-toggle="modal" data-bs-target="#editProductModal">
                            <img src="../imagens/editButton.png" alt="Edit">
                        </button>
                        <button type="button" data-id="${product.id}" data-bs-toggle="modal" data-bs-target="#deleteProductModal">
                            <img src="../imagens/deleteButton.png" alt="Delete">
                        </button>
                    </td>
                `;

                tbody.appendChild(tr);
            });
        } else {
            console.error("Dados inesperados:", data);
        }
    } catch (err) {
        console.error("Erro ao buscar produtos:", err);
    }
}
*/

// Atualiza os produtos de 30 em 30 segundos
//setInterval(fetchAndDisplayProducts, 30000);

// FUNÇÕES DE UPDATE E DELETE (Ainda é preciso criar a estrutura))
/*
async function productUpdate(productId, formData) {
        const response = await fetch(`../admin/updateProducts.php`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(formData),
        });

        return await response.json();
    }


    async function productDeletion(productId) {
        const response = await fetch(`../admin/deleteProducts.php`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
            }
        });

        return await response.json();
    }
        */
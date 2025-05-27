let currentEditProductId = null;
let currentDeleteProductId = null;

// Armazena o ID do produto que está a ser editado
document.addEventListener('click', function(e) {
    const editButton = e.target.closest('button[data-bs-target="#editProductModal"]');
    if (editButton) {
        currentEditProductId = editButton.getAttribute('data-id');
        console.log("ID armazenado:", currentEditProductId);
    }
});
document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener('click', function(e) {
    const deleteButton = e.target.closest('button[data-bs-target="#deleteProductModal"]');
    if (deleteButton) {
        currentDeleteProductId = deleteButton.getAttribute('data-id');
        console.log("ID armazenado:", currentDeleteProductId);
    }
});

});

/*
document.getElementById('productForm').addEventListener('submit', async function (e) {
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
        data_criacao_produto: "2025-05-23 00:00:00"
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
}); */


document.getElementById("productForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    let titulo_produto = document.getElementById("productName").value;
    let keywords_produto = document.getElementById("keywords").value;
    let id_categoria = document.getElementById("category").value;
    let preco_produto = parseFloat(document.getElementById("price").value);
    let stock_produto = document.getElementById("stock").value;
    let status_produto = document.getElementById("status").value === "Active" ? 1 : 0;
    let descricao_produto = document.getElementById("description").value;
    /* let imagem_principal = document.getElementById("image").files[0];
     let modelo3d_produto = document.getElementById("model3d").value;  */

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
            id_categoria: id_categoria,
            titulo_produto: titulo_produto,
            modelo3d_produto: "",
            descricao_produto: descricao_produto,
            imagem_principal: "",
            preco_produto: preco_produto,
            stock_produto: stock_produto,
            keywords_produto: keywords_produto,
            status_produto: status_produto,
            data_criacao_produto: new Date().toISOString().slice(0, 19).replace('T', ' ')
        };

        const productResult = await productInsertion(formData);

        if (productResult.status === 'success') {

            window.location.href = "/PTAW/src/Admin/produtosAdmin.php";

        }
    }

    async function productInsertion(formData) {

        const response = await fetch("../../admin/insertProducts.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(formData),
        });
        return await response.json();
    }

})


    // Carrega os produtos da base de dados
    document.addEventListener("DOMContentLoaded", function () {
        fetchAndDisplayProducts();
    });

async function fetchAndDisplayProducts() {
    try {
        const response = await fetch("../../admin/getProducts.php");
        const data = await response.json();

        const tbody = document.getElementById("productTableBody");
        tbody.innerHTML = ""; // limpa a tabela antes de atualizar

        if (Array.isArray(data)) {
            data.forEach(product => {
                const tr = document.createElement("tr");

                tr.innerHTML = `
                    <th scope="row">${product.titulo_produto}</th>
                    <td>${product.id_categoria}</td>
                    <td>${parseFloat(product.preco_produto).toFixed(2)}€</td>
                    <td>${product.stock_produto}</td>
                    <td>${product.stock_produto > 0 ? "In Stock" : "Out of Stock"}</td>
                    <td>
                        <button type="button" class="btn btn-sm" data-id="${product.id_produto}" data-bs-toggle="modal" data-bs-target="#editProductModal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" class="btn btn-sm" data-id="${product.id_produto}" data-bs-toggle="modal" data-bs-target="#deleteProductModal">
                            <i class="bi bi-trash"></i>
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
        console.log("Resposta do servidor:", await response.text());
    }
}


async function productUpdate(formData) {
    const response = await fetch(`../../admin/editProducts.php`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
    });
    return await response.json();

}

document.addEventListener("DOMContentLoaded", function () {

    document.getElementById('editProductForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    let titulo_produto = document.getElementById("editProductName").value;
    let keywords_produto = document.getElementById("editKeywords").value;
    let id_categoria = document.getElementById("editCategory").value;
    let preco_produto = parseFloat(document.getElementById("editPrice").value);
    let stock_produto = document.getElementById("editStock").value;
    let status_produto = document.getElementById("editStatus").value === "Active" ? 1 : 0;
    let descricao_produto = document.getElementById("editDescription").value;
    /* let imagem_principal = document.getElementById("image").files[0];
     let modelo3d_produto = document.getElementById("model3d").value;  */

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
            id_produto: currentEditProductId,
            id_categoria: id_categoria,
            titulo_produto: titulo_produto,
            modelo3d_produto: "",
            descricao_produto: descricao_produto,
            imagem_principal: "",
            preco_produto: preco_produto,
            stock_produto: stock_produto,
            keywords_produto: keywords_produto,
            status_produto: status_produto,
            data_criacao_produto:new Date().toISOString().slice(0, 19).replace('T', ' ')
        };

        const productResult = await productUpdate(formData);

        if (productResult.status === 'success') {
            await fetchAndDisplayProducts();
            window.location.href = "/PTAW/src/Admin/produtosAdmin.php";

        }
    }
});

});

async function productDeletion(productId) {
    const response = await fetch(`../../admin/deleteProducts.php`, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ id_produto: productId }),
    });

    return await response.json();
}


document.addEventListener("DOMContentLoaded", function () {

    document.getElementById("confirmDelete").addEventListener("click", async function (e) {
    e.preventDefault();
    await productDeletion(currentDeleteProductId);
    await fetchAndDisplayProducts();
    window.location.href = "/PTAW/src/Admin/produtosAdmin.php";
});

});






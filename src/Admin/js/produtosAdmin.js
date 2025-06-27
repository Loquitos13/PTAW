let allProductsAdmin = [];
let selectedColors = [];
let selectedDimensions = [];
let currentEditProductId = null;
let currentDeleteProductId = null;
let currentCategoryFilter = "all";
let currentStatusFilter = "all";


document.addEventListener('DOMContentLoaded', async function () {
    await carregarProdutosAdmin();
    fetchAndDisplayProducts("all", "all");

    // Limpa os arrays quando abre o formulário de inserção
    document.getElementById('addProductModal').addEventListener('show.bs.modal', function () {
        selectedColors = [];
        selectedDimensions = [];
        const form = document.getElementById('productForm');
        showColors(form);
        showDimensions(form);
    });

    // Armazena o ID do produto que está a ser editado
    document.addEventListener('click', function (e) {
        const form = document.getElementById('editProductForm');
        const editButton = e.target.closest('button[data-bs-target="#editProductModal"]');
        if (editButton) {
            currentEditProductId = editButton.getAttribute('data-id');
            const produto = allProductsAdmin.find(p => p.id_produto == currentEditProductId);
            if (produto) {
                selectedColors = [];
                selectedDimensions = [];

                if (produto.cores) {
                    selectedColors = produto.cores.map(cor => ({
                        id_cor: cor.id_cor,
                        hex_cor: cor.hex_cor,
                        nome_cor: cor.nome_cor
                    }));
                }
                if (produto.dimensoes) {
                    selectedDimensions = produto.dimensoes.map(dim => ({
                        dimensao_tipo: dim.dimensao_tipo,
                        tamanho: dim.tamanho
                    }));
                }

                preencherModalEdicao(produto);
                showColors(form);
                showDimensions(form);
            }
        }
    });

    fetchCategories().then(categories => {
        document.querySelectorAll('.category-options').forEach(categoryScroll => {
            let optionAll = document.createElement('option');
            optionAll.value = "all";
            optionAll.innerHTML = "All Categories";
            optionAll.selected = true;
            categoryScroll.appendChild(optionAll);

            for (let i = 0; i < categories.length; i++) {
                let option = document.createElement('option');
                option.value = categories[i].id_categoria;
                option.innerHTML = categories[i].titulo_categoria;
                categoryScroll.appendChild(option);
            }
        });
    });

    document.querySelectorAll('.category-options').forEach(categoryScroll => {
        categoryScroll.addEventListener("change", async function () {
            currentCategoryFilter = this.value;
            if (currentCategoryFilter.innerHTML === "All Categories") {
                currentCategoryFilter = "all";
            }
            await fetchAndDisplayProducts(currentCategoryFilter, currentStatusFilter);
        });
    })

    fetchProducts().then(products => {
        const statusAdicionados = new Set();
        const opcoesStatus = [];

        for (let i = 0; i < products.length; i++) {
            const status = products[i].status_produto;
            if (!statusAdicionados.has(status)) {
                statusAdicionados.add(status);
                opcoesStatus.push(status);
            }
        }

        document.querySelectorAll('.status-options').forEach(statusScroll => {
            statusScroll.innerHTML = "";
            let optionAll = document.createElement('option');
            optionAll.value = "all";
            optionAll.innerHTML = "All Status";
            optionAll.selected = true;
            statusScroll.appendChild(optionAll);

            opcoesStatus.forEach(status => {
                let option = document.createElement('option');
                option.value = status;
                option.innerHTML = status == 1 ? "Active" : "Inactive";
                statusScroll.appendChild(option);
            });
        });
    });

    document.querySelectorAll('.status-options').forEach(statusScroll => {
        statusScroll.addEventListener("change", async function () {
            currentStatusFilter = this.value;
            if (currentStatusFilter.innerHTML === "All Status") {
                currentStatusFilter = "all";
            }
            await fetchAndDisplayProducts(currentCategoryFilter, currentStatusFilter);
        });
    })

    // Formulário de Inserção
    document.getElementById("productForm").addEventListener("submit", async function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        const variantes = selectedColors.map(cor => ({
            id_cor: cor.hex.replace('#', '')
        }));

        // Transformar dimensões no formato esperado
        const dimensoes = selectedDimensions.map(dim => ({
            dimensao_tipo: "",
            tamanho: dim
        }));

        formData.append('variantes', JSON.stringify(variantes));
        formData.append('dimensoes', JSON.stringify(dimensoes));

        formData.delete('cor_produto[]');
        formData.delete('dimensao_produto[]');

        if (!formData.get("imagem_principal") || !formData.get("titulo_produto") || !formData.get("keywords_produto") ||
            !formData.get("id_categoria") || !formData.get("preco_produto") || !formData.get("stock_produto") ||
            !formData.get("status_produto") || !formData.get("descricao_produto")) {
            alert("Por favor, preencha todos os campos obrigatórios.");
            return;
        }

        const response = await fetch("../../admin/insertProducts.php", {
            method: "POST",
            body: formData
        });
        const productResult = await response.json();

        if (productResult.status === 'success') {
            await carregarProdutosAdmin();
            await fetchAndDisplayProducts(currentCategoryFilter, currentStatusFilter);
            const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
            if (modal) modal.hide();
            alert('Produto inserido com sucesso!');
        }
    });

    // Formulário de Edição
    document.getElementById('editProductForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const productResult = await productUpdate(this);

        if (productResult.status === 'success') {
            await carregarProdutosAdmin();
            await fetchAndDisplayProducts(currentCategoryFilter, currentStatusFilter);
            const modal = bootstrap.Modal.getInstance(document.getElementById('editProductModal'));
            if (modal) modal.hide();
            alert('Produto atualizado com sucesso!');
        }
    });

    // Confirmação de Eliminação
    document.getElementById("confirmDelete").addEventListener("click", async function (e) {
        e.preventDefault();
        await productDeletion(currentDeleteProductId);
        await carregarProdutosAdmin();
        await fetchAndDisplayProducts(currentCategoryFilter, currentStatusFilter);
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteProductModal'));
        if (modal) modal.hide();
        alert('Produto eliminado com sucesso!');
    });

    // Atualizar referência do ficheiro (inserção)
    const browseImageButton = document.getElementById("browseImageButton");
    const productImageInput = document.getElementById("productImage");
    const fileNameImage = document.getElementById("fileNameImage");

    browseImageButton.addEventListener("click", function (e) {
        e.preventDefault();
        productImageInput.click();
    });

    productImageInput.addEventListener("change", function () {
        if (productImageInput.files.length > 0) {
            fileNameImage.textContent = productImageInput.files[0].name;
        } else {
            fileNameImage.textContent = "";
        }
    });

    const browseModelButton = document.getElementById("browseModelButton");
    const productModelInput = document.getElementById("productModel3D");
    const fileNameModel = document.getElementById("fileNameModel");

    browseModelButton.addEventListener("click", function (e) {
        e.preventDefault();
        productModelInput.click();
    });

    productModelInput.addEventListener("change", function () {
        if (productModelInput.files.length > 0) {
            fileNameModel.textContent = productModelInput.files[0].name;
        } else {
            fileNameModel.textContent = "";
        }
    });

    // Atualizar referência do ficheiro (edição)
    const editBrowseButton = document.getElementById("editBrowseButton");
    const productImageEdited = document.getElementById("editProductImage");
    const editFileName = document.getElementById("editFileName");

    editBrowseButton.addEventListener("click", function (e) {
        e.preventDefault();
        productImageEdited.click();
    });

    productImageEdited.addEventListener("change", function () {
        if (productImageEdited.files.length > 0) {
            editFileName.textContent = productImageEdited.files[0].name;
        } else {
            editFileName.textContent = "";
        }
    });

    const editModelButton = document.getElementById("editModelButton");
    const productModelEdited = document.getElementById("editProductModel3D");
    const editNameModel = document.getElementById("editNameModel");

    editModelButton.addEventListener("click", function (e) {
        e.preventDefault();
        productModelEdited.click();
    });

    productModelEdited.addEventListener("change", function () {
        if (productModelEdited.files.length > 0) {
            editNameModel.textContent = productModelEdited.files[0].name;
        } else {
            editNameModel.textContent = "";
        }
    });

    // Atualizar valor do color picker em tempo real
    document.addEventListener('input', function (e) {
        if (e.target.matches('input[type="color"].color-picker')) {
            const span = e.target.parentElement.querySelector('.colorValue');
            if (span) span.textContent = e.target.value;
        }
    });

    // Guardar o ID do produto a eliminar
    document.addEventListener('click', function (e) {
        const deleteButton = e.target.closest('button[data-bs-target="#deleteProductModal"]');
        if (deleteButton) {
            currentDeleteProductId = deleteButton.getAttribute('data-id');
            console.log("ID armazenado:", currentDeleteProductId);
        }
    });

    document.querySelectorAll('.addColorBtn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            const form = btn.closest('form');
            addColor(form);
        });
    });

    document.querySelectorAll('.addDimensionBtn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            const form = btn.closest('form');
            addDimension(form);
        });
    });

});

async function carregarProdutosAdmin() {
    const response = await fetch("../../admin/getProductsAdmin.php");
    allProductsAdmin = await response.json();
}

function showColors(form) {
    const coresDiv = form.querySelector('.selectedColors');
    coresDiv.innerHTML = "";
    selectedColors.forEach((cor, idx) => {
        const span = document.createElement('span');
        span.innerHTML = `<span class='colorClass' style="background:${cor.hex_cor} "></span> ${cor.nome_cor || cor.hex_cor}`;

        const btn = document.createElement('button');
        btn.textContent = "x";
        btn.onclick = () => { selectedColors.splice(idx, 1); showColors(form); };
        span.appendChild(btn);
        coresDiv.appendChild(span);
    });
}

function showDimensions(form) {
    const dimsDiv = form.querySelector('.selectedDimensions');
    dimsDiv.innerHTML = "";
    selectedDimensions.forEach((dim, idx) => {
        const span = document.createElement('span');
        span.textContent = dim.tamanho.replace(/%20/g, " ");

        const btn = document.createElement('button');
        btn.textContent = "x";
        btn.onclick = () => { selectedDimensions.splice(idx, 1); showDimensions(form); };
        span.appendChild(btn);
        dimsDiv.appendChild(span);
    });
}

function addColor(form) {
    const colorInput = form.querySelector('#variantsSection input[type="color"]');
    const colorValue = colorInput.value;
    // Podes pedir nome ou só hex
    if (!colorValue) {
        alert("Escolha uma cor!");
        return;
    }
    // Evitar duplicados
    if (selectedColors.some(c => c.hex_cor === colorValue)) {
        alert("Esta cor já foi adicionada.");
        return;
    }
    selectedColors.push({ hex_cor: colorValue, nome_cor: colorValue });
    showColors(form);
}

function addDimension(form) {
    const dimInput = form.querySelector('#variantsSection input[type="text"]');
    let dimValue = dimInput.value.trim();
    if (!dimValue) {
        alert("Introduza uma dimensão!");
        return;
    }
    dimValue = dimValue.replace(/ /g, "%20");
    if (selectedDimensions.some(d => d.tamanho === dimValue)) {
        alert("Esta dimensão já foi adicionada.");
        return;
    }
    selectedDimensions.push({ tamanho: dimValue });
    showDimensions(form);
}

function preencherModalEdicao(produto) {
    document.getElementById('editProductName').querySelector('input').value = produto.titulo_produto;
    document.getElementById('editKeywords').querySelector('input').value = produto.keywords_produto;
    document.getElementById('editCategory').querySelector('select').value = produto.id_categoria;
    document.getElementById('editPrice').querySelector('input').value = produto.preco_produto;
    document.getElementById('editStock').querySelector('input').value = produto.stock_produto;
    document.getElementById('editStatus').querySelector('select').value = String(produto.status_produto);
    document.getElementById('editDescription').querySelector('textarea').value = produto.descricao_produto;
    document.getElementById('editImagemPrincipal').value = produto.imagem_principal;

    const modelo3DElement = document.getElementById('editModelo3DProduto');
    if (modelo3DElement) {
        modelo3DElement.textContent = produto.modelo3d_produto || '';
    }
}
/*
function getVariantsByCategory(idCategoria) {
    const produtosCategoria = allProductsAdmin.filter(p => p.id_categoria == idCategoria);

    const cores = [];
    const tamanhos = [];
    produtosCategoria.forEach(produto => {
        if (produto.cores) {
            produto.cores.forEach(cor => {
                if (!cores.some(c => c.id_cor === cor.id_cor)) {
                    cores.push(cor);
                }
            });
        }
        if (produto.dimensoes) {
            produto.dimensoes.forEach(dim => {
                if (!tamanhos.includes(dim.tamanho)) {
                    tamanhos.push(dim.tamanho);
                }
            });
        }
    });

    return { cores, tamanhos };
}

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

async function fetchCategories() {
    try {
        const response = await fetch("../../admin/getCategories.php");
        const data = await response.json();
        if (Array.isArray(data)) {
            return data;
        } else {
            console.error("Dados inesperados:", data);
            return null;
        }
    } catch (err) {
        console.error("Erro ao buscar produtos:", err);
        return null;
    }
}

//Apenas vai buscar os produtos, não os mostra
async function fetchProducts() {
    try {
        const response = await fetch("../../admin/getProducts.php");
        const data = await response.json();
        if (Array.isArray(data)) {
            return data;
        } else {
            console.error("Dados inesperados:", data);
            return null;
        }
    } catch (err) {
        console.error("Erro ao buscar produtos:", err);
        return null;
    }
}


// Carrega e mostra os produtos da base de dados
async function fetchAndDisplayProducts(selectedCategory = null, selectedStatus = null) {
    try {
        const response = await fetch("../../admin/getProductsAdmin.php");
        const data = await response.json();

        allProductsAdmin = data;

        const tbody = document.getElementById("productTableBody");
        tbody.innerHTML = "";

        let filteredData = data;
        if (selectedCategory && selectedCategory !== "all") {
            filteredData = data.filter(product => product.id_categoria == selectedCategory);
        }

        if (selectedStatus && selectedStatus !== "all") {
            filteredData = filteredData.filter(product => product.status_produto == selectedStatus);
        }

        if (Array.isArray(filteredData)) {
            filteredData.forEach(product => {
                const tr = document.createElement("tr");

                tr.innerHTML = `
                    <th scope="row">${product.titulo_produto}</th>
                    <td>${product.id_categoria}</td>
                    <td>${parseFloat(product.preco_produto).toFixed(2)}€</td>
                    <td>${product.stock_produto}</td>
                    <td>${product.status_produto == 1 ? "Active" : "Inactive"}</td>
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


async function productUpdate(formElement) {
    const formData = new FormData(formElement);
    formData.set('id_produto', currentEditProductId);
    formData.append('cores', JSON.stringify(selectedColors));
    formData.append('dimensoes', JSON.stringify(selectedDimensions));

    if (!formData.get("imagem_principal") && !formData.get("product_image")) {
        alert("Por favor, selecione uma imagem ou mantenha a atual.");
        return;
    }

    const response = await fetch("../../admin/editProducts.php", {
        method: "POST",
        body: formData
    });
    return await response.json();
}

async function productDeletion(productId) {
    const response = await fetch(`../../admin/deleteProducts.php`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ id_produto: productId }),
    });

    return await response.json();
}


// função para adicionar o link ativado ao header
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("#link-produtos").innerHTML = `<li class="nav-item"><a href="<?= $base_url ?>/index.php" class="nav-link active" style="background-color: #4F46E5;">Products</a></li>`;
});

document.addEventListener('DOMContentLoaded', function () {
    // Função para configurar um slider com base nos IDs fornecidos
    function setupSlider(rangeMinId, rangeMaxId, thumbMinId, thumbMaxId, trackHighlightId, valueMinId, valueMaxId) {
        const rangeMin = document.getElementById(rangeMinId);
        const rangeMax = document.getElementById(rangeMaxId);
        const thumbMin = document.getElementById(thumbMinId);
        const thumbMax = document.getElementById(thumbMaxId);
        const trackHighlight = document.getElementById(trackHighlightId);
        const valueMin = document.getElementById(valueMinId);
        const valueMax = document.getElementById(valueMaxId);

        function updateSlider() {
            // Garantir que o valor mínimo não exceda o máximo
            if (parseInt(rangeMin.value) > parseInt(rangeMax.value)) {
                rangeMin.value = rangeMax.value;
            }

            const minPercent = (rangeMin.value / rangeMin.max) * 100;
            const maxPercent = (rangeMax.value / rangeMax.max) * 100;

            thumbMin.style.left = minPercent + '%';
            thumbMax.style.left = maxPercent + '%';
            trackHighlight.style.left = minPercent + '%';
            trackHighlight.style.width = (maxPercent - minPercent) + '%';
            valueMin.textContent = rangeMin.value;
            valueMax.textContent = rangeMax.value;
        }

        // Adicionar eventos de input
        rangeMin.addEventListener('input', updateSlider);
        rangeMax.addEventListener('input', updateSlider);

        // Inicializar o slider
        updateSlider();
    }

    // Configurar o slider principal
    setupSlider('range-min', 'range-max', 'thumb-min', 'thumb-max', 'track-highlight', 'value-min', 'value-max');

    // Configurar o slider mobile
    setupSlider('range-min-mobile', 'range-max-mobile', 'thumb-min-mobile', 'thumb-max-mobile', 'track-highlight-mobile', 'value-min-mobile', 'value-max-mobile');
});

/*
document.addEventListener('DOMContentLoaded', function () {
    const colors = ["red","blue","green","orange","purple","black"]; 

// Generate color buttons dynamically
const colorContainer = document.getElementById("idColorOptions");
colors.forEach(color => {
    const btn = document.createElement("button");
    btn.classList.add("colorBtn");
    btn.style.backgroundColor = color;
    btn.setAttribute("data-color", color);
    
    // Set click event immediately (slow for whatever reason and not working as intended)
    btn.onclick = function () {
        document.querySelectorAll(".colorBtn").forEach(colorBtn => colorBtn.classList.remove("activeColor"));
        this.classList.add("activeColor");
    };

    colorContainer.appendChild(btn);
});

const colorsMobile = ["red","blue","green","orange","purple","black"]; 


const colorContainerMobile = document.getElementById("idColorOptions-mobile");
colorsMobile.forEach(color => {
    const btn = document.createElement("button");
    btn.classList.add("colorBtn");
    btn.style.backgroundColor = color;
    btn.setAttribute("data-color", color);
    
    // Set click event immediately (slow for whatever reason and not working as intended)
    btn.onclick = function () {
        document.querySelectorAll(".colorBtn").forEach(colorBtn => colorBtn.classList.remove("activeColor"));
        this.classList.add("activeColor");
    };

    colorContainerMobile.appendChild(btn);
});

})*/
/* Descontinuado pois agora os tamanhos e feito pelo bootstrap
document.addEventListener('DOMContentLoaded', function () {
    const sizes = ["S", "M", "L", "XL", "2XL"];

    // Generate size buttons dynamically
    const sizeContainer = document.getElementById("idSizeOptions");
    sizes.forEach(size => {
        const btn = document.createElement("button");
        btn.classList.add("sizeBtn");
        btn.textContent = size;

        // Set click event immediately
        btn.onclick = function () {
            document.querySelectorAll(".sizeBtn").forEach(sizeBtn => sizeBtn.classList.remove("activeSize"));
            this.classList.add("activeSize");
        };

        sizeContainer.appendChild(btn);
    });

    const sizesMobile = ["S", "M", "L", "XL", "2XL"];

    // Generate size buttons dynamically
    const sizeContainerMobile = document.getElementById("idSizeOptions-mobile");
    sizesMobile.forEach(size => {
        const btn = document.createElement("button");
        btn.classList.add("sizeBtn");
        btn.textContent = size;

        // Set click event immediately
        btn.onclick = function () {
            document.querySelectorAll(".sizeBtn").forEach(sizeBtn => sizeBtn.classList.remove("activeSize"));
            this.classList.add("activeSize");
        };

        sizeContainerMobile.appendChild(btn);
    });

})
*/


document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const menuMobile = document.getElementById('menu-mobile');

    menuToggle.addEventListener('click', function () {
        if (menuMobile.style.display === 'flex') {
            menuMobile.style.display = 'none';
        } else {
            menuMobile.style.display = 'flex';
        }
    });

    // Optionally close the menu when clicking a link
    const links = menuMobile.querySelectorAll('a');
    links.forEach(link => {
        link.addEventListener('click', () => {
            menuMobile.style.display = 'none';
        });
    });
});

// Verificar responsividade
function checkResponsiveness() {
    const isMobile = window.innerWidth <= 576;
    const isTablet = window.innerWidth <= 768 && window.innerWidth > 576;

    // Ajustar altura do carrossel com base no tamanho da tela
    const carouselContent = document.querySelector('.carousel-content');
    const dataFeedbacks = document.querySelectorAll('.feedback-date');
    if (carouselContent) {
        if (isMobile) {
            carouselContent.style.minHeight = '350px';
            dataFeedbacks.forEach(item => {
                item.style.display = 'none';
            });
        } else if (isTablet) {
            carouselContent.style.minHeight = '320px';
            dataFeedbacks.forEach(item => {
                item.style.display = 'none';
            });
        } else {
            carouselContent.style.minHeight = '300px';
            dataFeedbacks.forEach(item => {
                item.style.display = 'block';
            });
        }
    }
}

// Variável para controlar o índice atual
let currentIndex = 0;


document.addEventListener("DOMContentLoaded", () => {
    checkResponsiveness();
});

document.addEventListener('DOMContentLoaded', function () {
    const filtersSidebar = document.getElementById('filters-sidebar');
    const filtersOverlay = document.getElementById('filters-overlay');
    const applyFilters = document.getElementById('apply-filters');

    // Fechar filtros
    function closeFiltersSidebar() {
        filtersSidebar.classList.remove('open');
        filtersOverlay.classList.remove('active');
    }

    if (filtersOverlay) {
        filtersOverlay.addEventListener('click', closeFiltersSidebar);
    }

    // Aplicar filtros
    if (applyFilters) {
        applyFilters.addEventListener('click', function () {
            // Sua lógica de filtragem aqui
            console.log('Filtros aplicados!');
            closeFiltersSidebar();
        });
    }
});

/* Exemplo para obter o valor do tamanho selecionado, no desktop e mobile
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('.btn-check[name="size"]');
    radios.forEach(function (radio) {
        radio.addEventListener('change', function () {
            if (radio.checked) {
                console.log('Tamanho selecionado, mobile:', radio.value);
            }
        });
    });

    const radios_desktop = document.querySelectorAll('.btn-check[name="size-desktop"]');
    radios_desktop.forEach(function (radio) {
        radio.addEventListener('change', function () {
            if (radio.checked) {
                console.log('Tamanho selecionado, desktop:', radio.value);
            }
        });
    });
});*/

async function atualizarCoresPorCategoria() {
    const colorContainer = document.getElementById("idColorOptions");
    if (!colorContainer) {
        console.error("Container de cores não encontrado");
        return;
    }
    colorContainer.innerHTML = ""; // Clear previous content immediately

    try {
        // Obtém os valores (IDs) das categorias que estão selecionadas.
        const categoriasSelecionadas = [...document.querySelectorAll('input[type=checkbox][id^="defaultCategory"]:checked')].map(cb => cb.value);
        // console.log("Categorias selecionadas:", categoriasSelecionadas);

        if (categoriasSelecionadas.length === 0) {
            // console.log("Nenhuma categoria selecionada para buscar cores.");
            colorContainer.innerHTML = "<p>Selecione uma categoria para ver as cores disponíveis.</p>";
            return;
        }

        // Converte o array de categorias selecionadas em uma string separada por vírgulas para a URL da API.
        const categoriasStr = categoriasSelecionadas.join(',');
        // console.log("Buscando cores para categorias:", categoriasStr);

        const response = await fetch(`../restapi/PrintGoAPI.php/getColorsByCategories/${categoriasStr}`);
        const data = await response.json();

        if (!data || (!data.cores && !Array.isArray(data))) {
            console.error("Formato de resposta inesperado ao buscar cores:", data);
            colorContainer.innerHTML = "<p>Erro ao carregar cores (formato inválido).</p>";
            return;
        }

        const cores = data.cores || (Array.isArray(data) ? data : []);

        if (cores.length === 0) {
            colorContainer.innerHTML = "<p>Nenhuma cor disponível para esta categoria.</p>";
            return;
        }

        cores.forEach(cor => {
            //console.log("Cor recebida:", cor);
            const input = document.createElement("input");
            input.type = "radio";
            input.className = "btn-check";
            input.name = "color";
            input.id = `color-${cor.nome_cor}`;
            input.value = cor.hex_cor;
            input.setAttribute('data-color-name', cor.nome_cor);
            input.autocomplete = "off";

            const label = document.createElement("label");
            label.className = "btnColor rounded-circle p-2";
            label.setAttribute("for", `color-${cor.nome_cor}`);
            label.style.backgroundColor = cor.hex_cor;
            label.style.border = "2px solid #ccc";
            label.title = cor.nome_cor;

            colorContainer.appendChild(input);
            colorContainer.appendChild(label);
        });
    } catch (error) {
        console.error("Erro ao atualizar cores:", error);
        colorContainer.innerHTML = "<p>Ocorreu um erro ao carregar as cores.</p>";
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const categoriasContainer = document.getElementById('categorias-container');
    // Adiciona um listener de evento 'change' ao container de categorias.
    // Isso permite detectar quando um checkbox de categoria é marcado ou desmarcado.
    categoriasContainer.addEventListener('change', function (e) {
        // Verifica se o evento foi disparado por um checkbox de categoria específico.
        if (e.target && e.target.matches('input[type=checkbox][id^="defaultCategory"]')) {
            atualizarCoresPorCategoria(); // Chama a função para atualizar as cores.
        }
    });
});

// Mostrar Tamanhos consoante a categoria selecionada
async function atualizarSizePorCategoria() {
    const sizeContainer = document.getElementById("size-selector-desktop");
    if (!sizeContainer) {
        console.error("Container de tamanhos (desktop) não encontrado.");
        return;
    }
    sizeContainer.innerHTML = ""; // Clear previous content immediately

    try {
        // Obtém os valores (IDs) das categorias que estão selecionadas.
        const categoriasSelecionadas = [...document.querySelectorAll('input[type=checkbox][id^="defaultCategory"]:checked')].map(cb => cb.value);
        // console.log("Categorias selecionadas para tamanhos:", categoriasSelecionadas);

        if (categoriasSelecionadas.length === 0) {
            // console.log("Nenhuma categoria selecionada para buscar tamanhos.");
            sizeContainer.innerHTML = "<p>Selecione uma categoria para ver os tamanhos disponíveis.</p>";
            return;
        }

        const categoriasStr = categoriasSelecionadas.join(',');
        // console.log("Buscando tamanhos para categorias:", categoriasStr);

        const response = await fetch(`../restapi/PrintGoAPI.php/getSizesByCategories/${categoriasStr}`);
        const data = await response.json();
        // console.log("Resposta da API de tamanhos:", data);

        if (!Array.isArray(data)) {
            console.error("Resposta da API de tamanhos não é um array:", data);
            sizeContainer.innerHTML = "<p>Erro ao carregar tamanhos (formato inválido).</p>";
            return;
        }

        if (data.length === 0) {
            sizeContainer.innerHTML = "<p>Nenhum tamanho disponível para esta categoria.</p>";
            return;
        }

        const todosTamanhos = data.flatMap(item => {
            if (typeof item.tamanho === 'string' && item.tamanho.includes(',')) {
                return item.tamanho.split(',').map(t => t.trim());
            }
            return item.tamanho;
        });

        // console.log("Todos os tamanhos combinados:", todosTamanhos);
        const tamanhosSemDuplicatas = [...new Set(todosTamanhos)].filter(t => t); // Filter out empty/null if any

        // console.log("Tamanhos únicos:", tamanhosSemDuplicatas);

        if (tamanhosSemDuplicatas.length === 0) {
            sizeContainer.innerHTML = "<p>Nenhum tamanho disponível para esta categoria após processamento.</p>";
            return;
        }

        // Itera sobre cada tamanho recebido e cria um input de rádio e um label correspondente.
        tamanhosSemDuplicatas.forEach(tamanho => {

            const btnGroup = document.createElement('div');
            btnGroup.classList.add('btn-group', 'me-2');
            btnGroup.setAttribute('role', 'group');
            btnGroup.setAttribute('aria-label', 'Size option');

            const input = document.createElement('input');
            input.type = 'radio';
            input.classList.add('btn-check');
            input.name = 'size-desktop';
            input.value = tamanho;
            input.id = `btnradio-${tamanho}-desktop`;
            input.autocomplete = 'off';

            const label = document.createElement('label');
            label.classList.add('btn', 'btn-outline-primary');
            label.setAttribute('for', `btnradio-${tamanho}-desktop`);
            label.textContent = tamanho.replace(/%20/g, " ");

            btnGroup.appendChild(input);
            btnGroup.appendChild(label);
            sizeContainer.appendChild(btnGroup);
        });
    } catch (error) {
        console.error("Erro ao atualizar tamanhos:", error); // Corrected error message
        sizeContainer.innerHTML = "<p>Ocorreu um erro ao carregar os tamanhos.</p>";
    }
}
document.addEventListener('DOMContentLoaded', function () {
    const categoriasContainer = document.getElementById('categorias-container');
    // Adiciona um listener de evento 'change' ao container de categorias.
    // Isso permite detectar quando um checkbox de categoria é marcado ou desmarcado.
    categoriasContainer.addEventListener('change', function (e) {
        // Verifica se o evento foi disparado por um checkbox de categoria específico.
        if (e.target && e.target.matches('input[type=checkbox][id^="defaultCategory"]')) {
            atualizarSizePorCategoria(); // Chama a função para atualizar as cores.
        }
    });
});


// Faz uma requisição inicial para obter os filtros (categorias) disponíveis.
fetch('../restapi/PrintGoAPI.php/getCategoriesByID')
    .then(response => response.json())
    .then(data => {

        const categoriasContainer = document.getElementById('categorias-container');

        // Agora data é um array de categorias
        data.forEach((categoria, i) => {

            const divCategoria = document.createElement('div');
            divCategoria.classList.add('form-check');

            const inputCategoria = document.createElement('input');
            inputCategoria.classList.add('form-check-input');
            inputCategoria.type = 'checkbox';
            inputCategoria.value = categoria.id_categoria;
            inputCategoria.id = `defaultCategory${i}`; // ID único agora

            const labelCategoria = document.createElement('label');
            labelCategoria.classList.add('form-check-label');
            labelCategoria.setAttribute('for', `defaultCategory${i}`);
            labelCategoria.textContent = categoria.titulo_categoria;

            divCategoria.appendChild(inputCategoria);
            divCategoria.appendChild(labelCategoria);
            categoriasContainer.appendChild(divCategoria);
        });
    })
    .catch(error => {
        console.error('Erro ao buscar produtos:', error);
    });



// Função para buscar produtos (com ou sem filtros)
function buscarProdutos(filtros = {}) {
    // Check if filters are empty
    const isEmptyFilters = Object.keys(filtros).length === 0 ||
        ((!filtros.categorias || filtros.categorias.length === 0) &&
            (!filtros.cores || filtros.cores.length === 0) &&
            (!filtros.tamanhos || filtros.tamanhos.length === 0));

    let url = '';

    if (isEmptyFilters) {
        // If no filters, get all products
        url = '../restapi/PrintGoAPI.php/products';
    } else {
        // Format parameters for the API endpoint
        const categoria = filtros.categorias && filtros.categorias.length > 0 ? filtros.categorias.join(',') : '_';
        const precoMinimo = filtros.precoMin || '0';
        const precoMaximo = filtros.precoMax || '100';
        const cor = filtros.cores && filtros.cores.length > 0 ? filtros.cores.join(',') : '_';
        const tamanho = filtros.tamanhos && filtros.tamanhos.length > 0 ? filtros.tamanhos.join(',') : '_';

        // Call the API endpoint with path parameters
        url = `../restapi/PrintGoAPI.php/filterProducts/${categoria}/${precoMinimo}/${precoMaximo}/${cor}/${tamanho}`;
    }

    console.log('URL final para fetch:', url);

    fetch(url)
        .then(response => {
            if (!response.ok) {
                console.error("API Error Response Status:", response.status);
                return response.text().then(text => { throw new Error("API Error: " + text) });
            }
            return response.json();
        })
        .then(data => {
            //console.log("Produtos:", data);
            const produtosLista = document.getElementById('produtos-container');
            produtosLista.innerHTML = "";
            const produtos = Array.isArray(data) ? data : data.products || [];
            if (produtos.length === 0) {
                produtosLista.innerHTML = "<p>Nenhum produto encontrado com os filtros selecionados.</p>";
            } else {
                produtos.forEach(produto => {
                    //cria o container principal para ficar responsivo
                    let Containner = document.createElement('div');
                    Containner.classList.add('col-lg-3', 'col-md-4', 'col-sm-6', 'mb-4');
                    // cria o card do produto
                    let card = document.createElement('div');
                    card.classList.add("card", "border-0", "shadow-sm");
                    // Cria a div que vai conter a imagem do produto
                    let divImg = document.createElement('div');
                    divImg.classList.add("position-relative");
                    divImg.style.width = "100%";
                    divImg.style.height = "240px";
                    divImg.style.display = "flex";
                    divImg.style.alignItems = "center";
                    divImg.style.justifyContent = "center";
                    divImg.style.overflow = "hidden";

                    // Imagem do produto
                    let img = document.createElement('img');
                    img.src = produto.imagem_principal;
                    img.classList.add("card-img-top", "bg-light");
                    img.alt = produto.titulo_produto;
                    // Ajustes para centralização perfeita
                    img.style.maxWidth = "80%";
                    img.style.maxHeight = "80%";
                    img.style.width = "auto";
                    img.style.height = "auto";
                    img.style.objectFit = "contain";
                    img.style.position = "absolute";
                    img.style.top = "50%";
                    img.style.left = "50%";
                    img.style.transform = "translate(-50%, -50%)";

                    // cria uma div para conter as informações do produto
                    let divInfo = document.createElement("div");
                    divInfo.classList.add("card-body", "px-3", "pb-3");
                    // Titulo do produto
                    let tituloProduto = document.createElement("h5");
                    tituloProduto.classList.add("card-title", "fw-bold", "mb-1");
                    tituloProduto.textContent = produto.titulo_produto;
                    // Cria uma div para conter o preço e o botão
                    let divPrecoBtn = document.createElement("div");
                    divPrecoBtn.classList.add("d-flex", "justify-content-between", "align-items-center");
                    // Preço do produto
                    let precoProduto = document.createElement("span");
                    precoProduto.classList.add("fw-bold");
                    precoProduto.style.color = "#4F46E5";
                    precoProduto.textContent = produto.preco_produto + "€";
                    // Botão de comprar
                    let btnComprar = document.createElement("button");
                    btnComprar.type = "button";
                    btnComprar.classList.add("btn", "btn-primary");
                    btnComprar.style = "background-color: #4F46E5; border: 0;"
                    btnComprar.textContent = "Shop Now";
                    // Adiciona o evento de clique para redirecionar para a página do produto
                    btnComprar.addEventListener('click', function () {
                        window.location.href = "productscustom.php?id=" + produto.id_produto;
                    });

                    card.appendChild(divImg);
                    card.appendChild(divInfo);

                    divImg.appendChild(img);

                    divInfo.appendChild(tituloProduto);
                    divInfo.appendChild(divPrecoBtn);

                    divPrecoBtn.appendChild(precoProduto);
                    divPrecoBtn.appendChild(btnComprar);

                    Containner.appendChild(card);

                    // Adiciona o card ao container principal
                    produtosLista.appendChild(Containner);
                });
            }
        })
        .catch(error => {
            console.error('Erro ao buscar produtos ou processar resposta:', error);
            const produtosLista = document.getElementById('produtos-container');
            produtosLista.innerHTML = "<p>Ocorreu um erro ao carregar os produtos.</p>";
        });
}

// Ao carregar a página, buscar todos os produtos (sem filtros)
document.addEventListener('DOMContentLoaded', function () {
    buscarProdutos();
});

const applyFiltersDesktop = document.getElementById('apply-filters-desktop');
// Quando clicar em "Apply Filters", buscar com filtros
if (applyFiltersDesktop) {
    applyFiltersDesktop.addEventListener('click', function () {
        const filtros = {
            categorias: [...document.querySelectorAll('input[type=checkbox][id^="defaultCategory"]:checked')].map(cb => cb.value),
            precoMin: document.getElementById('range-min').value,
            precoMax: document.getElementById('range-max').value,
            cores: [...document.querySelectorAll('input[name="color"]:checked')].map(cb => cb.getAttribute('data-color-name')), // Use color name instead of hex value
            tamanhos: [...document.querySelectorAll('input[name="size-desktop"]:checked')].map(cb => cb.value),
        };
        // console.log("Filtros:" + JSON.stringify(filtros));
        buscarProdutos(filtros);
    });
}

const clearFiltersDesktop = document.getElementById('clear-filters-desktop');
if (clearFiltersDesktop) {
    clearFiltersDesktop.addEventListener('click', function () {
        // 1. Deselect all category checkboxes
        document.querySelectorAll('input[type=checkbox][id^="defaultCategory"]').forEach(checkbox => {
            checkbox.checked = false;
        });

        // 2. Deselect any checked color radio button
        const checkedColor = document.querySelector('input[name="color"]:checked');
        if (checkedColor) {
            checkedColor.checked = false;
        }

        // 3. Deselect any checked size radio button (desktop)
        const checkedSizeDesktop = document.querySelector('input[name="size-desktop"]:checked');
        if (checkedSizeDesktop) {
            checkedSizeDesktop.checked = false;
        }

        // 4. Reset the price range slider (desktop)
        const rangeMinDesktop = document.getElementById('range-min');
        const rangeMaxDesktop = document.getElementById('range-max');
        if (rangeMinDesktop && rangeMaxDesktop) {
            rangeMinDesktop.value = rangeMinDesktop.min; // Or "0"
            rangeMaxDesktop.value = rangeMaxDesktop.max; // Or "100"

            // Trigger input events to update slider UI
            rangeMinDesktop.dispatchEvent(new Event('input'));
            rangeMaxDesktop.dispatchEvent(new Event('input'));
        }

        // 5. Update color and size sections to reflect no categories selected
        // These functions will now clear their respective containers and show a placeholder message
        atualizarCoresPorCategoria();
        atualizarSizePorCategoria();

        // 6. Fetch and display all products (no filters)
        buscarProdutos();
    });
}
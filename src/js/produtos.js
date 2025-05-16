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



// Função para buscar produtos (com ou sem filtros)
function buscarProdutos(filtros = {}) {
    // Chamada para API para obter produtos
    // depois sera trocado por: http://~ptaw-grp4/PTAW/restapi/products
    fetch('../client/produtos.php', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(filtros)
    })
        .then(response => response.json())
        .then(data => {
            const produtosLista = document.getElementById('produtos-container');
            produtosLista.innerHTML = ""; // Limpa antes de adicionar novos
            data.forEach(produto => {

                //cria o container principal para ficar responsivo
                let Containner = document.createElement('div');
                Containner.classList.add('col-lg-3', 'col-md-4', 'col-sm-6', 'mb-4');
                // cria o card do produto
                let card = document.createElement('div');
                card.classList.add("card", "border-0", "shadow-sm");
                // Cria a div que vai conter a imagem do produto
                let divImg = document.createElement('div');
                divImg.classList.add("position-relative");
                // Imagem do produto
                let img = document.createElement('img');
                img.src = produto.imagem_principal;
                img.classList.add("card-img-top", "bg-light");
                img.alt = produto.titulo_produto;
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
        })
        .catch(error => {
            console.error('Erro ao buscar produtos:', error);
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
            categorias: [...document.querySelectorAll('input[type=checkbox][id^="defaultCategory"]:checked')].map(cb => cb.nextElementSibling.textContent.trim()),
            precoMin: document.getElementById('range-min').value,
            precoMax: document.getElementById('range-max').value,
            cores: [...document.querySelectorAll('input[name="color"]:checked')].map(cb => cb.id.replace('color-', '')),
            tamanhos: [...document.querySelectorAll('input[name="size-desktop"]:checked')].map(cb => cb.value),
        };
        // visualizar se os filtros estão corretos
        console.log(filtros);
        buscarProdutos(filtros);
        closeFiltersSidebar();
    });
}

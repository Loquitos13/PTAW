/*const feedbacks = [
  {
    id: 1,
    name: "Ana Silva",
    avatar: "/api/placeholder/100/100",
    role: "Cliente desde 2021",
    content:
      "Serviço excepcional! A Print & Go foi muito atenciosa e resolveu o meu problema rapidamente. Recomendo fortemente para todos os que precisam de um trabalho de qualidade.",
    rating: 5,
    date: "12 de Março, 2025",
    tags: ["Suporte", "Atendimento"],
  },
  {
    id: 2,
    name: "Carlos Mendes",
    avatar: "/api/placeholder/100/100",
    role: "Cliente desde 2020",
    content:
      "Utilizei os serviços várias vezes e sempre fiquei satisfeito com os resultados. A Print & Go é muito profissional e eficiente.",
    rating: 5,
    date: "5 de Março, 2025",
    tags: ["Pontualidade", "Qualidade"],
  },
  {
    id: 3,
    name: "Mariana Costa",
    avatar: "/api/placeholder/100/100",
    role: "Cliente desde 2022",
    content: "Trabalho impecável!",
    rating: 4,
    date: "28 de Fevereiro, 2025",
    tags: ["Pesquisa", "Qualidade"],
  },
  {
    id: 4,
    name: "Pedro Almeida",
    avatar: "/api/placeholder/100/100",
    role: "Cliente desde 2023",
    content: "Com certeza voltarei a comprar!",
    rating: 5,
    date: "15 de Fevereiro, 2025",
    tags: ["Preço", "Pontualidade"],
  },
  {
    id: 5,
    name: "Juliana Santos",
    avatar: "/api/placeholder/100/100",
    role: "Cliente desde 2022",
    content:
      "Atendimento personalizado e de alta qualidade. Senti que realmente se importavam com o meu projeto e se esforçaram para entregar o melhor resultado possível.",
    rating: 5,
    date: "7 de Fevereiro, 2025",
    tags: ["Personalização", "Atendimento"],
  },
];
*/

document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.getElementById("menu-toggle");
  const menuMobile = document.getElementById("menu-mobile");

  if (menuToggle && menuMobile) {
    menuToggle.addEventListener("click", function () {
      if (menuMobile.style.display === "flex") {
        menuMobile.style.display = "none";
      } else {
        menuMobile.style.display = "flex";
      }
    });

    // Optionally close the menu when clicking a link
    const links = menuMobile.querySelectorAll("a");
    links.forEach((link) => {
      link.addEventListener("click", () => {
        menuMobile.style.display = "none";
      });
    });
  }

  const userIdInput = document.getElementById("userId");
  if (userIdInput) {
    console.log("User ID:", userIdInput.value);
  } else {
    console.log("Elemento #userId não encontrado.");
  }
});

// estrutura de dados para feedbacks
let feedbacks = [];
let currentIndex = 0;

// função utilitária para remover acentos e converter para minúsculas
function normalizarTexto(texto) {
  if (!texto) return "";
  return texto
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "");
}

// obter feedbacks da api
async function getFeedbacks() {
  try {
    const response = await fetch("../client/productReview.php", {
      method: "GET",
    });

    if (!response.ok) {
      throw new Error("erro na requisição: " + response.status);
    }

    const result = await response.json();

    if (result.status !== "success") {
      throw new Error("erro no retorno da api: " + (result.message || ""));
    }

    return result.data;
  } catch (err) {
    console.error("erro ao carregar feedbacks:", err);
    return [];
  }
}

// função para converter e exibir feedbacks
async function convertFeedbacks() {
  try {
    feedbacks = await getFeedbacks();
    console.log("feedbacks recebidos:", feedbacks);

    const container = document.querySelector("#carouselItems");
    const indicatorContainer = document.querySelector("#indicator");
    const carouselContainer = document.querySelector('.feedback-carousel-container');

    if (!container) {
      console.error("container de itens não encontrado");
      return;
    }

    let createdIndicator = false;
    if (!indicatorContainer) {
      console.warn("container de indicadores não encontrado, a criar novo");

      const newIndicator = document.createElement('div');
      newIndicator.id = 'indicator';
      newIndicator.className = 'carousel-indicator';
      newIndicator.style.display = 'flex';
      newIndicator.style.justifyContent = 'center';
      newIndicator.style.gap = '8px';
      newIndicator.style.margin = '15px 0';

      if (container.parentNode) {
        container.parentNode.insertBefore(newIndicator, container.nextSibling);
      } else if (carouselContainer) {
        carouselContainer.appendChild(newIndicator);
      }

      createdIndicator = true;
    }

    const updatedIndicatorContainer = document.querySelector("#indicator");

    if (!updatedIndicatorContainer) {
      console.error("não foi possível criar ou encontrar o container de indicadores");
      return;
    }

    container.innerHTML = "";
    updatedIndicatorContainer.innerHTML = "";

    if (!Array.isArray(feedbacks) || feedbacks.length === 0) {
      container.innerHTML = "<p>nenhum feedback disponível.</p>";
      return;
    }

    feedbacks.forEach((feedback, i) => {
      const feedbackAdaptado = {
        id: feedback.id_cliente,
        name: normalizarTexto(`cliente ${feedback.id_cliente}`),
        role: normalizarTexto(`produto ${feedback.id_categoria}`),
        content: normalizarTexto(feedback.comentario),
        rating: parseInt(feedback.classificacao) || 0,
        date: normalizarTexto(feedback.data_review),
        tags: [],
      };

      const feedbackItem = createFeedbackItem(feedbackAdaptado);
      container.appendChild(feedbackItem);

      const dot = document.createElement("span");
      dot.classList.add("carousel-dot");
      dot.style.width = "10px";
      dot.style.height = "10px";
      dot.style.borderRadius = "50%";
      dot.style.backgroundColor = "#ccc";
      dot.style.display = "inline-block";
      dot.style.cursor = "pointer";

      if (i === 0) {
        dot.classList.add("active-dot");
        dot.style.backgroundColor = "#333";
      }

      dot.addEventListener("click", () => {
        goToSlide(i);
      });

      updatedIndicatorContainer.appendChild(dot);
    });

    createNavigationButtonsIfNeeded(carouselContainer);

    console.log(`total de feedbacks adicionados: ${feedbacks.length}`);
    console.log(`total de itens no container: ${container.children.length}`);
    console.log(`total de indicadores: ${updatedIndicatorContainer.children.length}`);

    const allItems = document.querySelectorAll(".carousel-item-container");
    console.log(`total de itens com classe carousel-item-container: ${allItems.length}`);

    if (createdIndicator) {
      const style = document.createElement('style');
      style.textContent = `
        .carousel-dot {
          width: 10px;
          height: 10px;
          border-radius: 50%;
          background-color: #ccc;
          margin: 0 5px;
          cursor: pointer;
          display: inline-block;
          transition: background-color 0.3s ease;
        }
        .carousel-dot.active-dot {
          background-color: #333;
        }
      `;
      document.head.appendChild(style);
    }

    updateCarouselState(0);
    checkResponsiveness();
    // startAutoSlide();
  } catch (error) {
    console.error("erro ao carregar feedbacks:", error);
    const carouselItems = document.querySelector("#carouselItems");
    if (carouselItems) {
      carouselItems.innerHTML = "<p>erro ao carregar feedbacks.</p>";
    }
  }
}

// actualizar o estado do carrossel
function updateCarouselState(index) {
  const items = document.querySelectorAll(".carousel-item-container");
  const total = items.length;

  if (total === 0) return;

  console.log(`actualizar carrossel para índice ${index}. total de itens: ${total}`);

  if (index < 0) index = total - 1;
  if (index >= total) index = 0;

  items.forEach((item, i) => {
    console.log(`item ${i}: visibilidade=${i === index ? 'visível' : 'oculto'}`);

    if (i === index) {
      item.classList.remove("hidden-item");
      item.classList.add("active");
    } else {
      item.classList.add("hidden-item");
      item.classList.remove("active");
    }
  });

  const dots = document.querySelectorAll(".carousel-dot");

  if (dots.length > 0) {
    console.log(`actualizar ${dots.length} indicadores para o índice ${index}`);

    dots.forEach((dot, i) => {
      if (i === index) {
        dot.classList.add("active-dot");
        dot.style.backgroundColor = "#333";
      } else {
        dot.classList.remove("active-dot");
        dot.style.backgroundColor = "#ccc";
      }
    });
  } else {
    console.warn("nenhum indicador (dot) encontrado para actualizar");
  }

  currentIndex = index;
}

// criar botões de navegação se necessário
function createNavigationButtonsIfNeeded(carouselContainer) {
  if (!carouselContainer) {
    console.error("container do carrossel não encontrado");
    carouselContainer = document.querySelector('.carousel-container') ||
      document.querySelector('#carouselContainer') ||
      document.querySelector('.feedback-carousel-container') ||
      document.querySelector('.carousel');

    if (!carouselContainer) {
      console.error("container do carrossel não encontrado mesmo após tentativas alternativas");
      return;
    } else {
      console.log("container do carrossel encontrado usando seletor alternativo:", carouselContainer);
    }
  }

  const existingPrevButton = document.querySelector('.carousel-prev, .prev-button, #prevBtn, [data-action="prev"], #prevButton');
  const existingNextButton = document.querySelector('.carousel-next, .next-button, #nextBtn, [data-action="next"], #nextButton');

  console.log("botões existentes:", { prev: existingPrevButton, next: existingNextButton });

  if (existingPrevButton && existingNextButton) {
    console.log("a configurar botões de navegação existentes");

    existingPrevButton.addEventListener("click", () => {
      console.log("botão anterior clicado");
      goToPrevSlide();
    });

    existingNextButton.addEventListener("click", () => {
      console.log("botão seguinte clicado");
      goToNextSlide();
    });

    return;
  }

  console.log("a criar botões de navegação...");

  const navContainer = document.createElement('div');
  navContainer.className = 'carousel-navigation';
  navContainer.style.display = 'flex';
  navContainer.style.justifyContent = 'space-between';
  navContainer.style.margin = '10px 0';
  navContainer.style.position = 'relative';
  navContainer.style.width = '100%';

  const prevButton = document.createElement('button');
  prevButton.className = 'carousel-prev';
  prevButton.innerHTML = '&lt;';
  prevButton.setAttribute('aria-label', 'slide anterior');
  prevButton.style.cursor = 'pointer';
  prevButton.style.padding = '5px 10px';
  prevButton.style.backgroundColor = '#f0f0f0';
  prevButton.style.border = 'none';
  prevButton.style.borderRadius = '3px';

  const nextButton = document.createElement('button');
  nextButton.className = 'carousel-next';
  nextButton.innerHTML = '&gt;';
  nextButton.setAttribute('aria-label', 'slide seguinte');
  nextButton.style.cursor = 'pointer';
  nextButton.style.padding = '5px 10px';
  nextButton.style.backgroundColor = '#f0f0f0';
  nextButton.style.border = 'none';
  nextButton.style.borderRadius = '3px';

  navContainer.appendChild(prevButton);
  navContainer.appendChild(nextButton);

  carouselContainer.parentNode.insertBefore(navContainer, carouselContainer.nextSibling);

  prevButton.addEventListener('click', () => {
    console.log("botão anterior clicado");
    goToPrevSlide();
  });

  nextButton.addEventListener('click', () => {
    console.log("botão seguinte clicado");
    goToNextSlide();
  });

  console.log("botões de navegação criados com sucesso");
}

function createStars(rating) {
  let stars = "";
  for (let i = 0; i < 5; i++) {
    stars += `<span class="star">${i < rating ? "★" : "☆"}</span>`;
  }
  return stars;
}

function createFeedbackItem(feedback) {
  if (!feedback || !feedback.id) {
    console.error("feedback inválido:", feedback);
    return null;
  }

  const item = document.createElement("div");
  item.className = "carousel-item-container hidden-item";
  item.setAttribute("data-id", feedback.id);

  const tags = feedback.tags && Array.isArray(feedback.tags)
    ? feedback.tags.map((tag) => `<span class="feedback-tag">${normalizarTexto(tag)}</span>`).join("")
    : "";

  item.innerHTML = `
    <div class="feedback-item">
      <div class="feedback-header"> 
        <div class="feedback-author">
          <h3>${feedback.name || 'anónimo'}</h3>
          <p>${feedback.role || ''}</p>
        </div>
      </div>
      <div class="feedback-content">
        "${feedback.content || ''}"
      </div>
      <div class="feedback-rating">
        ${createStars(feedback.rating)}
      </div>
      <div class="feedback-metadata">
        <div class="feedback-tags">
          ${tags}
        </div>
        <div class="feedback-date">${feedback.date || ''}</div>
      </div>
    </div>
  `;

  return item;
}

function checkResponsiveness() {
  const isMobile = window.innerWidth <= 576;
  const isTablet = window.innerWidth <= 768 && window.innerWidth > 576;

  const carouselContent = document.querySelector(".carousel-content");
  const dataFeedbacks = document.querySelectorAll(".feedback-date");

  if (carouselContent) {
    if (isMobile) {
      carouselContent.style.minHeight = "350px";
      dataFeedbacks.forEach((item) => {
        item.style.display = "none";
      });
    } else if (isTablet) {
      carouselContent.style.minHeight = "320px";
      dataFeedbacks.forEach((item) => {
        item.style.display = "none";
      });
    } else {
      carouselContent.style.minHeight = "300px";
      dataFeedbacks.forEach((item) => {
        item.style.display = "block";
      });
    }
  }
}

// navegação automática (opcional)
function startAutoSlide() {
  setInterval(() => {
    goToNextSlide();
  }, 5000);
}

function goToPrevSlide() {
  console.log(`a navegar para slide anterior (actual: ${currentIndex})`);
  updateCarouselState(currentIndex - 1);
}

function goToNextSlide() {
  console.log(`a navegar para slide seguinte (actual: ${currentIndex})`);
  updateCarouselState(currentIndex + 1);
}

function goToSlide(index) {
  console.log(`a navegar directamente para slide ${index} (actual: ${currentIndex})`);
  updateCarouselState(index);
}

document.addEventListener("DOMContentLoaded", () => {
  console.log("dom carregado, a inicializar carrossel...");

  convertFeedbacks();

  document.addEventListener("keydown", (event) => {
    if (event.key === "ArrowLeft") {
      console.log("tecla esquerda pressionada");
      goToPrevSlide();
    } else if (event.key === "ArrowRight") {
      console.log("tecla direita pressionada");
      goToNextSlide();
    }
  });

  window.addEventListener("resize", checkResponsiveness);
});

// submissao do formulário de feedback
document.addEventListener("DOMContentLoaded", function() {
  const feedbackForm = document.getElementById("feedbackForm");

  if (feedbackForm) {
    feedbackForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const comentario = normalizarTexto(document.querySelector('textarea[name="comments"]').value);
      const recommend = document.querySelector('input[name="recommend"]:checked')?.value === "yes" ? "1": "0";
      const classificacao = document.querySelector('input[name="rating"]:checked')?.value || "5";
      const id_categoria = document.querySelector('select[name="purchase"]').value;

      const id_cliente = document.getElementById("userId")?.value;
      if (!id_cliente) {
        console.log("id do cliente não encontrado");
        alert("tem de estar autenticado para enviar um comentário!");
        return;
      } else {
        console.log("id:" + id_cliente);
        console.log("comentário: " + comentario + "; recomendação: " + recommend + "; classificação: " + classificacao + "; id categoria: " + id_categoria);
      }

      if (!comentario || !id_categoria || !classificacao) {
        alert("preencha todos os campos.");
        return;
      }

      const data = {
        id_cliente: id_cliente,
        id_categoria: id_categoria,
        comentario: comentario,
        classificacao: classificacao,
        data_review: new Date().toISOString().split("T")[0],
        recommend: recommend,
      };

      try {
        const response = await fetch(
          "../client/productReview.php",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
          }
        );

        const result = await response.json();
        alert(result.success || result.message || "feedback enviado!");

        convertFeedbacks();

      } catch (err) {
        console.error("erro ao enviar feedback:", err);
        alert("erro ao enviar feedback.");
      }
    });
  }
});

/*
TODO:
- Connect to DB
- Admin check model with decal
- Limit image type, size and dimensions 
*/

// check for remove

const base_url = "/~ptaw-2025-gr4";

import * as THREE from "https://unpkg.com/three@0.174.0/build/three.module.js";

import WebGL from "https://unpkg.com/three@0.174.0/examples/jsm/capabilities/WebGL.js"; // import to see if device/browser supports WebGl 2

import { GLTFLoader } from "https://unpkg.com/three@0.174.0/examples/jsm/loaders/GLTFLoader.js"; // import own 3D models

import { OrbitControls } from "https://unpkg.com/three@0.174.0/examples/jsm/controls/OrbitControls.js"; // import to rotate the GLTF model using mouse click and drag

import { DecalGeometry } from "https://unpkg.com/three@0.174.0/examples/jsm/geometries/DecalGeometry.js"; // import to place decal on top of model

import { GLTFExporter } from "https://unpkg.com/three@0.174.0/examples/jsm/exporters/GLTFExporter.js"; // import to export the scene

let loadingText,
  logo,
  decalPlaced = false;
let canvas, scene, camera, renderer, controls, light;
let loader, model, box, center, size;
let mouse, raycaster, helper, customDecal, decal, decalTexture, decalMaterial;

document.querySelectorAll(".thumbnail").forEach((thumbnail) => {
  thumbnail.addEventListener("click", function () {
    document.getElementById("bigImage").src = this.src;

    // Remove 'active' class from all thumbnails
    document
      .querySelectorAll(".thumbnail")
      .forEach((img) => img.classList.remove("active"));

    // Add 'active' class to the clicked thumbnail
    this.classList.add("active");
  });
});

// Define sizes dynamically
const sizes = ["S", "M", "L", "XL", "2XL"];

// Generate size buttons dynamically
const sizeContainer = document.getElementById("idSizeOptions");
sizes.forEach((size) => {
  const btn = document.createElement("button");
  btn.classList.add("sizeBtn");
  btn.textContent = size;

  // Set click event immediately
  btn.onclick = function () {
    document
      .querySelectorAll(".sizeBtn")
      .forEach((sizeBtn) => sizeBtn.classList.remove("activeSize"));
    this.classList.add("activeSize");
  };

  sizeContainer.appendChild(btn);
});
/*
// Define colors dynamically
const colors = ["white", "black", "blue", "red"];

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
});*/

document.getElementById("openModal").addEventListener("click", function () {
  document.getElementById("modal3D").style.display = "flex";
  document.getElementById("modalImage").style.display = "flex";
  document.body.classList.add("no-scroll");
});

document.getElementById("closeModal").addEventListener("click", function () {
  document.getElementById("modal3D").style.display = "none";
  document.getElementById("modalImage").style.display = "none";
  document.body.classList.remove("no-scroll");
});

document.addEventListener("DOMContentLoaded", function () {
  const label = document.getElementById("uploadImage");
  const fileInput = label.querySelector("input[type='file']");

  fileInput.addEventListener("change", function (event) {
    if (event.target.files && event.target.files[0]) {
      const reader = new FileReader();
      reader.onload = function (e) {
        reader.src = URL.createObjectURL(event.target.files[0]); // set src to blob url

        logo = reader.src;

        label.innerHTML = `<img id="chosenImg" class="chosenImg" src="${e.target.result}" alt="Uploaded Image" style="max-width: 50%; height: 50%;">`;

        checkSupport();
      };

      document.getElementById("warningsId").innerText =
        "Double Click to place decal!";

      reader.readAsDataURL(event.target.files[0]);
      removeClass("btnReplaceImage", "hideButton");
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const fileInput = document.getElementById("inputReplaceImage");

  fileInput.addEventListener("change", function (event) {
    if (event.target.files && event.target.files[0]) {
      const reader = new FileReader();

      reader.onload = function (e) {
        logo = e.target.result;

        document.getElementById("chosenImg").src = logo;

        checkSupport();
      };

      reader.readAsDataURL(event.target.files[0]);

      removeDecal();
    }
  });
});

function removeClass(id, className) {
  let element = document.getElementById(id);
  element.classList.remove(className);
}

function addClass(id, className) {
  let element = document.getElementById(id);
  element.classList.add(className);
}

function checkSupport() {
  if (WebGL.isWebGL2Available()) {
    initThreeJS();
  } else {
    const webGLError = WebGL.getWebGL2ErrorMessage();
    //document.getElementById('modal3D').appendChild(warning);

    loadingText = document.getElementById("loading");
    loadingText.style.display = webGLError;
  }
}

function initThreeJS() {
  canvas = document.getElementById("modelCanvas");
  loadingText = document.getElementById("loading");

  // Scene
  scene = new THREE.Scene();

  // Camera
  camera = new THREE.PerspectiveCamera(
    50,
    canvas.clientWidth / canvas.clientHeight,
    0.1,
    2000
  );

  // Renderer
  renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
  renderer.setSize(canvas.clientWidth, canvas.clientHeight);
  renderer.setPixelRatio(canvas.devicePixelRatio);

  // Adding OrbitControls
  controls = new OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true;
  controls.dampingFactor = 0.05;
  controls.rotateSpeed = 0.5;
  controls.zoomSpeed = 1.2;
  controls.minDistance = 1;
  controls.maxDistance = 1.5;
  controls.enablePan = true;

  // Light
  light = new THREE.AmbientLight(0xffffff, 1);
  scene.add(light);

  // Adding own model to the scene
  loader = new GLTFLoader();

  loader.load(
    "../public/modelos3D/3D_Shirt.glb",
    function (gltf) {
      model = gltf.scene;

      scene.add(model);

      // Center model
      box = new THREE.Box3().setFromObject(model);
      center = box.getCenter(new THREE.Vector3());

      // Place the model in the center
      model.position.sub(center);

      // Move camera to fit model
      size = box.getSize(new THREE.Vector3()).length();
      camera.position.set(0, size * 0.5, size * 1.5);
      camera.lookAt(model.position);

      addCustomDecal();
    },
    undefined,
    function (error) {
      alert(`Error loading the model! \n Please try again. \n Error: ${error}`);
    }
  );

  // Hide loading text when model is ready
  loadingText.style.display = "none";

  animate();

  // Resize Handling
  canvas.addEventListener("resize", () => {
    camera.aspect = canvas.clientWidth / canvas.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
  });
}

// Animate the model
function animate() {
  requestAnimationFrame(animate);
  controls.update();
  renderer.render(scene, camera);
}

// Add decal to Model
function addCustomDecal() {
  // Decal
  mouse = new THREE.Vector2();
  raycaster = new THREE.Raycaster();
  helper = new THREE.Object3D();

  // Load the custom decal
  customDecal = new THREE.TextureLoader();
  decalTexture = customDecal.load(logo);

  document.addEventListener("dblclick", onClick);
}

function onClick(event) {
  event.preventDefault();

  mouse.x = (event.clientX / canvas.width) * 2 - 1;
  mouse.y = -(event.clientY / canvas.height) * 2 + 1;

  raycaster.setFromCamera(mouse, camera);

  let intersects = raycaster.intersectObject(model, true);

  if (intersects.length > 0) {
    let intersectedMesh = intersects[0].object;
    let position = intersects[0].point;
    let normal = intersects[0].face.normal.clone();
    normal.transformDirection(intersectedMesh.matrixWorld);
    normal.add(position);
    helper.position.copy(position);
    helper.lookAt(normal);

    let size = new THREE.Vector3(0.3, 0.3, 0.3);
    let decalGeometry = new DecalGeometry(
      intersectedMesh,
      position,
      helper.rotation,
      size
    );
    decalMaterial = new THREE.MeshStandardMaterial({
      map: decalTexture,
      transparent: true,
      depthTest: true,
      depthWrite: false,
      polygonOffset: true,
      polygonOffsetFactor: -4,
    });

    if (!decalPlaced) {
      decal = new THREE.Mesh(decalGeometry, decalMaterial);
      scene.add(decal);

      decalPlaced = true;

      document.getElementById("warningsId").innerText =
        "Limit to only one decal!";

      removeClass("rmvDecal", "disableButton");
      removeClass("btnAdd", "disableButton");
      document.removeEventListener("dblclick", onClick);

      addToCart();

      document.querySelectorAll(".removeDecal").forEach((button) => {
        button.addEventListener("click", function () {
          removeDecal();
        });
      });
    }
  }
}

function removeDecal() {
  scene.remove(decal);
  decalPlaced = false;

  document.getElementById("warningsId").innerText =
    "Double Click to place decal!";
  document.addEventListener("dblclick", onClick);
  addClass("rmvDecal", "disableButton");
  addClass("btnAdd", "disableButton");
}

function addToCart() {
  document.querySelectorAll(".buttonAdd").forEach((button) => {
    button.addEventListener("click", function () {
      const gltfExporter = new GLTFExporter();

      gltfExporter.parse(
        scene,
        function (result) {
          if (result instanceof ArrayBuffer) {
            saveArrayBuffer(result, "scene.glb");
          } else {
            const output = JSON.stringify(result, null, 2);
            saveString(output, "scene.gltf");
          }
        },
        function (error) {
          console.log("An error happened during parsing", error);
        }
      );
    });
  });
}

const link = document.createElement("a");
link.style.display = "none";
document.body.appendChild(link); // Firefox workaround, see #6594

function save(blob, filename) {
  link.href = URL.createObjectURL(blob);
  link.download = filename;
  link.click();

  // URL.revokeObjectURL( url ); breaks Firefox...
}

function saveString(text, filename) {
  save(new Blob([text], { type: "text/plain" }), filename);
}

function saveArrayBuffer(buffer, filename) {
  save(new Blob([buffer], { type: "application/octet-stream" }), filename);
}

function getUserByEmail(userEmail){
  return fetch(`$baseUrl/user/getUserByEmail.php?email=${userEmail}`)
}
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
    const response = await fetch(`../client/productReview.php`, {
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
    return [];
  }
}

async function convertFeedbacks() {
  try {
    feedbacks = await getFeedbacks();

    const container = document.querySelector("#carouselItems");
    const indicatorContainer = document.querySelector("#indicator");
    const carouselContainer = document.querySelector('.feedback-carousel-container');

    if (!container) {
      return;
    }

    let createdIndicator = false;
    if (!indicatorContainer) {

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

    const allItems = document.querySelectorAll(".carousel-item-container");

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
    const carouselItems = document.querySelector("#carouselItems");
    if (carouselItems) {
      carouselItems.innerHTML = "<p>erro ao carregar feedbacks.</p>";
    }
  }
}
function updateCarouselState(index) {
  const items = document.querySelectorAll(".carousel-item-container");
  const total = items.length;

  if (total === 0) return;

  if (index < 0) index = total - 1;
  if (index >= total) index = 0;

  items.forEach((item, i) => {

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

    dots.forEach((dot, i) => {
      if (i === index) {
        dot.classList.add("active-dot");
        dot.style.backgroundColor = "#333";
      } else {
        dot.classList.remove("active-dot");
        dot.style.backgroundColor = "#ccc";
      }
    });
  } 

  currentIndex = index;
}

// criar botões de navegação se necessário
function createNavigationButtonsIfNeeded(carouselContainer) {
  if (!carouselContainer) {
    carouselContainer = document.querySelector('.carousel-container') ||
      document.querySelector('#carouselContainer') ||
      document.querySelector('.feedback-carousel-container') ||
      document.querySelector('.carousel');

  }

  const existingPrevButton = document.querySelector('.carousel-prev, .prev-button, #prevBtn, [data-action="prev"], #prevButton');
  const existingNextButton = document.querySelector('.carousel-next, .next-button, #nextBtn, [data-action="next"], #nextButton');


  if (existingPrevButton && existingNextButton) {

    existingPrevButton.addEventListener("click", () => {
      goToPrevSlide();
    });

    existingNextButton.addEventListener("click", () => {
      goToNextSlide();
    });

    return;
  }

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
    goToPrevSlide();
  });

  nextButton.addEventListener('click', () => {
    goToNextSlide();
  });

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
  updateCarouselState(currentIndex - 1);
}

function goToNextSlide() {
  updateCarouselState(currentIndex + 1);
}

function goToSlide(index) {
  updateCarouselState(index);
}

document.addEventListener("DOMContentLoaded", () => {
  convertFeedbacks();

  document.addEventListener("keydown", (event) => {
    if (event.key === "ArrowLeft") {
      goToPrevSlide();
    } else if (event.key === "ArrowRight") {
      goToNextSlide();
    }
  });

  window.addEventListener("resize", checkResponsiveness);
});

document
  .getElementById("feedbackForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    // armazenar dados do formulario
    const comentario = document.querySelector('textarea[name="comments"]').value;
    const recommend =
      document.querySelector('input[name="recommend"]:checked')?.value === "yes" ? "2": "1";
    const classificacao = document.querySelector('input[name="rating"]:checked')?.value || "5";
    const id_categoria = document.querySelector('select[name="purchase"]').value;


    const id_cliente = document.getElementById("userId").value;
    if(!id_cliente){
      console.log("nao existe ID");
      alert("É necessário estar logado para enviar feedback.");
      return; 
    }else{
      console.log("ID:" +id_cliente);
    }

    // validar campos "obrigatorios"
    if (!comentario || !id_categoria || !classificacao) {
      alert("Preencha todos os campos.");
      return;
    }

    
    // construcao de array dos dados
    const data = {
      id_cliente: id_cliente,
      id_categoria: id_categoria,
      comentario: comentario,
      classificacao: classificacao,
      data_review: new Date().toISOString().split("T")[0],
      recommend: recommend,
    };
    if(recommend == 1){
      alert("Lamentamos que não tenha gostado do produto. Por favor, deixe um comentário para que possamos melhorar.");
      data[5] = "0";
    }



    // enviar dados diretamente para a API
    try {
      const response = await fetch(
        `../restapi/PrintGoAPI.php/insertFeedback`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );
      
      if (!response.ok) {
        const errorText = await response.text();
        throw new Error(`Erro HTTP: ${response.status}`);
      }
      
      const result = await response.json();
      console.log("Resposta da API:", result);
      
      if (result.success) {
        alert("Feedback enviado com sucesso!");
        document.getElementById("feedbackForm").reset();
      } else {
        alert(result.message || "Erro ao enviar feedback.");
      }
      
    } catch (err) {
      alert("Erro ao enviar feedback. Por favor, tente novamente.");
    }
  });





  

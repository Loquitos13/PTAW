<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catalog</title>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="css/produtos.css">

  <?php include 'includes/carrinho.php'; ?>
  <?php include 'includes/filtros.php'; ?>
</head>

<style>
  body {
    margin: 0;
    padding: 0;
    overflow: auto;
    align-items: center;
  }

  #menu-mobile {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 80%;
    height: 100%;
    background-color: white;
    transform: translateX(-10%);
    transition: transform 2000ms ease;
    overflow: hidden;
    z-index: 999;
    flex-direction: column-reverse;
    margin-top: 0;
    justify-content: flex-end;
    gap: 20px;
    align-items: center;
    padding-top: 100px;
  }

  .fixed-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: white;
    z-index: 999;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  #menu-mobile.open {
    display: flex;
    transform: translateX(0);
  }

  #menu-mobile a {
    color: #4F46E5;
    text-decoration: none;
    font-size: 1.5rem;
    margin: 15px 0;
  }

  #menu-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1000;
    font-size: 2rem;
    background: none;
    border: none;
    color: black;
    cursor: pointer;
  }

  #logo-header-mobile {
    display: none;
    background: none;
    border: none;
  }


  /* Ajustes para telas menores */
  @media (max-width: 1200px) {
    body {
      width: 100%;
      padding-top: 70px;
    }

    .header-desktop {
      display: none;
    }

    #a-logo-header-mobile {
      display: flex;
      width: max-content;
      justify-content: center;
      padding-top: 20px;
      padding-bottom: 20px;
      width: 100%;

    }

    #logo-header-mobile {
      display: block;
      width: 100px;
      height: auto;
      margin: 0 auto;
    }

    #menu-toggle {
      display: block;
      top: 0;
    }

    #menu-mobile {
      position: fixed;
      height: 100%;
      overflow: hidden;
    }

    #menu-mobile li {
      list-style: none;
    }

    #menu-mobile .social {
      display: flex;
      height: 100%;
      width: 100%;
      align-items: center;
      align-self: flex-end;
      justify-content: center;
    }

    .container1 {
      display: none;
    }

    .left,
    .right {
      width: 100%;
      justify-content: center;
    }

    .left a {
      width: auto;
    }

    #containerHeroe {
      flex-direction: column;
      height: auto;
      padding: 20px 20px;
      width: 100%;
    }

    .esqHeroe,
    .dirHeroe {
      width: 100%;
      text-align: center;
    }

    .dirHeroe img {
      position: static;
      height: auto;
      width: 80%;
    }

    #prodDestaques {
      flex-direction: column;
      align-items: center;
      justify-content: center;

    }

    .containerDestaques {
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 100%;
      gap: 20px;
    }

    .feedback-carousel-container {
      max-width: 90%;
      max-height: 400px;
    }

    #featureSection {
      padding: 5vh;
    }

    #containerFeatures {
      flex-direction: column;
    }

    .featureBox p {
      width: 90%;
    }

    #cta h3 {
      text-align: center;
      font-size: 100%;
    }

    #cta p {
      text-align: center;
      font-size: 90%;
    }

    footer {
      text-align: center;
    }

    #containerFooter {
      flex-direction: column;
      align-items: center;
      gap: 20px;
    }
  }
</style>

<body>
  <!-- Menu Mobile -->
  <?php include 'includes/header-mobile.php'; ?>

  <!-- Menu Desktop -->
  <?php include 'includes/header-desktop.php'; ?>

  <div id="containerTitulo">
    <div class="esqTitulo">
      <h1>Our Products</h1>
      <p>Choose from our wide range of customizable products</p>
    </div>
  </div>

  <div class="containerPrincipal">
    <hr>
    <!-- Filtros para desktop -->
    <div class="filtros">

      <!-- Categorias -->

      <div>
        <h6>Categories</h6>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="Apparel" id="defaultCategory1">
          <label class="form-check-label" for="labelCategory1">
            Apparel
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="Accessories" id="defaultCategory2">
          <label class="form-check-label" for="labelCategory2">
            Accessories
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="Home & Living" id="defaultCategory3">
          <label class="form-check-label" for="labelCategory3">
            Home & Living
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="Stationery" id="defaultCategory4">
          <label class="form-check-label" for="labelCategory4">
            Stationery
          </label>
        </div>
      </div>
      <hr>

      <div class="range-container">
        <h6 class="mb-3 fw-semibold">Price Range</h6>

        <!-- Slider de intervalo duplo -->
        <div class="double-range-slider">
          <div class="slider-track">
            <div class="slider-track-highlight" id="track-highlight"></div>
          </div>

          <div class="slider-thumb" id="thumb-min"></div>
          <div class="slider-thumb" id="thumb-max"></div>

          <input type="range" class="range-input" id="range-min" min="0" max="100" value="0">
          <input type="range" class="range-input" id="range-max" min="0" max="100" value="100">
        </div>

        <div class="price-labels mt-2">
          <span class="price-label">€<span id="value-min">30</span></span>
          <span class="price-label">€<span id="value-max">70</span></span>
        </div>
      </div>
      <hr>

      <!-- Cores da aba filtros -->

      <div class="color-selector">
        <h6>Colors</h6>
        <div id="idColorOptions" class="colorOptions d-flex gap-2">
          <input type="radio" class="btn-check" name="color" id="color-red" value="Vermelho" autocomplete="off">
          <label class="btnColor rounded-circle p-2" for="color-red"
            style="background-color: red; border: 2px solid #ccc;"></label>

          <input type="radio" class="btn-check" name="color" id="color-blue" value="Azul" autocomplete="off">
          <label class="btnColor rounded-circle p-2" for="color-blue"
            style="background-color: blue; border: 2px solid #ccc;"></label>

          <input type="radio" class="btn-check" name="color" id="color-green" value="Verde" autocomplete="off">
          <label class="btnColor rounded-circle p-2" for="color-green"
            style="background-color: green; border: 2px solid #ccc;"></label>

          <input type="radio" class="btn-check" name="color" id="color-orange" value="Laranja" autocomplete="off">
          <label class="btnColor rounded-circle p-2" for="color-orange"
            style="background-color: orange; border: 2px solid #ccc;"></label>

          <input type="radio" class="btn-check" name="color" id="color-purple" value="Roxo" autocomplete="off">
          <label class="btnColor rounded-circle p-2" for="color-purple"
            style="background-color: purple; border: 2px solid #ccc;"></label>

          <input type="radio" class="btn-check" name="color" id="color-Preto" value="Preto" autocomplete="off">
          <label class="btnColor rounded-circle p-2" for="color-Preto"
            style="background-color: black; border: 2px solid #ccc;"></label>

          <input type="radio" class="btn-check" name="color" id="color-Branco" value="Branco" autocomplete="off">
          <label class="btnColor rounded-circle p-2" for="color-Branco"
            style="background-color: white; border: 2px solid #ccc;"></label>
        </div>
      </div>
      <hr>

      <!-- Tamanhos -->
      <!-- quando for para pegar o valor selecionado udar um querySelector .btn-check[name="size-desktop"] -->
      <!-- Exemplo na linha 209 do profuto.js -->

      <div>
        <h6>Size</h6>

        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
          <div class="btn-group me-2" role="group" aria-label="First group">
            <input type="radio" class="btn-check" name="size-desktop" value="S" id="btnradio1-desktop"
              autocomplete="off">
            <label class="btn btn-outline-primary" for="btnradio1-desktop">S</label>
          </div>
          <div class="btn-group me-2" role="group" aria-label="Second group">
            <input type="radio" class="btn-check" name="size-desktop" value="M" id="btnradio2-desktop"
              autocomplete="off">
            <label class="btn btn-outline-primary" for="btnradio2-desktop">M</label>
          </div>
          <div class="btn-group me-2" role="group" aria-label="Third group">
            <input type="radio" class="btn-check" name="size-desktop" value="L" id="btnradio3-desktop"
              autocomplete="off">
            <label class="btn btn-outline-primary" for="btnradio3-desktop">L</label>
          </div>
          <div class="btn-group me-2" role="group" aria-label="Fourth group">
            <input type="radio" class="btn-check" name="size-desktop" value="XL" id="btnradio4-desktop"
              autocomplete="off">
            <label class="btn btn-outline-primary" for="btnradio4-desktop">XL</label>
          </div>
          <div class="btn-group me-2" role="group" aria-label="Fifth group">
            <input type="radio" class="btn-check" name="size-desktop" value="2XL" id="btnradio5-desktop"
              autocomplete="off">
            <label class="btn btn-outline-primary" for="btnradio5-desktop">2XL</label>
          </div>
        </div>
      </div>
      <hr>
      <!-- Botão de Aplicar -->
      <button id="apply-filters-desktop" class="btn btn-primary">Apply Filters</button>
    </div>

    <!-- Produtos -->
    <div id="produtos">
      <div class="containerOrdenar">
        <button data-bs-toggle="offcanvas" data-bs-target="#filtros_mob" aria-controls="filtros_mob"
          id="filters-toggle">Filters</button>
        <select class="form-select" style="width: auto;" aria-label="Sort options">
          <option selected>Popular (Best Seller)</option>
          <option value="1">Newest</option>
          <option value="2">Relevant</option>
          <option value="3">Sought-After</option>
        </select>
      </div>

      <!-- Todos os produtos -->
      <div class="container py-4">
        <!-- Todos os produtos serão exebidos aqui -->
        <div class="row" id="produtos-container">

        </div>

      </div>
    </div>

  </div>
  <div class="shadow p-3 mb-5 bg-body rounded containerPrincipalinho ">
    <h2>Need Bulk Orders?</h2>
    <p>Get special pricing and dedicated support for large quantity orders</p>
    <button type="button" class="btn btn-primary mx-auto " style="color: white; background-color: #4F46E5;">Contact
      Sales Team</button>
  </div>
  <!-- Footer -->
  <?php include 'includes/footer.php'; ?>

  <script src="js/produtos.js"></script>
</body>

</html>
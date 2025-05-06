<p?php /* session_start(); if (!isset($_SESSION['admin_email'])) { header("location: /~ptaw-2025-gr4/src/SignIn.html");
  } */ ?>


  <!DOCTYPE html>
  <html lang="pt">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print & Go</title>
  <link rel="stylesheet" href="css/Dashboard.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="js/Dashboard.js">

  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>


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
        display: none !important;
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

      #containerFooter {
        flex-direction: column;
        align-items: center;
        gap: 20px;
      }
    }
  </style>


  </head>

  <body style="background-color: #E5E7EB;">
    <div class="d-flex">

      <!-- Menu lateral -->
      <?php include '../includes/header-desktop-admin.php'; ?>
      <!-- Menu mobile -->
      <?php /*include '../includes/header-mobile-admin.php'; */ ?>


      <!-- Conteudo principal -->
      <div class="flex-grow-1 p-4" id="main-content">

        <?php /*echo ($_SESSION['admin_email']); */ ?>

        <!--
      <a href="/PTAW/src/logout.php" class="nav-link">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5985E1">
          <path
            d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" />
        </svg>
      </a> -->

        <!-- Header aqui -->

        <!-- experimentar depois colocar py-4 -->
        <div class="container-fluid">
          <div class="row">
            <!-- div para deixar cada um dos cards responsivos -->
            <div class="col-lg-3 col-md-6 mb-3">
              <!-- card de vendas totais -->
              <div class="card">
                <!-- corpo do card -->
                <div class="card-body">
                  <!-- titulo do card -->
                  <div class="container-fluid p-0">
                    <!-- cria uma linha para colocar o titulo e o valor -->
                    <div class="row">
                      <div class="col-8">
                        <p class="card-title">Total Sales</p>
                      </div>
                      <!-- per-possitivo é uma classe que vai dar cor ao valor -->
                      <div class="col-4 fw-bold per-possitivo">
                        <p>+12.5%</p>
                      </div>
                    </div>
                    <h5>24,78€</h5>
                  </div>
                  <p class="card-text">vs. $22,123 last month</p>
                </div>
              </div>
            </div>

            <!-- div para deixar cada um dos cards responsivos -->
            <div class="col-lg-3 col-md-6 mb-3">
              <!-- card de vendas totais -->
              <div class="card">
                <!-- corpo do card -->
                <div class="card-body">
                  <!-- titulo do card -->
                  <div class="container-fluid p-0">
                    <!-- cria uma linha para colocar o titulo e o valor -->
                    <div class="row">
                      <div class="col-8">
                        <p class="card-title">Total Sales</p>
                      </div>
                      <!-- per-possitivo é uma classe que vai dar cor ao valor -->
                      <div class="col-4 fw-bold per-possitivo">
                        <p>+12.5%</p>
                      </div>
                    </div>
                    <h5>24,78€</h5>
                  </div>
                  <p class="card-text">vs. $22,123 last month</p>
                </div>
              </div>
            </div>

            <!-- div para deixar cada um dos cards responsivos -->
            <div class="col-lg-3 col-md-6 mb-3">
              <!-- card de vendas totais -->
              <div class="card">
                <!-- corpo do card -->
                <div class="card-body">
                  <!-- titulo do card -->
                  <div class="container-fluid p-0">
                    <!-- cria uma linha para colocar o titulo e o valor -->
                    <div class="row">
                      <div class="col-8">
                        <p class="card-title">Total Sales</p>
                      </div>
                      <!-- per-possitivo é uma classe que vai dar cor ao valor -->
                      <div class="col-4 fw-bold per-possitivo">
                        <p>+12.5%</p>
                      </div>
                    </div>
                    <h5>24,78€</h5>
                  </div>
                  <p class="card-text">vs. $22,123 last month</p>
                </div>
              </div>
            </div>

            <!-- div para deixar cada um dos cards responsivos -->
            <div class="col-lg-3 col-md-6 mb-3">
              <!-- card de vendas totais -->
              <div class="card">
                <!-- corpo do card -->
                <div class="card-body">
                  <!-- titulo do card -->
                  <div class="container-fluid p-0">
                    <!-- cria uma linha para colocar o titulo e o valor -->
                    <div class="row">
                      <div class="col-8">
                        <p class="card-title">Total Sales</p>
                      </div>
                      <!-- per-possitivo é uma classe que vai dar cor ao valor -->
                      <div class="col-4 fw-bold per-possitivo">
                        <p>+12.5%</p>
                      </div>
                    </div>
                    <h5>24,78€</h5>
                  </div>
                  <p class="card-text">vs. $22,123 last month</p>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>



  </body>

  </html>
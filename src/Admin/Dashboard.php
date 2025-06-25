<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../SignIn.html');
    exit;
} else {

    $adminID = $_SESSION['admin_id'];

}

?>

<input type="hidden" id="adminID" value="<?php echo htmlspecialchars($adminID); ?>">


<!DOCTYPE html>
<html lang="pt">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Print & Go</title>
<link rel="stylesheet" href="css/Dashboard.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
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
    <?php include '../includes/header-mobile-admin.php'; ?>


    <!-- Conteudo principal -->
    <div class="flex-grow-1 p-4" id="main-content">

      <!-- Header do Dashboard -->
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap py-3">
              <!-- Mensagem de boas vindas -->
              <div class="mb-2 mb-lg-0">
                <h1 class="mb-1">Dashboard</h1>
                <p class="mb-0">Welcome back, Admin!</p>
              </div>
              <!-- Admin info -->
              <div class="d-flex align-items-center">
                <img src="../../imagens/admin.png" alt="" id="img-admin"
                  style="width:40px; height:40px; object-fit:cover; border-radius:50%;">
                <h6 id="admin_nome" class="mb-0 ms-3">Nome Admin</h6>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- experimentar depois colocar py-4 -->
      <div class="container-fluid">
        <div class="row">
          <!-- div para deixar cada um dos cards responsivos -->
          <div class="col-lg-3 col-md-6 mb-3">
            <!-- card de vendas totais -->
            <div class="card">
              <!-- corpo do card -->
              <div class="card-body">
                <div class="container-fluid p-0">
                  <!-- cria uma linha para colocar o titulo e a percentagem -->
                  <div class="row">
                    <div class="col-7">
                      <p class="card-title">Total Sales</p>
                    </div>
                    <!-- per-possitivo é uma classe que vai dar cor ao valor -->
                    <div class="col-5 fw-bold per-possitivo">
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
            <!-- card de numero de vendas -->
            <div class="card">
              <!-- corpo do card -->
              <div class="card-body">
                <div class="container-fluid p-0">
                  <!-- cria uma linha para colocar o titulo e a percentagem -->
                  <div class="row">
                    <div class="col-7">
                      <p class="card-title">Orders</p>
                    </div>
                    <!-- per-possitivo é uma classe que vai dar cor ao valor -->
                    <div class="col-5 fw-bold per-possitivo">
                      <p>+8.2%</p>
                    </div>
                  </div>
                  <h5>384</h5>
                </div>
                <p class="card-text">vs. 355 last month</p>
              </div>
            </div>
          </div>

          <!-- div para deixar cada um dos cards responsivos -->
          <div class="col-lg-3 col-md-6 mb-3">
            <!-- card de numero de clientes -->
            <div class="card">
              <!-- corpo do card -->
              <div class="card-body">
                <div class="container-fluid p-0">
                  <!-- cria uma linha para colocar o titulo e a percentagem -->
                  <div class="row">
                    <div class="col-7">
                      <p class="card-title">Customers</p>
                    </div>
                    <!-- per-possitivo é uma classe que vai dar cor ao valor -->
                    <div class="col-5 fw-bold per-possitivo">
                      <p>+23.5%</p>
                    </div>
                  </div>
                  <h5>1,842</h5>
                </div>
                <p class="card-text">vs. 1,492 last month</p>
              </div>
            </div>
          </div>

          <!-- div para deixar cada um dos cards responsivos -->
          <div class="col-lg-3 col-md-6 mb-3">
            <!-- card de preço medio de encomenda -->
            <div class="card">
              <!-- corpo do card -->
              <div class="card-body">
                <div class="container-fluid p-0">
                  <!-- cria uma linha para colocar o titulo e a percentagem -->
                  <div class="row">
                    <div class="col-7">
                      <p class="card-title">Avg. Order Value</p>
                    </div>
                    <!-- per-possitivo é uma classe que vai dar cor ao valor -->
                    <div class="col-5 fw-bold per-negativo">
                      <p>-2.3%</p>
                    </div>
                  </div>
                  <h5>64.50€</h5>
                </div>
                <p class="card-text">vs. 66.02€ last month</p>
              </div>
            </div>
          </div>

        </div>

      </div>

      <div class="container-fluid">
        <div class="row">

          <!-- div para deixar cada um dos cards responsivos -->
          <div class="col-lg-6 col-md-12 mb-3">
            <!-- card ordens recentes -->
            <div class="card">
              <!-- corpo do card -->
              <div class="card-body">
                <div class="container-fluid p-0">
                  <h5 class="card-title">Recent Orders</h5>
                  <hr>

                  <!-- Tabela de ordens recentes com scroll -->
                  <!-- A tabela tem um max-height de 180px e overflow-y: auto para permitir o scroll quando o conteúdo excede essa altura -->
                  <div style="max-height: 180px; overflow-y: auto;">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Order ID</th>
                          <th scope="col">Customer</th>
                          <th scope="col">Status</th>
                          <th scope="col">Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">7842</th>
                          <td>Sarah Johnson</td>
                          <td class="status-comleted">Completed</td>
                          <td>128.50€</td>
                        </tr>
                        <tr>
                          <th scope="row">7841</th>
                          <td>Michael Chen</td>
                          <td class="status-pending ">Pending</td>
                          <td>85.20€</td>
                        </tr>
                        <tr>
                          <th scope="row">7840</th>
                          <td>Emily Wilson</td>
                          <td class="status-processing">Processing</td>
                          <td>242.00€</td>
                        </tr>
                        <tr>
                          <th scope="row">7840</th>
                          <td>Emily Wilson</td>
                          <td class="status-processing">Processing</td>
                          <td>242.00€</td>
                        </tr>
                        <tr>
                          <th scope="row">7840</th>
                          <td>Emily Wilson</td>
                          <td class="status-processing">Processing</td>
                          <td>242.00€</td>
                        </tr>
                        <tr>
                          <th scope="row">7840</th>
                          <td>Emily Wilson</td>
                          <td class="status-processing">Processing</td>
                          <td>242.00€</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- div para deixar cada um dos cards responsivos -->
          <div class="col-lg-6 col-md-12 mb-3">
            <!-- card dos produtos mais vendidos -->
            <div class="card" style="min-height: 271px;">
              <!-- corpo do card -->
              <div class="card-body">
                <div class="container-fluid p-0">
                  <h5 class="card-title">Best Selling Products</h5>
                  <hr>
                  <!-- Tabela de produtos mais vendidos com scroll -->
                  <div style="max-height: 180px; overflow-y: auto; overflow-x: hidden;">

                    <div class="row">
                      <!-- imagem do produto centrada ao meio -->
                      <div class="col-3 align-items-center justify-content-center d-flex">
                        <img src="../../imagens/produtos varios hero.png" alt="Product Image" class="" width="auto"
                          height="55px">
                      </div>
                      <!-- Nome do produto e numero de vendas -->
                      <div class="col-6">
                        <p class="fw-bold">Classic T-Shirt</p>
                        <p class="num-sales">1,242 sales</p>
                      </div>
                      <!-- Preço do produto e percentagem de vendas -->
                      <div class="col-3">
                        <p class="fw-bold">24.99€</p>
                        <p class="num-sales per-possitivo">+12%</p>
                      </div>
                    </div>

                    <div class="row">
                      <!-- imagem do produto centrada ao meio -->
                      <div class="col-3 align-items-center justify-content-center d-flex">
                        <img src="../../imagens/produtos varios hero.png" alt="Product Image" width="auto"
                          height="55px">
                      </div>
                      <!-- Nome do produto e numero de vendas -->
                      <div class="col-6">
                        <p class="fw-bold">Classic T-Shirt</p>
                        <p class="num-sales">1,242 sales</p>
                      </div>
                      <!-- Preço do produto e percentagem de vendas -->
                      <div class="col-3">
                        <p class="fw-bold">24.99€</p>
                        <p class="num-sales per-possitivo">+12%</p>
                      </div>
                    </div>

                    <div class="row">
                      <!-- imagem do produto centrada ao meio -->
                      <div class="col-3 align-items-center justify-content-center d-flex">
                        <img src="../../imagens/produtos varios hero.png" alt="Product Image" width="auto"
                          height="55px">
                      </div>
                      <!-- Nome do produto e numero de vendas -->
                      <div class="col-6">
                        <p class="fw-bold">Classic T-Shirt</p>
                        <p class="num-sales">1,242 sales</p>
                      </div>
                      <!-- Preço do produto e percentagem de vendas -->
                      <div class="col-3">
                        <p class="fw-bold">24.99€</p>
                        <p class="num-sales per-possitivo">+12%</p>
                      </div>
                    </div>

                    <div class="row">
                      <!-- imagem do produto centrada ao meio -->
                      <div class="col-3 align-items-center justify-content-center d-flex">
                        <img src="../../imagens/produtos varios hero.png" alt="Product Image" width="auto"
                          height="55px">
                      </div>
                      <!-- Nome do produto e numero de vendas -->
                      <div class="col-6">
                        <p class="fw-bold">Classic T-Shirt</p>
                        <p class="num-sales">1,242 sales</p>
                      </div>
                      <!-- Preço do produto e percentagem de vendas -->
                      <div class="col-3">
                        <p class="fw-bold">24.99€</p>
                        <p class="num-sales per-possitivo">+12%</p>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
  </div>

</body>

<script src="js/Dashboard.js"></script>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Order #ORD-7842</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/Orders.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <script src="js/Orders.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</head>

<style>
  body {
    margin: 0;
    padding: 0;
    overflow: auto;
    align-items: center;
  }
  .modal {
  display: none;
  position: fixed;
  z-index: 9999;
  padding-top: 60px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.5);
}

.modal-content {
  background-color: #fff;
  margin: auto;
  padding: 20px;
  border-radius: 8px;
  width: 80%;
  max-width: 700px;
  position: relative;
}

.close-button {
  color: #aaa;
  position: absolute;
  top: 10px;
  right: 20px;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close-button:hover {
  color: #000;
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

<body style="background-color: #E5E7EB;">
  <div class="d-flex">
    <!-- Menu lateral -->
    <?php include '../includes/header-desktop-admin.php'; ?>
    <!-- Menu mobile -->
    <?php include '../includes/header-mobile-admin.php'; ?>



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

      <div class="container my-5">
    <h4>Order #ORD-</h4>
    <div class="row">
      <!-- Informação da encomenda -->
      <div class="col-md-8">
        <div class="card mb-4">
          <div class="card-header">Order Information</div>
          <div class="card-body">
            <p><strong>Status:</strong> <span class="badge bg-warning text-dark">Processing</span></p>
            <p><strong>Purchase Origin:</strong> Online store</p>
          </div>
        </div>

        <!-- Detalhes do produto -->
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between">
            <span>Order Details</span>
            <button class="btn btn-sm btn-primary" id="button-process">Process Items</button>
          </div>
          <div class="card-body">
            <p><strong>Delivery Method:</strong> CTT Expresso</p>
            <table class="table">
              <thead>
                <tr>
                  <th>Product</th><th>Size</th><th>Qty</th><th>Price</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>T-shirt<br><small>No personalization</small></td>
                  <td>L</td>
                  <td>1</td>
                  <td>€23.00</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Pagamento -->
        <div class="card">
          <div class="card-header">Payment and Costs</div>
          <div class="card-body">
            <p><strong>Subtotal:</strong> €23.00</p>
            <p><strong>Shipping:</strong> Free</p>
            <hr>
            <p><strong>Total:</strong> €23.00</p>
          </div>
        </div>
      </div>

      <!-- Informação do cliente -->
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-header">Customer</div>
          <div class="card-body">
            <p><strong>Camila</strong></p>
            <p><small>Customer since Mar 2022</small></p>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between">
            <span>Contact and Address</span>
            <button class="btn btn-sm btn-outline-secondary">✏️</button>
          </div>
          <div class="card-body">
            <p><strong>Email:</strong> bruno@example.com</p>
            <p><strong>Phone:</strong> +351 123 456 789</p>
            <p><strong>Shipping Address:</strong><br>
              123 Main St, Apt 4B<br>
              Lisbon, 1000-001<br>
              Portugal</p>
            <p><strong>Billing:</strong> Same as shipping</p>
          </div>
        </div>

        <div class="card">
          <div class="card-header">Notes</div>
          <div class="card-body">
            <p>No customer notes</p>
          </div>
        </div>
      </div>
    </div>
  </div>

    </div>

  </div>

  <div id="processModal" class="modal">
  <div class="modal-content">
    <span class="close-button">&times;</span>
    <div id="modal-body-content">A carregar...</div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('processModal');
    const closeBtn = document.querySelector('.close-button');
    const modalContent = document.getElementById('modal-body-content');

    document.getElementById('button-process').addEventListener('click', function () {
      modal.style.display = 'block';

      fetch('ProcessItems.php')
        .then(response => response.text())
        .then(data => {
          modalContent.innerHTML = data;
        })
        .catch(error => {
          modalContent.innerHTML = '<p>Erro ao carregar conteúdo.</p>';
          console.error(error);
        });
    });

    closeBtn.onclick = function () {
      modal.style.display = 'none';
    }

    window.onclick = function (event) {
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    }
  });
</script>


</body>

</html>
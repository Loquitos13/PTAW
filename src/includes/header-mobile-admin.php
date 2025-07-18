




<div class="fixed-header">
  <button id="menu-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu"
    aria-controls="mobileMenu">
    ☰
  </button>
  <a href="Dashboard.php" class="d-flex align-items-center mb-md-0 me-md-auto link-body-emphasis text-decoration-none"
    id="a-logo-header-mobile">
    <img src="<?= $base_url ?>/imagens/Print&Go.png" alt="" id="logo-header-mobile" href="../index.php">
  </a>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="d-flex flex-column gap-3">

      <a href="Dashboard.php" class="nav-link py-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" style="stroke:currentColor; stroke-width:1;"
          width="16" height="16" fill="currentColor" class="bi pe-none me-2 bi-graph-up" viewBox="0 0 16 16">
          <path fill-rule="evenodd"
            d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07" />
        </svg>
        Dashboard
      </a>
      <a href="produtosAdmin.php" class="nav-link py-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi me-2 bi-box2-fill"
          viewBox="0 0 16 16">
          <path
            d="M3.75 0a1 1 0 0 0-.8.4L.1 4.2a.5.5 0 0 0-.1.3V15a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V4.5a.5.5 0 0 0-.1-.3L13.05.4a1 1 0 0 0-.8-.4zM15 4.667V5H1v-.333L1.5 4h6V1h1v3h6z" />
        </svg>
        Products
      </a>
      <a href="orders.php" class="nav-link py-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi me-2 bi-cart-fill"
          viewBox="0 0 16 16">
          <path
            d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
        </svg>
        Orders
      </a>
      <a href="analytics.php" class="nav-link py-2">
        <svg xmlns="http://www.w3.org/2000/svg" style="stroke:currentColor; stroke-width:1;" width="30" height="30"
          fill="currentColor" class="bi pe-none me-2 bi-graph-up" viewBox="0 0 16 16">
          <path fill-rule="evenodd"
            d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07" />
        </svg>
        Analytics
      </a>
      <a href="<?= $base_url ?>/src/sobre.php" class="nav-link py-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi me-2 bi-tag-fill"
          viewBox="0 0 16 16">
          <path
            d="M2 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 6.586 1zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
        </svg>
        Discounts
      </a>
      <a href="Settings.php" class="nav-link py-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi me-2 bi-gear-fill"
          viewBox="0 0 16 16">
          <path
            d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
        </svg>
        Settings
      </a>
    </div>
  </div>
</div>
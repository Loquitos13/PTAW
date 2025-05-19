document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("#link-orders").innerHTML = `<li>
            <a href="#" class="nav-link active" aria-current="page" id="">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi me-2 bi-cart-fill" viewBox="0 0 16 16">
                    <path
                        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                </svg>
                Orders
            </a>
        </li>`;

    document.addEventListener('DOMContentLoaded', function () {
    const processButton = document.getElementById('button-process');
    processButton.addEventListener('click', function () {
      window.open('ProcessItems.php', 'ProcessPopup', 'width=600,height=600,resizable=yes,scrollbars=yes');
    });
  });
});





// função para adicionar o link ativado no menu lateral ao link Products
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("#link-products").innerHTML = `<li id="link-products">
            <a href="Products.php" class="nav-link active" aria-current="page">
                <svg xmlns="http://www.w3.org/2000/svg" style=" color: #4F46E5;" width="16" height="16" fill="currentColor"
                    class="bi me-2 bi-box2-fill" viewBox="0 0 16 16">
                    <path
                        d="M3.75 0a1 1 0 0 0-.8.4L.1 4.2a.5.5 0 0 0-.1.3V15a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V4.5a.5.5 0 0 0-.1-.3L13.05.4a1 1 0 0 0-.8-.4zM15 4.667V5H1v-.333L1.5 4h6V1h1v3h6z" />
                </svg>
                Products
            </a>
        </li>`;
});
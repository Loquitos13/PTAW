// função para adicionar o link ativado no menu lateral ao link analytics
document.addEventListener("DOMContentLoaded", function() {
    document.querySelector("#link-analytics").innerHTML = `<li id="link-analytics">
            <a href="#" class="nav-link active" aria-current="page">
                <svg xmlns="http://www.w3.org/2000/svg" style="stroke:currentColor; stroke-width:1; color: #4F46E5;" width="16"
                    height="16" fill="currentColor" class="bi pe-none me-2 bi-graph-up" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07" />
                </svg>
                Analytics
            </a>
        </li>`;
});
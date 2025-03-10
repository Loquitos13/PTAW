document.addEventListener('DOMContentLoaded', function () {
    // Obter elementos
    const rangeMin = document.getElementById('range-min');
    const rangeMax = document.getElementById('range-max');
    const thumbMin = document.getElementById('thumb-min');
    const thumbMax = document.getElementById('thumb-max');
    const trackHighlight = document.getElementById('track-highlight');
    const valueMin = document.getElementById('value-min');
    const valueMax = document.getElementById('value-max');

    // Função para atualizar o slider
    function updateSlider() {
        // Garantir que o valor mínimo não seja maior que o valor máximo
        if (parseInt(rangeMin.value) > parseInt(rangeMax.value)) {
            rangeMin.value = rangeMax.value;
        }

        // Calcular porcentagens
        const minPercent = (rangeMin.value / rangeMin.max) * 100;
        const maxPercent = (rangeMax.value / rangeMax.max) * 100;

        // Atualizar posição dos thumbs
        thumbMin.style.left = minPercent + '%';
        thumbMax.style.left = maxPercent + '%';

        // Atualizar a faixa destacada
        trackHighlight.style.left = minPercent + '%';
        trackHighlight.style.width = (maxPercent - minPercent) + '%';

        // Atualizar valores exibidos
        valueMin.textContent = rangeMin.value;
        valueMax.textContent = rangeMax.value;
    }

    // Adicionar event listeners
    rangeMin.addEventListener('input', updateSlider);
    rangeMax.addEventListener('input', updateSlider);

    // Inicializar o slider
    updateSlider();
});
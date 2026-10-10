document.addEventListener("DOMContentLoaded", () => {

    /*
     * ============================================================
     * ELEMENTOS DO FILTRO
     * ============================================================
     */

    const precoMinInput = document.getElementById("precoMin");
    const precoMaxInput = document.getElementById("precoMax");


    /*
     * ============================================================
     * PREÇO
     * ============================================================
     *
     * O componente de filtro pode ser reutilizado em páginas
     * diferentes. Portanto, se os campos não existirem,
     * simplesmente não executamos essa parte.
     */

    if (precoMinInput && precoMaxInput) {

        precoMinInput.addEventListener("input", () => {

            const min = parseFloat(precoMinInput.value);
            const max = parseFloat(precoMaxInput.value);

            if (
                !isNaN(min)
                && !isNaN(max)
                && min > max
            ) {
                precoMaxInput.value = min;
            }

        });


        precoMaxInput.addEventListener("input", () => {

            const min = parseFloat(precoMinInput.value);
            const max = parseFloat(precoMaxInput.value);

            if (
                !isNaN(min)
                && !isNaN(max)
                && max < min
            ) {
                precoMinInput.value = max;
            }

        });

    }

});

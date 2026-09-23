<?php

function renderCardTeste(string $nome = 'Produto teste')
{
?>

    <div class="card h-100">

        <div class="ratio ratio-4x3 bg-secondary-subtle">
            <div class="d-flex align-items-center justify-content-center">
                <span class="text-secondary">
                    Imagem
                </span>
            </div>
        </div>

        <div class="card-body">

            <h5 class="card-title">
                <?= htmlspecialchars($nome) ?>
            </h5>

            <p class="card-text text-secondary">
                Espaço reservado para o futuro card de produto.
            </p>

        </div>

    </div>

<?php
}
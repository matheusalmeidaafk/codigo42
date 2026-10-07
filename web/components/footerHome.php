<?php
// components/footerHome.php
// Só o HTML do rodapé: sem require de API, sem <html>/<head>.

$colunasFooter = [
    'Loja' => [
        'Camisetas' => '#',
        'Canecas'   => '#',
        'Adesivos'  => '#',
        'Novidades' => '#',
    ],
    'Empresa' => [
        'Sobre nós' => '#',
        'Parceiros' => '#',
    ],
    'Termos de uso' => [
        'Privacidade' => '#',
        'Segurança'   => '#',
    ],
];
?>

<footer class="bg-dark text-light py-4 mt-auto">
    <div class="container">
        <div class="row gy-4 align-items-start">

            <div class="col-12 col-md-4">
                <img src="/assets/images/logo.png" alt="Código 42" height="120" class="mb-2">
                <p class="small mb-0">
                    Camisetas, canecas e adesivos com identidade.
                    Feitos para quem não quer parecer — quer ser.
                </p>
            </div>

            <?php $primeira = true; ?>
            <?php foreach ($colunasFooter as $titulo => $links): ?>
                <div class="col-6 col-md-2 text-center <?= $primeira ? 'offset-md-2' : '' ?>">
                    <h6 class="fw-bold fs-5 mb-1"><?= htmlspecialchars($titulo) ?></h6>
                    <ul class="list-unstyled small mb-0">
                        <?php foreach ($links as $texto => $url): ?>
                            <li>
                                <a href="<?= htmlspecialchars($url) ?>" class="text-light text-decoration-none">
                                    <?= htmlspecialchars($texto) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php $primeira = false; ?>
            <?php endforeach; ?>

        </div>
    </div>
</footer>
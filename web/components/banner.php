<?php
require_once __DIR__ . '/../services/produtoApi.php';
$bannerEscape = static fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$bannerSafe = static fn($v) => is_string($v) && preg_match('~^(https?://|/(?!/))[^\r\n]*$~i', $v);
try {
    $bannersHome = array_values(array_filter(requisitarApi('/banners'), static fn($b) => $bannerSafe($b['bannerUrl'] ?? null)));
} catch (Throwable $e) {
    error_log('[HOME BANNERS] ' . $e->getMessage());
    $bannersHome = [];
    foreach ([1, 2, 3] as $n) $bannersHome[] = ['bannerUrl' => "/assets/images/banner/banner%20$n.png", 'linkUrl' => '/pages/produtos.php'];
}
?>
<?php if ($bannersHome): ?>
<section class="home-banner" aria-label="Destaques da loja">
<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
<div class="carousel-inner">
<?php foreach ($bannersHome as $i => $banner): ?>
<div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
<?php $link = $banner['linkUrl'] ?? ''; if ($bannerSafe($link)): ?><a href="<?= $bannerEscape($link) ?>"><?php endif; ?>
<img src="<?= $bannerEscape($banner['bannerUrl']) ?>" class="d-block w-100" alt="Destaque <?= $i + 1 ?> da Código 42" <?= $i > 0 ? 'loading="lazy"' : '' ?>>
<?php if ($bannerSafe($link)): ?></a><?php endif; ?>
</div>
<?php endforeach; ?>
</div>
<?php if (count($bannersHome) > 1): ?>
<div class="carousel-indicators indicadores-banner">
<?php foreach ($bannersHome as $i => $_): ?>
<button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>" <?= $i === 0 ? 'aria-current="true"' : '' ?> aria-label="Destaque <?= $i + 1 ?>"></button>
<?php endforeach; ?>
</div>
<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev"><span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span><span class="visually-hidden">Anterior</span></button>
<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next"><span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span><span class="visually-hidden">Próximo</span></button>
<?php endif; ?>
</div>
</section>
<?php endif; ?>

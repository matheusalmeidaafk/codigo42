<?php

function renderInputText(array $props): void
{
    $id = htmlspecialchars($props['id'] ?? '', ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars($props['name'] ?? $id, ENT_QUOTES, 'UTF-8');
    $label = htmlspecialchars($props['label'] ?? '', ENT_QUOTES, 'UTF-8');
    $type = htmlspecialchars($props['type'] ?? 'text', ENT_QUOTES, 'UTF-8');
    $placeholder = htmlspecialchars($props['placeholder'] ?? '', ENT_QUOTES, 'UTF-8');
    $autocomplete = htmlspecialchars($props['autocomplete'] ?? '', ENT_QUOTES, 'UTF-8');
    $inputmode = htmlspecialchars($props['inputmode'] ?? '', ENT_QUOTES, 'UTF-8');

    $wrapperClass = htmlspecialchars(
        $props['wrapperClass'] ?? 'mb-2',
        ENT_QUOTES,
        'UTF-8'
    );

    $inputClass = htmlspecialchars(
        $props['inputClass'] ?? 'form-control codigo42-input',
        ENT_QUOTES,
        'UTF-8'
    );

    $required = $props['required'] ?? false;
?>

    <div class="<?= $wrapperClass ?>">

        <label
            for="<?= $id ?>"
            class="form-label fw-bold mb-1">
            <?= $label ?>
        </label>

        <input
            type="<?= $type ?>"
            id="<?= $id ?>"
            name="<?= $name ?>"
            class="<?= $inputClass ?>"
            placeholder="<?= $placeholder ?>"

            <?php if ($autocomplete): ?>
                autocomplete="<?= $autocomplete ?>"
            <?php endif; ?>

            <?php if ($inputmode): ?>
                inputmode="<?= $inputmode ?>"
            <?php endif; ?>

            <?php if ($required): ?>
                required
            <?php endif; ?>
        >

    </div>

<?php
}
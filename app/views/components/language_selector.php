<?php
require_once __DIR__ . '/../../helpers/i18n.php';
// Language selector component
$currentLang = getCurrentLang();
$availableLanguages = getAvailableLanguages();
?>
<div class="language-dropdown">
    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-globe me-1"></i><?= __('language') ?>
    </button>
    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
        <?php foreach ($availableLanguages as $code => $name): ?>
        <li>
            <a class="dropdown-item <?= $currentLang === $code ? 'active' : '' ?>" href="/work/public/language?lang=<?= $code ?>">
                <?= $name ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div> 
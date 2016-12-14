<section class="error">
    <div class="container">

        <div class="error-container">
            <div class="icon-container">
                <?= file_get_contents(PIMCORE_DOCUMENT_ROOT . '/static/dist/assets/icons/icon_error.svg'); ?>
            </div>
            <div class="message-container">
                <?php if ($this->errorCode || $this->errorMessage): ?>
                    <h3><?= $this->errorCode . ' &ndash; ' . $this->t($this->errorMessage); ?></h3>
                <?php endif; ?>
                <h1><?= $this->t('Seite wurde nicht gefunden.'); ?></h1>
                <p class="error-message"><?= $this->t('Wir konnten die Seite nach der du<br />suchst leider nicht finden.'); ?></p>
                <a class="error-button" href="/" target="_self"><?= $this->t('Zurück zur Startseite'); ?></a>
            </div>
        </div>

    </div>
</section>

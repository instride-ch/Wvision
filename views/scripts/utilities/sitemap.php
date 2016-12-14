<div class="container">
    <?php if ($this->initial): ?>
    <ul>
        <?php endif; ?>

        <li><a href="<?= $this->doc->getFullpath(); ?>"><?= $this->doc->getProperty('navigation_name'); ?></a></li>

        <?php if ($this->doc->hasChilds()): ?>
            <ul>
                <?php foreach ($this->doc->getChilds() as $child): ?>
                    <?php if (in_array($child->getType(), ['page', 'link'])): ?>
                        <?= $this->action('sitemap', 'utilities', 'Wvision', ['doc' => $child]); ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($this->initial): ?>
    </ul>
    <?php endif; ?>
</div>

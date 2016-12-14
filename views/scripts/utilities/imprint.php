<?php $this->placeholder('footer')->captureStart(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.getJSON('//w-vision.ch/address?callback=?', 'service=wvision,wvisionDesign' /*,kobalt'*/, function(res) {
                $('#copyright .contentblock-wrapper').append(res.wvisionDesign.addr).addClass('contentblock');
                //$('#copyright .contentblock-wrapper').append(res.kobalt.addr).addClass('contentblock');
                $('#copyright .contentblock-wrapper').append(res.wvision.addr).addClass('contentblock');
            });
        });
    </script>
<?php $this->placeholder('footer')->captureEnd(); ?>

<article class="imprint cushion">
    <div class="container">

        <aside class="sidebar-wrapper hidden-xs" data-spy="affix" data-offset-top="0" data-offset-bottom="0">
            <ul class="sidebar-navigation">
                <?php $i = 0; ?>
                <?php foreach ($this->block('article')->getElements() as $entry): ?>
                    <li><a class="anchor" href="#article-<?= $i; ?>"><?= $entry->getInput('articleTitle'); ?></a></li>
                    <?php $i++; ?>
                <?php endforeach; ?>

                <?php if (!$this->input('copyrightTitle')->isEmpty()): ?>
                    <li><a class="anchor" href="#copyright"><?= $this->input('copyrightTitle')->getData(); ?></a></li>
                <?php endif; ?>
            </ul>
        </aside>

        <aside class="sidebar-wrapper visible-xs" data-spy="affix" data-offset-top="85" data-offset-bottom="0">
            <ul class="sidebar-navigation">
                <li><a id="triggerSidebar" href="#"><span class="mobicon"><span></span></span><?= $this->t('Artikel'); ?></a></li>

                <?php $i = 0; ?>
                <?php foreach ($this->block('article')->getElements() as $entry): ?>
                    <li><a class="anchor" href="#article-<?= $i; ?>"><?= $entry->getInput('articleTitle'); ?></a></li>
                    <?php $i++; ?>
                <?php endforeach; ?>

                <?php if (!$this->input('copyrightTitle')->isEmpty()): ?>
                    <li><a class="anchor" href="#copyright"><?= $this->input('copyrightTitle')->getData(); ?></a></li>
                <?php endif; ?>
            </ul>
        </aside>

        <section class="content-wrapper">
            <?php while ($this->block('article')->loop()): ?>
                <?php $sectionId = $this->block('article')->getCurrent(); ?>

                <div id="article-<?= $sectionId; ?>" class="article">
                    <h2><?= $this->input('articleTitle'); ?></h2>

                    <?php if ($this->editmode): ?>
                        <?= $this->wysiwyg('articleText', [
                            'height' => 100,
                            'customConfig' => '/static/edit/js/ckeditor_config.js'
                        ]); ?>
                    <?php else: ?>
                        <?php foreach ($this->config as $config): ?>
                            <?php $text = str_replace('{' . $config->getName() . '}', $config->getData(), $this->wysiwyg('articleText')->getData()); ?>
                        <?php endforeach; ?>

                        <?= $text; ?>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>

            <div id="copyright" class="article">
                <h2><?= $this->input('copyrightTitle'); ?></h2>

                <div class="contentblock-wrapper">
                    <div class="contentblock">
                        <h4><?= $this->input('copyrightPublisher'); ?></h4>
                        <?php if ($this->editmode): ?>
                            <?= $this->wysiwyg('copyrightText', [
                                'height' => 100,
                                'customConfig' => '/static/edit/js/ckeditor_config.js'
                            ]); ?>
                        <?php else: ?>
                            <?php $text = $this->wysiwyg('copyrightText')->getData(); ?>
                            <?php foreach ($this->config as $config): ?>
                                <?php $text = str_replace('{' . $config->getName() . '}', $config->getData(), $text); ?>
                            <?php endforeach; ?>

                            <?= $text; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </section>

    </div>
</article>

<?php $this->inherits('main'); ?>
<?php $this->block('title'); ?><?= $keyword ?> — OverDocs.net<?php $this->end(); ?>
<?php $this->block('content'); ?>
<div class="content">
    <h2>Sheets tagged as <em><?= $keyword ?></em></h2>
    <ul class="sheet-list">
    <?php foreach ($sheets as $sheet): ?>
        <li>
            <a href="<?= $base ?>/<?= $sheet['path'] ?>"><?= $this->escape($sheet['title']) ?>
            <span class="sheet-summary"><?= $this->escape($sheet['summary']) ?></span></a>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
<?php $this->end(); ?>

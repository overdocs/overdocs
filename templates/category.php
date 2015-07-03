<?php $this->inherits('main'); ?>
<?php $this->block('title'); ?><?= $names[$category] ?> cheat sheets — OverDocs.net<?php $this->end(); ?>
<?php $this->block('content'); ?>
<div class="content">
    <h2><?= $names[$category] ?> cheat sheets</h2>
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

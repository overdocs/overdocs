<?php $this->inherits('main'); ?>
<?php $this->block('title'); ?><?= $title ?> — OverDocs.net<?php $this->end(); ?>
<?php $this->block('content'); ?>
<div class="content">
    <h2><?= $title ?></h2>
    <p><?= $message ?></p>
</div>
<?php $this->end(); ?>

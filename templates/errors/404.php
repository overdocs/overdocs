<?php $this->inherits('main'); ?>
<?php $this->block('title'); ?>Page not found — OverDocs.net<?php $this->end(); ?>
<?php $this->block('content'); ?>
<div class="content">
    <h2>Page not found</h2>

    <p>
        Sorry, but page you've requested could not be found. Head to the <a href="<?= $base ?>/">
        home page</a> and either use search engine or browse available cheat sheets by category.
    </p>

    <!-- yay, let's screw up indentation - prism.js <3 -->
    <pre>
        <code class="language-php">
throw new VeryUnexpectedException("Page not found :(", 404);
        </code></pre>
</div>
<?php $this->end(); ?>
<?php $this->block('scripts'); ?>
<script src="<?= $base ?>/vendor/prism.js"></script>
<?php $this->end(); ?>

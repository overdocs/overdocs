<?php $this->inherits('main'); ?>
<?php $this->block('title'); ?>Cheatsheets for developers — OverDocs.net<?php $this->end(); ?>
<?php $this->block('content'); ?>
<section class="intro">
    <div class="container">
        <p class="intro-primary">OverDocs collects the most important APIs, solutions and practices.</p>
        <p class="intro-secondary">OverDocs are cheatsheets for developers.</p>
        <noscript>
            JavaScript is required to search OverDocs. Enable JavaScript or navigate by categories.
        </noscript>
        <div class="search-container"></div>
    </div>
</section>
<section class="categories" id="categories">
    <nav class="container">
        <h2>Or have a look at the categories…</h2>
        <ul>
        <?php foreach ($categories as $slug => $name): ?>
            <li><a href="<?= $app->url('category', ['category' => $slug]) ?>"><img src="<?= $base ?>/images/categories/<?= $slug ?>.png"><?= $this->escape($name) ?></a></li>
        <?php endforeach; ?>
        </ul>
    </nav>
</section>
<?php $this->end(); ?>
<?php $this->block('scripts'); ?>
<script id="search-template" type="text/html">
    <div class="search">
        <input class="search-input" type="search" autocomplete="off" autofocus
               placeholder="Search for »styling text selection«">
        <div class="search-results"></div>
    </div>
</script>
<script id="search-result-template" type="text/html">
    <div class="search-result-title"><span class="search-result-category">(<%- categoryName %>)</span> <%- title %></div>
    <div class="search-result-summary"><%- summary %></div>
</script>
<?php if ($env === 'development'): ?>
<script src="<?= $base ?>/vendor/zepto.js"></script>
<script src="<?= $base ?>/vendor/lodash.js"></script>
<script src="<?= $base ?>/vendor/backbone.js"></script>
<script src="<?= $base ?>/vendor/fuse.js"></script>
<script src="<?= $base ?>/vendor/prism.js"></script>
<script src="<?= $base ?>/js/app.js"></script>
<script src="<?= $base ?>/js/utils.js"></script>
<script src="<?= $base ?>/js/models.js"></script>
<script src="<?= $base ?>/js/views.js"></script>
<script src="<?= $base ?>/js/search.js"></script>
<script src="<?= $base ?>/js/search_views.js"></script>
<script src="<?= $base ?>/js/main.js"></script>
<script src="<?= $base ?>/js/index.js"></script>
<?php else: ?>
<script src="<?= $base ?>/js/bundle.js"></script>
<?php endif; ?>
<?php $this->end(); ?>

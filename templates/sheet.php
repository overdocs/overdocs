<?php $this->inherits('main'); ?>
<?php $this->block('title'); ?><?= $this->escape($title) ?> — OverDocs.net<?php $this->end(); ?>
<?php $this->block('content'); ?>
    <article class="sheet content">
        <header>
            <h2>
                <?= $this->escape($title) ?>
                <a class="fork-button" href="<?= $this->escape($editOnGithubURL) ?>">
                    Edit on GitHub
                </a>
                <a class="sheet-category" href="<?= $app->url('category', ['category' => $category]) ?>"
                   title="Browse category">
                    <img src="<?= $base ?>/images/categories/<?= $category ?>.png"
                         alt="In category: <?= $this->escape($categoryName) ?>" />
                </a>
            </h2>
        </header>
        <?= $content ?>
        <footer class="sheet-footer">
            <h3 id="keywords">Keywords <a class="anchor" href="#keywords">#keywords</a></h3>
            <ul class="sheet-keywords">
                <?php foreach ($keywords as $keyword): ?>
                    <li>
                        <a href="<?= $app->url('keyword', ['keyword' => $keyword]) ?>"><?= $keyword ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </footer>

        <h3 id="comments">Comments <a class="anchor" href="#comments">#comments</a></h3>
        <div id="disqus_thread"></div>
        <script>
            var disqus_shortname = '<?= $disqusShortname; ?>';
            var disqus_identifier = '<?= $disqusIdentifier; ?>';

            (function() {
                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">
        comments powered by Disqus.</a></noscript>
    </article>
<?php $this->end(); ?>
<?php $this->block('scripts'); ?>
<script src="<?= $base ?>/vendor/prism.js"></script>
<script src="<?= $base ?>/vendor/zepto.js"></script>
<script src="<?= $base ?>/js/sheet.js"></script>
<?php $this->end(); ?>

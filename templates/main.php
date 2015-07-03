<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php $this->block('title');?>OverDocs.net<?php $this->end(); ?></title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,400italic">
    <link rel="stylesheet" href="<?= $base ?>/styles/overdocs.css" async>
    <link rel="icon" href="<?= $base ?>/favicon.ico">
    <script>
        var OverDocs = {
            baseURL: '<?= $base ?>'
        };
    </script>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1><a href="<?= $base ?>/">OverDocs</a></h1>
            <nav class="header-nav" role="navigation">
                <ul>
                    <li><a href="<?= $app->url('about') ?>">About</a></li>
                    <li><a href="<?= $github_url ?>">Source</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <?php $this->block('content'); ?><?php $this->end(); ?>
    </main>
    <script>
        window.addEventListener('scroll', function (e) {
            if (window.scrollY * Math.max(window.innerHeight, document.body.clientHeight) > 50) {
                if (!document.body.className.match(/(^|\s+)scrolled($|\s+)/)) {
                    document.body.className += ' scrolled';
                }
            } else {
                document.body.className = document.body.className.replace('scrolled', '');
            }
        });
    </script>

    <?php if ($analytics_id): ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '<?= $analytics_id ?>', 'auto');
        ga('send', 'pageview');
    </script>
    <?php endif; ?>
    <?php $this->block('scripts'); ?><?php $this->end(); ?>
</body>
</html>

<?php $this->inherits('main'); ?>
<?php $this->block('title'); ?>About — OverDocs.net<?php $this->end(); ?>
<?php $this->block('content'); ?>
<div class="content">
    <h2>About OverDocs</h2>
    <h3 id="philosophy">Philosophy</h3>
    <p>
        OverDocs aims to be practical source of knowledge, both for beginners and advanced
        developers. These first can learn best programming practices, while more experienced
        users can utilize OverDocs as a handy reference complementary to manuals or books.
    </p>
    <p>
        We didn't want to create another blog with tutorials. That's why while writing content
        we are assuming that you have a basic knowledge of the topic. Such approach brings
        profits for everyone. You don't have to read a wall of text, just a summary of the most
        important informations, code snippets and reference for further reading.
    </p>
    <p>
        There are tons of resources promoting obsolete or just wrong solutions. We would love to
        focus on modern techniques and that's why we encourage everyone to join our efforts and
        share a piece of their experience.
    </p>

    <h3 id="contributing">Contributing</h3>
    <p>
        We would love to have your contributions to the content. Therefore OverDocs is fully open source.
        If you found any place you can improve, just click on the <em>Edit on GitHub</em> button on the
        right top. It will automatically create fork of <a href="https://github.com/overdocs/overdocs">
        our repository</a> and allow you to modify source file for given sheet. However, GitHub account
        is obviously required.
    </p>
    <p>
        You can also manually fork repository and send us a Pull Request. We will review all proposals,
        promise!
    </p>
    <p>
        Nothing is perfect. The most preferable place for reporting bugs is
        <a href="https://github.com/overdocs/overdocs/issues">issues section</a> in our GitHub repository.
    </p>

    <h3 id="credits">Credits</h3>
    <p>
        The idea of OverDocs was born in minds of two Polish developers having different reasons,
        but sharing the same goal - delivering knowledge.
    </p>

    <h4><a href="https://olgierd.me" class="credits-link">winek</a></h4>
    <p>
        Nobody has become an expert after a few weeks of education. There is a multitude of outdated
        resources, recommending antipatterns and bad practices. I started OverDocs because I wanted
        a collection of helpful resources written in a concise form.
    </p>

    <h4><a href="http://sobak.pl" class="credits-link">Sobak</a></h4>
    <p>
        Taking initial steps in web technologies, I was looking for people who could support me.
        I decided to register on several discussion forums and started asking, but while gaining
        experience, I slowly became the one helping others.
    </p>
    <p>
        Years passed and soon I realized that questions are very repetitive. I needed some reference,
        where I could point questioners to. And there it is—set of the notes, ideas, code snippets
        and other interesting resources found in the Internet—also used by me in my everyday work.
    </p>

    <h3 id="contact">Contact</h3>
    <p>
         Want to contribute but don't have enough time for Pull Request? Have a question? Or maybe just want
         to say "hello"? Feel free to email us at either
         <span class="go-away-sobak" title="Sorry, anti-spam protection">@</span> (Sobak) or
         <span class="go-away-winek" title="Sorry, anti-spam protection">@</span> (winek).
    </p>
</div>
<?php $this->end(); ?>

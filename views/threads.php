<div class="forum-widget-wrapper">
    <?php

    if (!$async && (!isset($json) || !isset($json['threads']) || empty($json['threads']))) {
        ?>
        <div class="error">
            <p>There is no records yet.</p>
        </div>
        <?php
    } elseif ($async) {
        ?>
        <div class="threads-wrapper" data-url="<?= $requestUrl; ?>" data-base-url="<?= $forumUrl; ?>"
             data-images-url="<?= $pluginUrl; ?>"></div>
        <?php
    } else {
        foreach ($json['threads'] as $thread) {
            ?>
            <div class="thread">
                <div class="inner-icon">
                    <img src="<?php echo esc_url(plugins_url('../images/question.svg', __FILE__)); ?>" alt="question"/>
                </div>
                <div class="thread-link">
                    <a href="<?= $forumUrl . '/thread/' . $thread['slug']; ?>"
                       target="_blank"><?= $thread['name']; ?></a>
                </div>
                <div class="messages">
                    <img src="<?php echo esc_url(plugins_url('../images/messages.svg', __FILE__)); ?>" alt="messages"/>
                    <span class="font-bold"><?= $thread['posts']; ?></span>
                </div>
            </div>

            <?php
        }
    }
    ?>
</div>

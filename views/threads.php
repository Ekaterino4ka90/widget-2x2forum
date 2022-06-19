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
        <div class="threads-wrapper" data-url="<?php echo esc_url( $requestUrl ); ?>" data-base-url="<?php echo esc_url( $forumUrl ); ?>"
             data-images-url="<?php echo esc_url( $pluginUrl ); ?>"></div>
        <?php
    } else {
        foreach ($json['threads'] as $thread) {
            ?>
            <div class="thread">
                <div class="inner-icon">
                    <img src="<?php echo esc_url(plugins_url('../images/question.svg', __FILE__)); ?>" alt="question"/>
                </div>
                <div class="thread-link">
                    <a href="<?php echo esc_url( $forumUrl . '/thread/' . $thread['slug'] ); ?>"
                       target="_blank"><?php echo esc_attr( $thread['name'] ); ?></a>
                </div>
                <div class="messages">
                    <img src="<?php echo esc_url(plugins_url('../images/messages.svg', __FILE__)); ?>" alt="messages"/>
                    <span class="font-bold"><?php echo esc_attr( $thread['posts'] ); ?></span>
                </div>
            </div>

            <?php
        }
    }
    ?>
</div>

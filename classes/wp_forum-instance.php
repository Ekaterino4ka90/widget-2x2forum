<?php

class WP_Forum_Instance extends WP_Widget
{
    /**
     * Automatically cache the response for 5 min.
     */
    const CACHING_TIME = 5;

    public function __construct()
    {
        $widget_options = array(
            'classname' => 'forum-wrapper',
            'description' => 'Latest threads widget to display recent posts from 2x2forum.ru or mywebforum.com.',
        );
        parent::__construct('2x2forum_widget', '2x2Forum Widget', $widget_options);
    }

    public function enqueue_style()
    {
        wp_enqueue_script('forum_js');

        wp_enqueue_style('forum_css');
    }

    public function widget($args, $instance)
    {
        add_action('wp_enqueue_scripts', self::enqueue_style());
        $title = apply_filters('widget_title', $instance['title']);
        $forumUrl = apply_filters('widget_forum_url', $instance['forum_url']);
        $count = apply_filters('widget_count', $instance['count']);
        $async = isset($instance['async']) ? (bool)$instance['async'] : false;

        $pluginUrl = plugins_url('../images', __FILE__);
        // If Forum URL is not valid or empty.
        if (empty($forumUrl)) {
            return;
        }

        $url = parse_url($forumUrl);

        if (!is_array($url)) {
            return;
        }

        $requestUrl = 'https://' . $url['host'] . '/api/thread/latest?limit=' . $count;

        echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title'];

        if (!$async) {

            $data = wp_cache_get('forum_' . $url['host'] . $count . '_threads', '');

            if (!$data) {
                $response = wp_remote_get($requestUrl, array(
                    'timeout' => 5,
                    'sslverify' => false,
                ));
                if (!is_wp_error($response)) {
                    $data = wp_remote_retrieve_body($response);
                    wp_cache_set('forum_' . $url['host'] . $count . '_threads', $data, '', self::CACHING_TIME * MINUTE_IN_SECONDS);
                }
            }

            if ($data) {
                $json = json_decode($data, true);
            }
        }
        require_once(dirname(__FILE__) . '/../views/threads.php');

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $forumUrl = !empty($instance['forum_url']) ? $instance['forum_url'] : '';
        $count = !empty($instance['count']) ? $instance['count'] : '';
        $async = !empty($instance['async']) ? $instance['async'] : 0;
        $title = !empty($instance['title']) ? $instance['title'] : ''; ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input type="text" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('forum_url'); ?>">Forum URL:</label>
            <input type="text" id="<?php echo $this->get_field_id('forum_url'); ?>"
                   name="<?php echo $this->get_field_name('forum_url'); ?>" value="<?php echo esc_attr($forumUrl); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>">Number of Latest Threads:</label>
            <input type="text" id="<?php echo $this->get_field_id('count'); ?>"
                   name="<?php echo $this->get_field_name('count'); ?>" value="<?php echo esc_attr($count); ?>"/>
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('async'); ?>"
                   name="<?php echo $this->get_field_name('async'); ?>"<?php checked($async); ?> />
            <label for="<?php echo $this->get_field_id('async'); ?>"><?php _e('Async mode'); ?></label>
        </p>
        <?php

    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['forum_url'] = $new_instance['forum_url'];
        $instance['async'] = !empty($new_instance['async']) ? 1 : 0;
        if (filter_var($new_instance['forum_url'], FILTER_VALIDATE_URL)) {
            $instance['forum_url'] = $new_instance['forum_url'];
        }
        $instance['count'] = intval($new_instance['count']);
        return $instance;
    }
}

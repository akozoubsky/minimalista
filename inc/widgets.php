<?php

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function minimalista_widgets_init()
{
    register_sidebar(
        array(
            'name'          => esc_html__('Sidebar', 'minimalista'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here.', 'minimalista'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        )
    );
}
add_action('widgets_init', 'minimalista_widgets_init');


function minimalista_register_social_widget()
{
    register_widget('Minimalista_Social_Widget');
}
add_action('widgets_init', 'minimalista_register_social_widget');

class Minimalista_Social_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'minimalista_social_widget',
            __('Social Media Links', 'minimalista'),
            array('description' => __('A widget to display social media links.', 'minimalista'))
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
?>
        <div class="social-links">
            <?php if (!empty($instance['facebook'])) : ?>
                <a href="<?php echo esc_url($instance['facebook']); ?>" target="_blank" class="social-link facebook">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
            <?php endif; ?>
            <?php if (!empty($instance['twitter'])) : ?>
                <a href="<?php echo esc_url($instance['twitter']); ?>" target="_blank" class="social-link twitter">
                    <i class="fab fa-twitter"></i> Twitter
                </a>
            <?php endif; ?>
            <?php if (!empty($instance['instagram'])) : ?>
                <a href="<?php echo esc_url($instance['instagram']); ?>" target="_blank" class="social-link instagram">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
            <?php endif; ?>
            <!-- Adicione mais redes sociais conforme necessário -->
        </div>
    <?php
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $facebook = !empty($instance['facebook']) ? $instance['facebook'] : '';
        $twitter = !empty($instance['twitter']) ? $instance['twitter'] : '';
        $instagram = !empty($instance['instagram']) ? $instance['instagram'] : '';
    ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'minimalista'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('facebook')); ?>"><?php esc_attr_e('Facebook URL:', 'minimalista'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook')); ?>" name="<?php echo esc_attr($this->get_field_name('facebook')); ?>" type="text" value="<?php echo esc_attr($facebook); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('twitter')); ?>"><?php esc_attr_e('Twitter URL:', 'minimalista'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('twitter')); ?>" name="<?php echo esc_attr($this->get_field_name('twitter')); ?>" type="text" value="<?php echo esc_attr($twitter); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('instagram')); ?>"><?php esc_attr_e('Instagram URL:', 'minimalista'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('instagram')); ?>" name="<?php echo esc_attr($this->get_field_name('instagram')); ?>" type="text" value="<?php echo esc_attr($instagram); ?>">
        </p>
    <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['facebook'] = (!empty($new_instance['facebook'])) ? esc_url_raw($new_instance['facebook']) : '';
        $instance['twitter'] = (!empty($new_instance['twitter'])) ? esc_url_raw($new_instance['twitter']) : '';
        $instance['instagram'] = (!empty($new_instance['instagram'])) ? esc_url_raw($new_instance['instagram']) : '';
        return $instance;
    }
}

/**
 * Plugin Name: Minimalista Recent Posts Widget
 * Description: Customiza o widget de Posts Recentes para usar a marcação de tags do Bootstrap 5.3.
 * Version: 1.0
 * Author: Alexandre Kozoubsky
 */

class Minimalista_Recent_Posts_Widget extends WP_Widget_Recent_Posts
{
    public function widget($args, $instance)
    {
        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        $title = (!empty($instance['title'])) ? $instance['title'] : __('Recent Posts');

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
        if (!$number) {
            $number = 5;
        }

        $show_date = isset($instance['show_date']) ? $instance['show_date'] : false;

        $r = new WP_Query(
            apply_filters(
                'widget_posts_args',
                array(
                    'posts_per_page'      => $number,
                    'no_found_rows'       => true,
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true,
                ),
                $instance
            )
        );

        if (!$r->have_posts()) {
            return;
        }
    ?>

        <?php echo $args['before_widget']; ?>

        <?php if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        } ?>

        <ul class="list-group">
            <?php foreach ($r->posts as $recent_post) :
                $post_title   = get_the_title($recent_post->ID);
                $title        = (!empty($post_title)) ? $post_title : __('(no title)');
                $aria_current = '';
                if (get_queried_object_id() === $recent_post->ID) {
                    $aria_current = ' aria-current="page"';
                }
            ?>
                <li class="list-group-item">
                    <a href="<?php the_permalink($recent_post->ID); ?>" <?php echo $aria_current; ?>><?php echo $title; ?></a>
                    <?php if ($show_date) : ?>
                        <span class="post-date"><?php echo get_the_date('', $recent_post->ID); ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php echo $args['after_widget']; ?>
        <?php
    }
}

function register_minimalista_recent_posts_widget()
{
    unregister_widget('WP_Widget_Recent_Posts');
    register_widget('Minimalista_Recent_Posts_Widget');
}
add_action('widgets_init', 'register_minimalista_recent_posts_widget');

/**
 * Plugin Name: Minimalista Categories Widget
 * Description: Customiza o widget de Categorias para usar a marcação de tags do Bootstrap 5.3.
 * Version: 1.0
 * Author: Alexandre Kozoubsky
 */

class Minimalista_Categories_Widget extends WP_Widget_Categories
{
    public function widget($args, $instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('Categories');

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $count  = !empty($instance['count']) ? '1' : '0';
        $hierarchical = !empty($instance['hierarchical']) ? '1' : '0';
        $dropdown = !empty($instance['dropdown']) ? '1' : '0';

        echo $args['before_widget'];
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $cat_args = array(
            'orderby'      => 'name',
            'show_count'   => $count,
            'hierarchical' => $hierarchical,
        );

        if ($dropdown) {
            wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args, $instance));
        ?>

            <script type='text/javascript'>
                /* <![CDATA[ */
                var dropdown = document.getElementById("cat");

                function onCatChange() {
                    if (dropdown.options[dropdown.selectedIndex].value > 0) {
                        location.href = "<?php echo home_url(); ?>/?cat=" + dropdown.options[dropdown.selectedIndex].value;
                    }
                }
                dropdown.onchange = onCatChange;
                /* ]]> */
            </script>

        <?php
        } else {
        ?>
            <ul class="list-group">
                <?php
                $cat_args['title_li'] = '';
                $cat_args['style'] = 'list';
                $categories = get_categories($cat_args);
                foreach ($categories as $category) {
                    echo '<li class="list-group-item"><a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
                    if ($count) {
                        echo ' (' . $category->count . ')';
                    }
                    echo '</li>';
                }
                ?>
            </ul>
        <?php
        }

        echo $args['after_widget'];
    }
}

function register_minimalista_categories_widget()
{
    unregister_widget('WP_Widget_Categories');
    register_widget('Minimalista_Categories_Widget');
}
add_action('widgets_init', 'register_minimalista_categories_widget');

/**
 * Plugin Name: Minimalista Archives Widget
 * Description: Customiza o widget de Arquivos para usar a marcação de tags do Bootstrap 5.3.
 * Version: 1.0
 * Author: Alexandre Kozoubsky
 */

class Minimalista_Archives_Widget extends WP_Widget_Archives
{
    public function widget($args, $instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('Archives');

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $count  = !empty($instance['count']) ? '1' : '0';
        $dropdown = !empty($instance['dropdown']) ? '1' : '0';

        echo $args['before_widget'];
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $archive_args = array(
            'type'            => 'monthly',
            'show_post_count' => $count,
        );

        if ($dropdown) {
            $dropdown_id = "{$this->id_base}-dropdown-{$this->number}";
        ?>
            <label class="screen-reader-text" for="<?php echo esc_attr($dropdown_id); ?>"><?php echo $title; ?></label>
            <select id="<?php echo esc_attr($dropdown_id); ?>" name="archive-dropdown" onchange="document.location.href=this.options[this.selectedIndex].value;">
                <?php
                $archive_args['format'] = 'option';
                wp_get_archives(apply_filters('widget_archives_dropdown_args', $archive_args));
                ?>
            </select>
        <?php
        } else {
        ?>
            <ul class="list-group">
                <?php
                $archive_args['format'] = 'custom';
                $archive_args['before'] = '<li class="list-group-item">';
                $archive_args['after'] = '</li>';
                wp_get_archives(apply_filters('widget_archives_args', $archive_args));
                ?>
            </ul>
        <?php
        }

        echo $args['after_widget'];
    }
}

function register_minimalista_archives_widget()
{
    unregister_widget('WP_Widget_Archives');
    register_widget('Minimalista_Archives_Widget');
}
add_action('widgets_init', 'register_minimalista_archives_widget');

/**
 * Plugin Name: Minimalista Text Widget
 * Description: Customiza o widget de Texto para usar a marcação de tags do Bootstrap 5.3.
 * Version: 1.0
 * Author: Alexandre Kozoubsky
 */

class Minimalista_Text_Widget extends WP_Widget_Text
{
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $text = apply_filters('widget_text', empty($instance['text']) ? '' : $instance['text'], $instance, $this);

        echo $args['before_widget'];

        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        ?>
        <div class="card">
            <div class="card-body">
                <?php echo !empty($instance['filter']) ? wpautop($text) : $text; ?>
            </div>
        </div>
    <?php
        echo $args['after_widget'];
    }
}

function register_minimalista_text_widget()
{
    unregister_widget('WP_Widget_Text');
    register_widget('Minimalista_Text_Widget');
}
add_action('widgets_init', 'register_minimalista_text_widget');

/**
 * Plugin Name: Minimalista Tag Cloud Widget
 * Description: Customiza o widget de Nuvem de Tags para usar a marcação de tags do Bootstrap 5.3.
 * Version: 1.0
 * Author: Alexandre Kozoubsky
 */

class Minimalista_Tag_Cloud_Widget extends WP_Widget_Tag_Cloud
{
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Tags') : $instance['title'], $instance, $this->id_base);

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $tags = get_tags();
        if (!empty($tags)) {
            echo '<div class="tag-cloud">';
            foreach ($tags as $tag) {
                $tag_link = get_tag_link($tag->term_id);
                $tag_name = $tag->name;
                echo '<a href="' . esc_url($tag_link) . '" class="badge bg-primary m-1">' . esc_html($tag_name) . '</a>';
            }
            echo '</div>';
        }

        echo $args['after_widget'];
    }
}

function register_minimalista_tag_cloud_widget()
{
    unregister_widget('WP_Widget_Tag_Cloud');
    register_widget('Minimalista_Tag_Cloud_Widget');
}
add_action('widgets_init', 'register_minimalista_tag_cloud_widget');

/**
 * Plugin Name: Minimalista Gallery Widget
 * Description: Customiza o widget de Galeria para usar a marcação de tags do Bootstrap 5.3.
 * Version: 1.0
 * Author: Alexandre Kozoubsky
 */

class Minimalista_Gallery_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'minimalista_gallery_widget',
            'description' => 'Customiza o widget de Galeria para usar a marcação de tags do Bootstrap 5.3',
        );
        parent::__construct('minimalista_gallery_widget', 'Minimalista Gallery Widget', $widget_ops);
    }

    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Gallery') : $instance['title'], $instance, $this->id_base);

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Query for the images in the gallery
        $gallery_images = !empty($instance['gallery_images']) ? explode(',', $instance['gallery_images']) : array();

        if (!empty($gallery_images)) {
            echo '<div class="row">';
            foreach ($gallery_images as $image_id) {
                $img_src = wp_get_attachment_image_src($image_id, 'medium');
                if ($img_src) {
                    echo '<div class="col-6 col-md-4 mb-3">';
                    echo '<a href="' . esc_url(wp_get_attachment_url($image_id)) . '" class="d-block mb-4 h-100">';
                    echo '<img src="' . esc_url($img_src[0]) . '" class="img-fluid img-thumbnail" alt="">';
                    echo '</a>';
                    echo '</div>';
                }
            }
            echo '</div>';
        }

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $gallery_images = !empty($instance['gallery_images']) ? $instance['gallery_images'] : '';
    ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('gallery_images')); ?>"><?php _e('Gallery Images (comma-separated attachment IDs):'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('gallery_images')); ?>" name="<?php echo esc_attr($this->get_field_name('gallery_images')); ?>" type="text" value="<?php echo esc_attr($gallery_images); ?>">
        </p>
    <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['gallery_images'] = strip_tags($new_instance['gallery_images']);
        return $instance;
    }
}

function register_minimalista_gallery_widget()
{
    register_widget('Minimalista_Gallery_Widget');
}
add_action('widgets_init', 'register_minimalista_gallery_widget');

/**
 * Plugin Name: Minimalista Events Widget
 * Description: Customiza o widget de Agenda para usar a marcação de tags do Bootstrap 5.3.
 * Version: 1.0
 * Author: Alexandre Kozoubsky
 */

class Minimalista_Events_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'minimalista_events_widget',
            'description' => 'Customiza o widget de Agenda para usar a marcação de tags do Bootstrap 5.3',
        );
        parent::__construct('minimalista_events_widget', 'Minimalista Events Widget', $widget_ops);
    }

    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Events') : $instance['title'], $instance, $this->id_base);

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Query for the events
        $events = get_posts(array(
            'post_type' => 'event', // Supondo que os eventos sejam um tipo de post personalizado
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'ASC',
        ));

        if (!empty($events)) {
            echo '<ul class="list-group">';
            foreach ($events as $event) {
                $event_date = get_post_meta($event->ID, 'event_date', true);
                echo '<li class="list-group-item">';
                echo '<h5 class="mb-1">' . esc_html(get_the_title($event->ID)) . '</h5>';
                if ($event_date) {
                    echo '<small class="text-muted">' . esc_html($event_date) . '</small>';
                }
                echo '<p class="mb-1">' . esc_html(wp_trim_words(get_the_content($event->ID), 15, '...')) . '</p>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . __('No events found.', 'text_domain') . '</p>';
        }

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
    ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
    <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }
}

function register_minimalista_events_widget()
{
    register_widget('Minimalista_Events_Widget');
}
add_action('widgets_init', 'register_minimalista_events_widget');

/**
 * Plugin Name: Minimalista Bootstrap Image Widget
 * Description: Customiza o widget de Imagem do WordPress para usar a marcação de tags do Bootstrap 5.3.
 * Version: 1.0
 * Author: Alexandre Kozoubsky
 */

 class Minimalista_Bootstrap_Image_Widget extends WP_Widget_Media_Image {
    public function widget( $args, $instance ) {
        // Mesclando valores padrão com a instância atual
        $instance = wp_parse_args( $instance, array(
            'title' => '',
            'attachment_id' => '',
            'size' => 'medium',
            'width' => 0,
            'height' => 0,
            'caption' => '',
            'alt' => '',
            'link_type' => '',
            'link_url' => '',
            'link_target_blank' => false,
            'image_classes' => '',
            'link_classes' => '',
        ) );

        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $attachment_id = $instance['attachment_id'];

        if ( $attachment_id ) {
            $size = $instance['size'];
            $image_attributes = wp_get_attachment_image_src( $attachment_id, $size );
            $src = $image_attributes[0];
            $width = $image_attributes[1];
            $height = $image_attributes[2];
            $alt = empty( $instance['alt'] ) ? get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) : $instance['alt'];
            $alt = empty( $alt ) ? '' : ' alt="' . esc_attr( $alt ) . '"';
            $caption = $instance['caption'];

            $image_html = '<img src="' . esc_url( $src ) . '" class="card-img-top img-fluid ' . esc_attr( $instance['image_classes'] ) . '"' . $alt . ' width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '">';

            if ( ! empty( $instance['link_type'] ) ) {
                $link_url = '';
                switch ( $instance['link_type'] ) {
                    case 'file':
                        $link_url = wp_get_attachment_url( $attachment_id );
                        break;
                    case 'post':
                        $link_url = get_attachment_link( $attachment_id );
                        break;
                    case 'custom':
                        $link_url = $instance['link_url'];
                        break;
                }

                if ( ! empty( $link_url ) ) {
                    $target = $instance['link_target_blank'] ? ' target="_blank"' : '';
                    $image_html = '<a href="' . esc_url( $link_url ) . '" class="' . esc_attr( $instance['link_classes'] ) . '"' . $target . '>' . $image_html . '</a>';
                }
            }

            echo '<div class="card">';
            echo $image_html;

            if ( ! empty( $caption ) ) {
                echo '<div class="card-body">';
                echo '<p class="card-text">' . esc_html( $caption ) . '</p>';
                echo '</div>';
            }

            echo '</div>';
        }

        echo $args['after_widget'];
    }
}

function register_minimalista_bootstrap_image_widget() {
    unregister_widget( 'WP_Widget_Media_Image' );
    register_widget( 'Minimalista_Bootstrap_Image_Widget' );
}
add_action( 'widgets_init', 'register_minimalista_bootstrap_image_widget' );

<?php
/**
 * Plugin Name: Custom Bootstrap Gallery Widget
 * Description: Customiza o widget de Galeria do WordPress para usar a marcação de tags do Bootstrap 5.3.
 * Version: 1.0
 * Author: Alexandre Kozoubsky
 */

class Custom_Bootstrap_Gallery_Widget extends WP_Widget_Media_Gallery {
    public function widget( $args, $instance ) {
        // Mesclando valores padrão com a instância atual
        $instance = wp_parse_args( $instance, array(
            'title' => '',
            'ids' => '',
            'columns' => 3,
            'size' => 'thumbnail',
            'link' => 'post',
            'orderby' => 'post__in',
            'include' => '',
            'exclude' => '',
            'image_classes' => '',
            'link_classes' => '',
        ) );

        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $ids = $instance['ids'];

        if ( ! empty( $ids ) ) {
            if ( is_array( $ids ) ) {
                $ids = implode( ',', $ids );
            }

            $gallery_shortcode = '[gallery ids="' . esc_attr( $ids ) . '" columns="' . esc_attr( $instance['columns'] ) . '" size="' . esc_attr( $instance['size'] ) . '" link="' . esc_attr( $instance['link'] ) . '"]';

            $gallery_html = do_shortcode( $gallery_shortcode );

            // Adicionando classes Bootstrap 5.3
            $gallery_html = str_replace( 'gallery-item', 'gallery-item col-4', $gallery_html );
            $gallery_html = str_replace( 'gallery-icon', 'gallery-icon img-fluid', $gallery_html );

            echo '<div class="card">';
            echo '<div class="card-body">';
            echo $gallery_html;
            echo '</div>';
            echo '</div>';
        }

        echo $args['after_widget'];
    }
}

function register_custom_bootstrap_gallery_widget() {
    unregister_widget( 'WP_Widget_Media_Gallery' );
    register_widget( 'Custom_Bootstrap_Gallery_Widget' );
}
add_action( 'widgets_init', 'register_custom_bootstrap_gallery_widget' );


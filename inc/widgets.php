<?php
/**
 * Register widget area.
 * 
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * 
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Register Sidebar Widgets
 *
 * This function registers a widget areas for the sidebar.
 *
 * @return void
 */
function minimalista_register_sidebar_widgets()
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
add_action('widgets_init', 'minimalista_register_sidebar_widgets');

/**
 * Register Footer Widgets
 *
 * This function registers multiple widget areas for the footer.
 * Each widget area will adjust responsively based on the number of active widgets.
 *
 * @return void
 */
function minimalista_register_footer_widgets() {
    register_sidebar(array(
        'name' => 'Footer Widget Area 1',
        'id' => 'footer-1',
        'description' => 'Appears in the footer area as the first column.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => 'Footer Widget Area 2',
        'id' => 'footer-2',
        'description' => 'Appears in the footer area as the second column.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => 'Footer Widget Area 3',
        'id' => 'footer-3',
        'description' => 'Appears in the footer area as the third column.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => 'Footer Widget Area 4',
        'id' => 'footer-4',
        'description' => 'Appears in the footer area as the fourth column.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
}
add_action('widgets_init', 'minimalista_register_footer_widgets');

function minimalista_register_social_widget() {
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
                <a href="<?php echo esc_url($instance['facebook']); ?>" target="_blank" class="social-link facebook me-4">
                    <i class="fab fa-facebook-f me-2"></i> Facebook
                </a>
            <?php endif; ?>
            <?php if (!empty($instance['twitter'])) : ?>
                <a href="<?php echo esc_url($instance['twitter']); ?>" target="_blank" class="social-link twitter me-4">
                    <i class="fab fa-twitter me-2"></i> Twitter/X
                </a>
            <?php endif; ?>
            <?php if (!empty($instance['instagram'])) : ?>
                <a href="<?php echo esc_url($instance['instagram']); ?>" target="_blank" class="social-link instagram me-4">
                    <i class="fab fa-instagram me-2"></i> Instagram
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

/**
 * Add a custom class field to all widgets.
 *
 * @param WP_Widget $widget The widget instance.
 * @param null|array $return Return null or array (passed by reference).
 * @param array $instance The widget instance's settings.
 */
function minimalista_add_custom_class_field($widget, $return, $instance) {
    if (!isset($instance['custom_class'])) {
        $instance['custom_class'] = ''; // Default value.
    }
    ?>
    <p>
        <label for="<?php echo $widget->get_field_id('custom_class'); ?>"><?php _e('Custom Class:', 'minimalista'); ?></label>
        <input class="widefat" id="<?php echo $widget->get_field_id('custom_class'); ?>" name="<?php echo $widget->get_field_name('custom_class'); ?>" type="text" value="<?php echo esc_attr($instance['custom_class']); ?>">
    </p>
    <?php
}
add_action('in_widget_form', 'minimalista_add_custom_class_field', 10, 3);

/**
 * Save the custom class field for all widgets.
 *
 * @param array $instance The widget instance's settings.
 * @param array $new_instance New settings for this instance as input by the user via `WP_Widget::form()`.
 * @param array $old_instance Old settings for this instance.
 * @return array The updated settings.
 */
function minimalista_save_custom_class_field($instance, $new_instance, $old_instance) {
    $instance['custom_class'] = sanitize_text_field($new_instance['custom_class']);
    return $instance;
}
add_filter('widget_update_callback', 'minimalista_save_custom_class_field', 10, 3);

/**
 * Add the custom class to the widget parameters before display.
 *
 * @param array $params The widget parameters.
 * @return array The modified widget parameters.
 */
function minimalista_apply_custom_class($params) {
    global $wp_registered_widgets;

    // Get the widget ID.
    $widget_id = $params[0]['widget_id'];

    // Get the widget options.
    $widget_obj = $wp_registered_widgets[$widget_id];
    $widget_opt = get_option($widget_obj['callback'][0]->option_name);
    $widget_num = $widget_obj['params'][0]['number'];

    // Check for a custom class in the widget options.
    if (isset($widget_opt[$widget_num]['custom_class']) && !empty($widget_opt[$widget_num]['custom_class'])) {
        // Add the custom class to the widget container.
        $params[0]['before_widget'] = str_replace('class="', 'class="' . esc_attr($widget_opt[$widget_num]['custom_class']) . ' ', $params[0]['before_widget']);
    }

    return $params;
}
add_filter('dynamic_sidebar_params', 'minimalista_apply_custom_class');

/**
 * Register the Bootstrap Card widget.
 */
function register_bootstrap_card_widget() {
    register_widget('Bootstrap_Card_Widget');
}
add_action('widgets_init', 'register_bootstrap_card_widget');

/**
 * Widget API: Bootstrap_Card_Widget class
 *
 * @package Minimalista
 * @since 1.0.0
 * @version 1.0.0
 */

 /**
 * Core class used to implement the Bootstrap Card widget.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class Bootstrap_Card_Widget extends WP_Widget {

     /**
     * Sets up a new Bootstrap Card widget instance.
     *
     * @since 1.0.0
     */
    function __construct() {
        parent::__construct(
            'bootstrap_card_widget',
            __('Bootstrap Card', 'minimalista'),
            array('description' => __('A Bootstrap 5.3 card widget', 'minimalista'))
        );
    }

    /**
     * Outputs the content for the current Bootstrap Card widget instance.
     *
     * @since 1.0.0
     *
     * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Bootstrap Card widget instance.
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $width = !empty($instance['width']) ? $instance['width'] : '100%';
        $img_url = !empty($instance['img_url']) ? $instance['img_url'] : '';
        $img_alt = !empty($instance['img_alt']) ? $instance['img_alt'] : '';
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $title_tag = !empty($instance['title_tag']) ? $instance['title_tag'] : 'h4';
        $text = !empty($instance['text']) ? $instance['text'] : '';
        $btn_text = !empty($instance['btn_text']) ? $instance['btn_text'] : '';
        $btn_url = !empty($instance['btn_url']) ? $instance['btn_url'] : '';
        $btn_class = !empty($instance['btn_class']) ? $instance['btn_class'] : 'btn';

        ?>
        <div class="card" style="width: <?php echo esc_attr($width); ?>;">
            <?php if ($img_url) : ?>
                <img src="<?php echo esc_url($img_url); ?>" class="card-img-top" alt="<?php echo esc_attr($img_alt); ?>">
            <?php endif; ?>
            <?php if ($title || $text || $btn_text) : ?>
            <div class="card-body">
                <?php if ($title) : ?>
                    <<?php echo esc_attr($title_tag); ?> class="card-title"><?php echo esc_html($title); ?></<?php echo esc_attr($title_tag); ?>>
                <?php endif; ?>
                <?php if ($text) : ?>
                    <?php
                    remove_filter('the_content', 'wpautop');
                    $filtered_text = apply_filters('the_content', $text);
                    ?>
                    <div class="card-text"><?php echo do_shortcode($filtered_text); ?></div>
                <?php endif; ?>
                <?php if ($btn_text && $btn_url) : ?>
                    <div class="text-center">
                        <a href="<?php echo esc_url($btn_url); ?>" class="<?php echo esc_attr($btn_class); ?>"><?php echo esc_html($btn_text); ?></a>
                    </div>
                    <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php

        echo $args['after_widget'];
    }

    /**
     * Outputs the settings form for the Bootstrap Card widget.
     *
     * @since 1.0.0
     *
     * @param array $instance Current settings.
     */
    public function form($instance) {
        $width = !empty($instance['width']) ? $instance['width'] : '100%';
        $img_url = !empty($instance['img_url']) ? $instance['img_url'] : '';
        $img_alt = !empty($instance['img_alt']) ? $instance['img_alt'] : '';
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $title_tag = !empty($instance['title_tag']) ? $instance['title_tag'] : 'h4';
        $text = !empty($instance['text']) ? $instance['text'] : '';
        $btn_text = !empty($instance['btn_text']) ? $instance['btn_text'] : '';
        $btn_url = !empty($instance['btn_url']) ? $instance['btn_url'] : '';
        $btn_class = !empty($instance['btn_class']) ? $instance['btn_class'] : 'btn';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Card Width:', 'minimalista'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr($width); ?>">
            <small><?php _e('Use valid CSS width values (e.g., 18rem, 100%, 250px)', 'minimalista'); ?></small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('img_url'); ?>"><?php _e('Image URL:', 'minimalista'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('img_url'); ?>" name="<?php echo $this->get_field_name('img_url'); ?>" type="text" value="<?php echo esc_url($img_url); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('img_alt'); ?>"><?php _e('Image Alt Text:', 'minimalista'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('img_alt'); ?>" name="<?php echo $this->get_field_name('img_alt'); ?>" type="text" value="<?php echo esc_attr($img_alt); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Card Title:', 'minimalista'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('title_tag'); ?>"><?php _e('Card Title Tag:', 'minimalista'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('title_tag'); ?>" name="<?php echo $this->get_field_name('title_tag'); ?>">
                <?php
                $tags = array('h1', 'h2', 'h3', 'h4', 'h5', 'p', 'span');
                foreach ($tags as $tag) {
                    echo '<option value="' . esc_attr($tag) . '"' . selected($title_tag, $tag, false) . '>' . esc_html($tag) . '</option>';
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Card Text:', 'minimalista'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" rows="10"><?php echo esc_textarea($text); ?></textarea>
            <small><?php _e('You can use HTML tags and shortcodes.', 'minimalista'); ?></small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('btn_text'); ?>"><?php _e('Button Text:', 'minimalista'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('btn_text'); ?>" name="<?php echo $this->get_field_name('btn_text'); ?>" type="text" value="<?php echo esc_attr($btn_text); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('btn_url'); ?>"><?php _e('Button URL:', 'minimalista'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('btn_url'); ?>" name="<?php echo $this->get_field_name('btn_url'); ?>" type="text" value="<?php echo esc_url($btn_url); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('btn_class'); ?>"><?php _e('Button Class:', 'minimalista'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('btn_class'); ?>" name="<?php echo $this->get_field_name('btn_class'); ?>" type="text" value="<?php echo esc_attr($btn_class); ?>">
            <small><?php _e('Optional. Use additional Bootstrap button classes (e.g., btn-primary, btn-secondary)', 'minimalista'); ?></small>
        </p>
        <?php
    }

    /**
     * Handles updating the settings for the current Bootstrap Card widget instance.
     *
     * @since 1.0.0
     *
     * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Settings to save or bool false to cancel saving.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['width'] = (!empty($new_instance['width'])) ? sanitize_text_field($new_instance['width']) : '100%';
        $instance['img_url'] = (!empty($new_instance['img_url'])) ? esc_url_raw($new_instance['img_url']) : '';
        $instance['img_alt'] = (!empty($new_instance['img_alt'])) ? sanitize_text_field($new_instance['img_alt']) : '';
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['title_tag'] = (!empty($new_instance['title_tag'])) ? sanitize_text_field($new_instance['title_tag']) : 'h4';
        $instance['text'] = (!empty($new_instance['text'])) ? wp_kses_post($new_instance['text']) : '';
        $instance['btn_text'] = (!empty($new_instance['btn_text'])) ? sanitize_text_field($new_instance['btn_text']) : '';
        $instance['btn_url'] = (!empty($new_instance['btn_url'])) ? esc_url_raw($new_instance['btn_url']) : '';
        $instance['btn_class'] = (!empty($new_instance['btn_class'])) ? sanitize_text_field($new_instance['btn_class']) : 'btn';

        return $instance;
    }
}

<?php
/**
 * Plugin Name: WP Sweepstakes (Vendor Frontend)
 * Description: Sweepstakes CPT + Vendor frontend create/list + Winners table with role restrictions.
 * Version: 1.0.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit;

class WPSW_Sweepstakes_Plugin {
    const CPT = 'sweepstake';
    const NONCE_ACTION = 'wpsw_vendor_sweepstake_save';
    const NONCE_NAME = 'wpsw_nonce';
    const TABLE = 'wpsw_sweepstake_winners';

    public function __construct() {
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        add_action('init', [$this, 'register_cpt']);
        add_action('init', [$this, 'ensure_vendor_role_caps']);

        // Admin meta box (optional for admin editing)
        add_action('add_meta_boxes', [$this, 'add_metabox']);
        add_action('save_post_' . self::CPT, [$this, 'save_metabox'], 10, 2);

        // Shortcodes
        add_shortcode('sweepstake_vendor_form', [$this, 'shortcode_vendor_form']);
        add_shortcode('sweepstake_vendor_list', [$this, 'shortcode_vendor_list']);
        add_shortcode('sweepstake_winners', [$this, 'shortcode_winners']);

        // Security: limit list table / queries for vendors in wp-admin
        add_action('pre_get_posts', [$this, 'restrict_admin_list_for_vendors']);

        // Admin-only: optionally add winner via query (you can build UI later)
        add_action('admin_post_wpsw_add_winner', [$this, 'admin_add_winner']);
    }

    public function activate() {
        $this->create_winners_table();
        $this->ensure_vendor_role_caps();
        $this->register_cpt();
        flush_rewrite_rules();
    }

    public function deactivate() {
        flush_rewrite_rules();
    }

    private function create_winners_table() {
        global $wpdb;
        $table = $wpdb->prefix . self::TABLE;
        $charset_collate = $wpdb->get_charset_collate();

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sql = "CREATE TABLE {$table} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            sweepstake_id BIGINT(20) UNSIGNED NOT NULL,
            winner_user_id BIGINT(20) UNSIGNED NULL,
            winner_name VARCHAR(190) NOT NULL,
            winner_email VARCHAR(190) NULL,
            prize VARCHAR(255) NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY sweepstake_id (sweepstake_id),
            KEY winner_user_id (winner_user_id)
        ) {$charset_collate};";

        dbDelta($sql);
    }

    public function ensure_vendor_role_caps() {
        // Create vendor role if missing
        if (!get_role('vendor')) {
            add_role('vendor', 'Vendor', ['read' => true]);
        }

        // Capabilities for CPT
        $admin = get_role('administrator');
        $vendor = get_role('vendor');
        if (!$admin || !$vendor) return;

        $caps = [
            'read',
            'edit_sweepstakes',
            'edit_sweepstake',
            'edit_sweepstakes',
            'publish_sweepstakes',
            'delete_sweepstake',
            'delete_sweepstakes',
            'read_sweepstake',
            'read_private_sweepstakes',
            'edit_published_sweepstakes',
            'delete_published_sweepstakes',
        ];

        // Vendors: only own posts by default; WP enforces via author checks when "edit_others_*" is not granted.
        $vendor_caps = [
            'read' => true,
            'edit_sweepstake' => true,
            'edit_sweepstakes' => true,
            'publish_sweepstakes' => true,
            'delete_sweepstake' => true,
            'delete_sweepstakes' => true,
            'read_sweepstake' => true,
        ];

        foreach ($vendor_caps as $cap => $grant) {
            $vendor->add_cap($cap, $grant);
        }

        // Admin: full access
        $admin_caps = [
            'edit_sweepstake',
            'edit_sweepstakes',
            'edit_others_sweepstakes',
            'publish_sweepstakes',
            'read_sweepstake',
            'read_private_sweepstakes',
            'delete_sweepstake',
            'delete_sweepstakes',
            'delete_others_sweepstakes',
            'edit_published_sweepstakes',
            'delete_published_sweepstakes',
        ];
        foreach ($admin_caps as $cap) {
            $admin->add_cap($cap);
        }
    }

    public function register_cpt() {
        $labels = [
            'name' => 'Sweepstakes',
            'singular_name' => 'Sweepstake',
            'add_new_item' => 'Add New Sweepstake',
            'edit_item' => 'Edit Sweepstake',
        ];

        register_post_type(self::CPT, [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-tickets',
            'supports' => ['title', 'editor', 'author'],
            'capability_type' => ['sweepstake', 'sweepstakes'],
            'map_meta_cap' => true,
        ]);
    }

    public function add_metabox() {
        add_meta_box(
            'wpsw_sweepstake_meta',
            'Sweepstake Details',
            [$this, 'render_metabox'],
            self::CPT,
            'normal',
            'default'
        );
    }

    public function render_metabox($post) {
        $desc = get_post_meta($post->ID, '_wpsw_description', true);
        $start = get_post_meta($post->ID, '_wpsw_start_date', true);
        $end = get_post_meta($post->ID, '_wpsw_end_date', true);
        $prize = get_post_meta($post->ID, '_wpsw_prize', true);

        wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME);
        ?>
        <p>
            <label><strong>Description</strong></label><br/>
            <textarea name="wpsw_description" style="width:100%;min-height:80px;"><?php echo esc_textarea($desc); ?></textarea>
        </p>
        <p>
            <label><strong>Start Date</strong></label><br/>
            <input type="date" name="wpsw_start_date" value="<?php echo esc_attr($start); ?>"/>
        </p>
        <p>
            <label><strong>End Date</strong></label><br/>
            <input type="date" name="wpsw_end_date" value="<?php echo esc_attr($end); ?>"/>
        </p>
        <p>
            <label><strong>Prize</strong></label><br/>
            <input type="text" name="wpsw_prize" style="width:100%;" value="<?php echo esc_attr($prize); ?>"/>
        </p>
        <?php
    }

    public function save_metabox($post_id, $post) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!isset($_POST[self::NONCE_NAME]) || !wp_verify_nonce($_POST[self::NONCE_NAME], self::NONCE_ACTION)) return;
        if (!current_user_can('edit_post', $post_id)) return;

        $desc = isset($_POST['wpsw_description']) ? sanitize_textarea_field($_POST['wpsw_description']) : '';
        $start = isset($_POST['wpsw_start_date']) ? sanitize_text_field($_POST['wpsw_start_date']) : '';
        $end = isset($_POST['wpsw_end_date']) ? sanitize_text_field($_POST['wpsw_end_date']) : '';
        $prize = isset($_POST['wpsw_prize']) ? sanitize_text_field($_POST['wpsw_prize']) : '';

        update_post_meta($post_id, '_wpsw_description', $desc);
        update_post_meta($post_id, '_wpsw_start_date', $start);
        update_post_meta($post_id, '_wpsw_end_date', $end);
        update_post_meta($post_id, '_wpsw_prize', $prize);
    }

    /** Restrict wp-admin sweepstake list for vendors */
    public function restrict_admin_list_for_vendors($query) {
        if (!is_admin() || !$query->is_main_query()) return;
        if ($query->get('post_type') !== self::CPT) return;

        $user = wp_get_current_user();
        if (in_array('administrator', (array)$user->roles, true)) return;

        if (in_array('vendor', (array)$user->roles, true)) {
            $query->set('author', get_current_user_id());
        }
    }

    /** ---------- Shortcodes ---------- */

    private function require_vendor_or_admin() {
        if (!is_user_logged_in()) return new WP_Error('auth', 'Please login.');
        $user = wp_get_current_user();
        if (in_array('administrator', (array)$user->roles, true)) return true;
        if (in_array('vendor', (array)$user->roles, true)) return true;
        return new WP_Error('role', 'Access denied.');
    }

    public function shortcode_vendor_form($atts) {
        $ok = $this->require_vendor_or_admin();
        if (is_wp_error($ok)) return esc_html($ok->get_error_message());

        $atts = shortcode_atts(['id' => 0], $atts);
        $edit_id = absint($atts['id']);

        // If editing, verify ownership or admin
        if ($edit_id) {
            $post = get_post($edit_id);
            if (!$post || $post->post_type !== self::CPT) return 'Invalid sweepstake.';
            if (!current_user_can('edit_post', $edit_id)) return 'You cannot edit this sweepstake.';
        }

        $msg = '';
        if (!empty($_POST['wpsw_submit'])) {
            $msg = $this->handle_vendor_save($edit_id);
        }

        $title = $edit_id ? get_the_title($edit_id) : '';
        $content = $edit_id ? get_post_field('post_content', $edit_id) : '';
        $desc = $edit_id ? get_post_meta($edit_id, '_wpsw_description', true) : '';
        $start = $edit_id ? get_post_meta($edit_id, '_wpsw_start_date', true) : '';
        $end = $edit_id ? get_post_meta($edit_id, '_wpsw_end_date', true) : '';
        $prize = $edit_id ? get_post_meta($edit_id, '_wpsw_prize', true) : '';

        ob_start();
        if ($msg) echo '<div class="wpsw-message">' . wp_kses_post($msg) . '</div>';
        ?>
        <form method="post">
            <?php wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME); ?>

            <p>
                <label><strong>Title</strong></label><br/>
                <input type="text" name="wpsw_title" style="width:100%;" value="<?php echo esc_attr($title); ?>" required />
            </p>

            <p>
                <label><strong>Main Description</strong></label><br/>
                <textarea name="wpsw_editor" style="width:100%;min-height:120px;" required><?php echo esc_textarea($content); ?></textarea>
                <small>This maps to WordPress editor content.</small>
            </p>

            <p>
                <label><strong>Extra Description (Meta)</strong></label><br/>
                <textarea name="wpsw_description" style="width:100%;min-height:80px;"><?php echo esc_textarea($desc); ?></textarea>
            </p>

            <p>
                <label><strong>Start Date</strong></label><br/>
                <input type="date" name="wpsw_start_date" value="<?php echo esc_attr($start); ?>" required />
            </p>

            <p>
                <label><strong>End Date</strong></label><br/>
                <input type="date" name="wpsw_end_date" value="<?php echo esc_attr($end); ?>" required />
            </p>

            <p>
                <label><strong>Prize</strong></label><br/>
                <input type="text" name="wpsw_prize" style="width:100%;" value="<?php echo esc_attr($prize); ?>" required />
            </p>

            <p>
                <button type="submit" name="wpsw_submit" value="1">
                    <?php echo $edit_id ? 'Update Sweepstake' : 'Create Sweepstake'; ?>
                </button>
            </p>
        </form>
        <?php
        return ob_get_clean();
    }

    private function handle_vendor_save($edit_id = 0) {
        if (!isset($_POST[self::NONCE_NAME]) || !wp_verify_nonce($_POST[self::NONCE_NAME], self::NONCE_ACTION)) {
            return '<span style="color:red;">Security check failed.</span>';
        }

        $title = isset($_POST['wpsw_title']) ? sanitize_text_field($_POST['wpsw_title']) : '';
        $editor = isset($_POST['wpsw_editor']) ? wp_kses_post($_POST['wpsw_editor']) : '';
        $desc = isset($_POST['wpsw_description']) ? sanitize_textarea_field($_POST['wpsw_description']) : '';
        $start = isset($_POST['wpsw_start_date']) ? sanitize_text_field($_POST['wpsw_start_date']) : '';
        $end = isset($_POST['wpsw_end_date']) ? sanitize_text_field($_POST['wpsw_end_date']) : '';
        $prize = isset($_POST['wpsw_prize']) ? sanitize_text_field($_POST['wpsw_prize']) : '';

        if (!$title || !$editor || !$start || !$end || !$prize) {
            return '<span style="color:red;">Please fill all required fields.</span>';
        }

        // Basic date validation
        if (strtotime($end) < strtotime($start)) {
            return '<span style="color:red;">End date cannot be before start date.</span>';
        }

        $postarr = [
            'post_type' => self::CPT,
            'post_title' => $title,
            'post_content' => $editor,
            'post_status' => 'publish',
        ];

        if ($edit_id) {
            if (!current_user_can('edit_post', $edit_id)) {
                return '<span style="color:red;">You cannot edit this sweepstake.</span>';
            }
            $postarr['ID'] = $edit_id;
            $post_id = wp_update_post($postarr, true);
        } else {
            $postarr['post_author'] = get_current_user_id();
            $post_id = wp_insert_post($postarr, true);
        }

        if (is_wp_error($post_id)) {
            return '<span style="color:red;">Error: ' . esc_html($post_id->get_error_message()) . '</span>';
        }

        update_post_meta($post_id, '_wpsw_description', $desc);
        update_post_meta($post_id, '_wpsw_start_date', $start);
        update_post_meta($post_id, '_wpsw_end_date', $end);
        update_post_meta($post_id, '_wpsw_prize', $prize);

        return '<span style="color:green;">Saved successfully. Sweepstake ID: ' . intval($post_id) . '</span>';
    }

    public function shortcode_vendor_list() {
        $ok = $this->require_vendor_or_admin();
        if (is_wp_error($ok)) return esc_html($ok->get_error_message());

        $user = wp_get_current_user();
        $is_admin = in_array('administrator', (array)$user->roles, true);

        $args = [
            'post_type' => self::CPT,
            'post_status' => 'any',
            'posts_per_page' => 50,
            'orderby' => 'date',
            'order' => 'DESC',
        ];
        if (!$is_admin) $args['author'] = get_current_user_id();

        $q = new WP_Query($args);

        ob_start();
        echo '<div class="wpsw-list">';
        if (!$q->have_posts()) {
            echo '<p>No sweepstakes found.</p>';
        } else {
            echo '<table style="width:100%;border-collapse:collapse;" border="1" cellpadding="8">';
            echo '<tr><th>Title</th><th>Start</th><th>End</th><th>Prize</th><th>Actions</th></tr>';

            while ($q->have_posts()) {
                $q->the_post();
                $id = get_the_ID();
                $start = esc_html(get_post_meta($id, '_wpsw_start_date', true));
                $end = esc_html(get_post_meta($id, '_wpsw_end_date', true));
                $prize = esc_html(get_post_meta($id, '_wpsw_prize', true));

                // Edit link: pass id into the form shortcode page (you can place form on same page)
                $edit_url = add_query_arg(['sweepstake_id' => $id], get_permalink());

                echo '<tr>';
                echo '<td>' . esc_html(get_the_title()) . '</td>';
                echo '<td>' . $start . '</td>';
                echo '<td>' . $end . '</td>';
                echo '<td>' . $prize . '</td>';
                echo '<td><a href="' . esc_url($edit_url) . '">Edit</a> | ';
                echo '<a href="' . esc_url(add_query_arg(['sweepstake_id' => $id, 'view_winners' => 1], get_permalink())) . '">Winners</a></td>';
                echo '</tr>';
            }
            echo '</table>';
            wp_reset_postdata();
        }
        echo '</div>';

        // Convenience: if page has ?sweepstake_id=123, render the form automatically
        if (isset($_GET['sweepstake_id']) && absint($_GET['sweepstake_id'])) {
            echo do_shortcode('[sweepstake_vendor_form id="' . absint($_GET['sweepstake_id']) . '"]');
        }

        return ob_get_clean();
    }

    public function shortcode_winners($atts) {
        $ok = $this->require_vendor_or_admin();
        if (is_wp_error($ok)) return esc_html($ok->get_error_message());

        $atts = shortcode_atts([
            'sweepstake_id' => 0,
        ], $atts);

        $requested_id = absint($atts['sweepstake_id']);
        if (!$requested_id && isset($_GET['sweepstake_id'])) {
            $requested_id = absint($_GET['sweepstake_id']);
        }

        $user = wp_get_current_user();
        $is_admin = in_array('administrator', (array)$user->roles, true);
        $vendor_id = get_current_user_id();

        global $wpdb;
        $table = $wpdb->prefix . self::TABLE;

        // Build allowed sweepstake filter
        $where = "1=1";
        $params = [];

        if ($requested_id) {
            // Validate access to sweepstake
            $post = get_post($requested_id);
            if (!$post || $post->post_type !== self::CPT) return 'Invalid sweepstake.';
            if (!$is_admin && intval($post->post_author) !== $vendor_id) return 'Access denied.';

            $where .= " AND sweepstake_id = %d";
            $params[] = $requested_id;
        } else {
            // If no sweepstake_id passed: admin sees all; vendor sees only own sweepstakes
            if (!$is_admin) {
                $ids = get_posts([
                    'post_type' => self::CPT,
                    'posts_per_page' => -1,
                    'fields' => 'ids',
                    'author' => $vendor_id,
                ]);
                $ids = array_map('intval', $ids);
                if (empty($ids)) return 'No winners yet.';
                $where .= " AND sweepstake_id IN (" . implode(',', $ids) . ")";
            }
        }

        $sql = "SELECT * FROM {$table} WHERE {$where} ORDER BY created_at DESC LIMIT 200";
        $rows = !empty($params) ? $wpdb->get_results($wpdb->prepare($sql, $params)) : $wpdb->get_results($sql);

        ob_start();
        if (empty($rows)) {
            echo '<p>No winners found.</p>';
            return ob_get_clean();
        }

        echo '<table style="width:100%;border-collapse:collapse;" border="1" cellpadding="8">';
        echo '<tr><th>Sweepstake</th><th>Winner</th><th>Email</th><th>Prize</th><th>Date</th></tr>';

        foreach ($rows as $r) {
            $title = get_the_title((int)$r->sweepstake_id);
            echo '<tr>';
            echo '<td>' . esc_html($title) . ' (#' . intval($r->sweepstake_id) . ')</td>';
            echo '<td>' . esc_html($r->winner_name) . '</td>';
            echo '<td>' . esc_html($r->winner_email) . '</td>';
            echo '<td>' . esc_html($r->prize) . '</td>';
            echo '<td>' . esc_html($r->created_at) . '</td>';
            echo '</tr>';
        }
        echo '</table>';

        return ob_get_clean();
    }

    /** Admin-only endpoint example to add winner (basic). You can create UI later. */
    public function admin_add_winner() {
        if (!current_user_can('manage_options')) wp_die('Forbidden');

        $sweepstake_id = isset($_POST['sweepstake_id']) ? absint($_POST['sweepstake_id']) : 0;
        $winner_name = isset($_POST['winner_name']) ? sanitize_text_field($_POST['winner_name']) : '';
        $winner_email = isset($_POST['winner_email']) ? sanitize_email($_POST['winner_email']) : '';
        $prize = isset($_POST['prize']) ? sanitize_text_field($_POST['prize']) : '';

        if (!$sweepstake_id || !$winner_name) wp_die('Missing fields');

        global $wpdb;
        $table = $wpdb->prefix . self::TABLE;

        $wpdb->insert($table, [
            'sweepstake_id' => $sweepstake_id,
            'winner_user_id' => 0,
            'winner_name' => $winner_name,
            'winner_email' => $winner_email,
            'prize' => $prize,
            'created_at' => current_time('mysql'),
        ]);

        wp_redirect(admin_url('edit.php?post_type=' . self::CPT));
        exit;
    }
}

new WPSW_Sweepstakes_Plugin();

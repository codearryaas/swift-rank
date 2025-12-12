<?php
/**
 * Setup Wizard Notice
 *
 * @package Swift_Rank
 */

namespace Swift_Rank\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class Wizard_Notice
{
    /**
     * Initialize the notice
     */
    public function __construct()
    {
        add_action('admin_notices', array($this, 'show_wizard_notice'));
        add_action('wp_ajax_swift_rank_dismiss_wizard_notice', array($this, 'dismiss_notice'));
    }

    /**
     * Show wizard notice if incomplete
     */
    public function show_wizard_notice()
    {
        // Don't show on wizard page itself
        if (isset($_GET['page']) && $_GET['page'] === 'swift-rank-setup') {
            return;
        }

        // Check if wizard is complete
        $wizard_state = get_option('swift_rank_wizard_state', array());
        if (!empty($wizard_state['is_complete'])) {
            return;
        }

        // Check if user dismissed notice (temporary)
        if (get_transient('swift_rank_wizard_notice_dismissed')) {
            return;
        }

        // Get current step
        $current_step = isset($wizard_state['current_step']) ? $wizard_state['current_step'] : 1;
        $completed_steps = isset($wizard_state['completed_steps']) ? $wizard_state['completed_steps'] : array();
        $total_steps = 5;
        $progress = count($completed_steps);

        ?>
        <div class="notice notice-info is-dismissible schema-wizard-notice">
            <p>
                <strong><?php _e('Swift Rank Setup', 'swift-rank'); ?></strong> -
                <?php
                if ($progress === 0) {
                    _e('Complete the setup wizard to configure your schema settings.', 'swift-rank');
                } else {
                    printf(
                        __('You\'re %d%% done! Continue setting up Swift Rank.', 'swift-rank'),
                        round(($progress / $total_steps) * 100)
                    );
                }
                ?>
            </p>
            <p>
                <a href="<?php echo admin_url('admin.php?page=swift-rank-setup'); ?>" class="button button-primary">
                    <?php _e('Complete Setup', 'swift-rank'); ?>
                </a>
                <a href="#" class="button button-link schema-wizard-dismiss">
                    <?php _e('Remind me later', 'swift-rank'); ?>
                </a>
            </p>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                $('.schema-wizard-dismiss').on('click', function (e) {
                    e.preventDefault();
                    $.post(ajaxurl, {
                        action: 'swift_rank_dismiss_wizard_notice'
                    });
                    $('.schema-wizard-notice').fadeOut();
                });
            });
        </script>
        <style>
            .schema-wizard-notice {
                border-left-color: #2271b1;
            }

            .schema-wizard-notice p:first-child {
                margin: 0.5em 0;
            }

            .schema-wizard-notice p:last-child {
                margin: 0.5em 0 0 0;
            }

            .schema-wizard-notice .button {
                margin-right: 10px;
            }
        </style>
        <?php
    }

    /**
     * Dismiss notice temporarily
     */
    public function dismiss_notice()
    {
        // Dismiss for 7 days
        set_transient('swift_rank_wizard_notice_dismissed', true, 7 * DAY_IN_SECONDS);
        wp_send_json_success();
    }
}

// Initialize with proper namespace
new \Swift_Rank\Admin\Wizard_Notice();

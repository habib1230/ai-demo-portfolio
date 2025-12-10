<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Elementor_Vertical_Scroll_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'vertical_scroll';
    }

    public function get_title()
    {
        return esc_html__('Vertical Scroll', 'elementor_vertical_scroll');
    }

    public function get_icon()
    {
        return 'eicon-post-list';
    }

    public function get_categories()
    {
        return ['general'];
    }

    public function get_keywords()
    {
        return ['scroll', 'nano', 'banana', 'portfolio'];
    }

    public function get_script_depends()
    {
        return ['gsap-js', 'gsap-scrolltrigger', 'vertical-scroll-widget-js'];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'elementor_vertical_scroll'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'list_title',
            [
                'label' => esc_html__('Title', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Project Name', 'elementor_vertical_scroll'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'list_description',
            [
                'label' => esc_html__('Description', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('Description Text', 'elementor_vertical_scroll'),
            ]
        );

        $repeater->add_control(
            'list_image',
            [
                'label' => esc_html__('Image', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'list_link',
            [
                'label' => esc_html__('Link', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'elementor_vertical_scroll'),
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                    'custom_attributes' => '',
                ],
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => esc_html__('Projects List', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'list_title' => esc_html__('Project 1', 'elementor_vertical_scroll'),
                        'list_description' => esc_html__('Description 1', 'elementor_vertical_scroll'),
                    ],
                    [
                        'list_title' => esc_html__('Project 2', 'elementor_vertical_scroll'),
                        'list_description' => esc_html__('Description 2', 'elementor_vertical_scroll'),
                    ],
                ],
                'title_field' => '{{{ list_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'elementor_vertical_scroll'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label' => esc_html__('Hover Effect Color', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#eaff00',
                'selectors' => [
                    '{{WRAPPER}} .project-card::before' => 'background: radial-gradient(800px circle at var(--mouse-x, 50%) var(--mouse-y, 50%), {{VALUE}} 0%, transparent 40%)',
                    '{{WRAPPER}} .cursor-follower' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <section class="work" id="work">
            <!-- Include Styles Locally for standalone plugin usage -->
            <style>
                .work {
                    padding: 0;
                    overflow: hidden;
                    position: relative;
                }

                .section-header {
                    margin-bottom: 4rem;
                    border-bottom: 1px solid #333;
                    padding-bottom: 1rem;
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-end;
                    padding-left: 4rem;
                    padding-right: 4rem;
                }

                .section-header h2 {
                    font-size: 3rem;
                    font-weight: 400;
                    overflow: hidden;
                }

                .projects-grid {
                    display: flex;
                    flex-wrap: nowrap;
                    gap: 8rem;
                    padding: 0 4rem;
                    width: fit-content;
                    /* Ensure vertical alignment center in the scroll track */
                    align-items: center; 
                    height: 100vh;
                    margin: 0;
                    position: relative;
                    /* Make the grid track full height to allow vertical centering of cards */
                }

                .project-card {
                    display: grid;
                    grid-template-columns: 1.5fr 1fr;
                    gap: 4rem;
                    align-items: center;
                    align-content: center;
                    /* Ensure grid content is centered */
                    position: relative;
                    padding: 3rem;
                    border-radius: 12px;
                    background: rgba(255, 255, 255, 0.02);
                    border: 1px solid rgba(255, 255, 255, 0.05);
                    overflow: hidden;
                    width: 70vw;
                    height: 70vh;
                    /* Enforce fixed height for the card to ensure uniformity */
                    flex-shrink: 0;
                    opacity: 1;
                    transform: none;
                }

                .project-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: radial-gradient(800px circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(234, 255, 0, 0.1), transparent 40%);
                    opacity: 0;
                    transition: opacity 0.5s;
                    pointer-events: none;
                    z-index: 0;
                }

                .project-card:hover::before {
                    opacity: 1;
                }

                .project-image-container,
                .project-info {
                    z-index: 1;
                }

                .project-card:nth-child(even) {
                    grid-template-columns: 1fr 1.5fr;
                    direction: rtl;
                }

                .project-card:nth-child(even) .project-info {
                    direction: ltr;
                    text-align: right;
                }

                .project-image-container {
                    width: 100%;
                    height: 100%;
                    /* Fill the grid cell */
                    max-height: 100%;
                    overflow: hidden;
                    position: relative;
                    border-radius: 4px;
                }

                .project-image {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
                }

                .project-image-container:hover .project-image {
                    transform: scale(1.05);
                }

                .project-info h3 {
                    font-size: 4rem;
                    margin-bottom: 1rem;
                    font-weight: 500;
                }

                .project-info p {
                    font-size: 1.2rem;
                    color: #888;
                }

                @media (max-width: 768px) {
                    .project-card {
                        grid-template-columns: 1fr;
                        width: 90vw;
                        height: auto;
                        /* Allow auto height on mobile */
                    }

                    .project-info h3 {
                        font-size: 2rem;
                    }

                    .project-image-container {
                        height: 40vh;
                    }
                }
            </style>

            <div class="projects-grid">
                <?php foreach ($settings['list'] as $item): ?>
                    <?php
                    $link_html = '';
                    if (!empty($item['list_link']['url'])) {
                        $this->add_link_attributes('link_' . $item['_id'], $item['list_link']);
                        // Wrap card or add link button, for now wrapping card logic roughly
                    }
                    ?>
                    <article class="project-card elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>" data-speed="1.0">
                        <div class="project-image-container">
                            <img src="<?php echo esc_url($item['list_image']['url']); ?>"
                                alt="<?php echo esc_attr($item['list_title']); ?>" class="project-image">
                        </div>
                        <div class="project-info">
                            <h3><?php echo esc_html($item['list_title']); ?></h3>
                            <p><?php echo esc_html($item['list_description']); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Inline Script for Interaction Logic (Simplified for plugin context) -->

        </section>
        <?php
    }
}

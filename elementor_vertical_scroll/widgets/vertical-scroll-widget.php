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
                'label' => esc_html__('Style & Layout', 'elementor_vertical_scroll'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_height',
            [
                'label' => esc_html__('Panel Height', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => ['min' => 200, 'max' => 1000],
                    'vh' => ['min' => 10, 'max' => 100],
                ],
                'default' => ['unit' => 'vh', 'size' => 70],
                'selectors' => [
                    '{{WRAPPER}} .project-card' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_width',
            [
                'label' => esc_html__('Panel Width', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw', '%'],
                'range' => [
                    'px' => ['min' => 200, 'max' => 1000],
                    'vw' => ['min' => 10, 'max' => 100],
                ],
                'default' => ['unit' => 'vw', 'size' => 70],
                'selectors' => [
                    '{{WRAPPER}} .project-card' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'panel_padding',
            [
                'label' => esc_html__('Panel Padding', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .project-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'panel_margin',
            [
                'label' => esc_html__('Panel Margin', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .project-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'alignment_heading',
            [
                'label' => esc_html__('Alignment & Text', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'v_align',
            [
                'label' => esc_html__('Vertical Alignment', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'elementor_vertical_scroll'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'elementor_vertical_scroll'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'elementor_vertical_scroll'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .project-card' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'h_align',
            [
                'label' => esc_html__('Horizontal Alignment', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Left', 'elementor_vertical_scroll'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementor_vertical_scroll'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'end' => [
                        'title' => esc_html__('Right', 'elementor_vertical_scroll'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .project-card' => 'align-items: {{VALUE}}; text-align: {{VALUE}};',
                    // Corrected selector from .project-content to .project-info
                    '{{WRAPPER}} .project-info' => 'text-align: {{VALUE}}; width: 100%;',
                    // width: 100% ensures text-align works inside the flex item
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Text Color', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .project-info h3, {{WRAPPER}} .project-info p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_heading',
            [
                'label' => esc_html__('Overlay & Effects', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => esc_html__('Overlay Color', 'elementor_vertical_scroll'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.4)',
                'selectors' => [
                    '{{WRAPPER}} .project-overlay' => 'background-color: {{VALUE}};',
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
            <style>
                .work {
                    padding: 0;
                    overflow: hidden;
                    position: relative;
                }

                .projects-grid {
                    display: flex;
                    flex-wrap: nowrap;
                    gap: 4rem;
                    /* Standard gap, can be overridden by margins */
                    padding: 0 4rem;
                    width: fit-content;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    position: relative;
                }

                .project-card {
                    display: flex;
                    /* Changed from grid to flex for alignment */
                    flex-direction: column;
                    /* Stack content vertically, control V-align with justify */
                    position: relative;
                    /* W/H set by controls */
                    border-radius: 12px;
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    overflow: hidden;
                    flex-shrink: 0;
                    opacity: 1;
                    transform: none;
                    box-sizing: border-box;
                }

                /* Background Image */
                .project-bg-image {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    /* Ensure full coverage */
                    min-width: 100%;
                    min-height: 100%;
                    z-index: 0;
                    transition: transform 0.8s cubic-bezier(0.2, 1, 0.3, 1);
                }

                .project-card:hover .project-bg-image {
                    transform: scale(1.1) rotate(2deg);
                }

                /* Overlay */
                .project-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 1;
                    transition: background-color 0.3s;
                }

                /* Content */
                .project-info {
                    position: relative;
                    z-index: 2;
                    width: 100%;
                }

                .project-info h3 {
                    font-size: 3rem;
                    margin-bottom: 0.5rem;
                    font-weight: 500;
                    line-height: 1.1;
                }

                .project-info p {
                    font-size: 1rem;
                    opacity: 0.8;
                }

                @media (max-width: 768px) {
                    .project-info h3 {
                        font-size: 2rem;
                    }
                }
            </style>

            <div class="projects-grid">
                <?php foreach ($settings['list'] as $item): ?>
                    <?php
                    $link_attributes = '';
                    if (!empty($item['list_link']['url'])) {
                        $this->add_link_attributes('link_' . $item['_id'], $item['list_link']);
                        ?>
                        <a <?php echo $this->get_render_attribute_string('link_' . $item['_id']); ?>
                            class="project-card elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>">
                        <?php } else { ?>
                            <article class="project-card elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>">
                            <?php } ?>

                            <img src="<?php echo esc_url($item['list_image']['url']); ?>"
                                alt="<?php echo esc_attr($item['list_title']); ?>" class="project-bg-image">
                            <div class="project-overlay"></div>

                            <div class="project-info">
                                <h3><?php echo esc_html($item['list_title']); ?></h3>
                                <p><?php echo esc_html($item['list_description']); ?></p>
                            </div>

                            <?php if (!empty($item['list_link']['url'])) { ?>
                        </a>
                    <?php } else { ?>
                        </article>
                    <?php } ?>
                <?php endforeach; ?>
            </div>

        </section>
        <?php
    }
}

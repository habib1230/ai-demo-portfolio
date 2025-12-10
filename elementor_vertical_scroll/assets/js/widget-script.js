jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/vertical_scroll.default', function($scope, $) {
        const widgetId = $scope.data('id');
        const section = $scope.find('.work');
        const grid = $scope.find('.projects-grid');
        const cards = $scope.find('.project-card');

        // Ensure GSAP is loaded
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            return;
        }

        gsap.registerPlugin(ScrollTrigger);

        // Cleanup function for Elementor editor re-renders
        // (Not strictly necessary for frontend only, but good practice)
        
        // Calculate dimensions based on the widget container, not window, for boxed support
        function calculateScroll() {
            const containerWidth = $scope.width(); // Use widget width
            const gridWidth = grid[0].scrollWidth;
            
            // If content fits, no scroll needed
            if (gridWidth <= containerWidth) return;

            const scrollDistance = gridWidth - containerWidth;

            const scrollTween = gsap.to(grid, {
                x: -scrollDistance,
                ease: "none",
                scrollTrigger: {
                    trigger: section,
                    start: "top top", // When top of section hits top of viewport
                    end: () => "+=" + scrollDistance,
                    pin: true,
                    scrub: 1,
                    invalidateOnRefresh: true,
                    // pinReparent: true, // Only if absolutely necessary for complex DOM, but risky in WP
                    // markers: false 
                }
            });

            // Card Animations
            cards.each(function(i, card) {
                // Fade Up Entry
                gsap.from(card, {
                    y: 100,
                    duration: 1,
                    ease: "power3.out",
                    scrollTrigger: {
                        trigger: card,
                        containerAnimation: scrollTween,
                        start: "left 90%", // Adjusted for safer trigger
                        toggleActions: "play none none reverse"
                    }
                });
            });
        }

        // Initialize
        calculateScroll();
        
        // Recalculate on resize (GSAP handles invalidateOnRefresh, but initial calc helps)
    });
});

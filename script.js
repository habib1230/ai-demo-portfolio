// Initialize Lenis for smooth scrolling
const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), // https://www.desmos.com/calculator/brs54l4xou
    direction: 'vertical',
    gestureDirection: 'vertical',
    smooth: true,
    mouseMultiplier: 1,
    smoothTouch: false,
    touchMultiplier: 2,
});

function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
}

requestAnimationFrame(raf);

// Register ScrollTrigger
gsap.registerPlugin(ScrollTrigger);

// Custom Cursor
const cursor = document.querySelector('.cursor-follower');
const links = document.querySelectorAll('a, button, .project-card');

document.addEventListener('mousemove', (e) => {
    gsap.to(cursor, {
        x: e.clientX,
        y: e.clientY,
        duration: 0.1,
        ease: 'power2.out'
    });
});

links.forEach(link => {
    link.addEventListener('mouseenter', () => {
        cursor.classList.add('cursor-hover');
    });
    link.addEventListener('mouseleave', () => {
        cursor.classList.remove('cursor-hover');
    });
});

// Helper: Split Text into Spans
function splitTextToSpans(selector) {
    const elements = document.querySelectorAll(selector);
    elements.forEach(el => {
        const text = el.textContent;
        el.innerHTML = '';
        text.split('').forEach(char => {
            const span = document.createElement('span');
            span.textContent = char === ' ' ? '\u00A0' : char;
            span.style.display = 'inline-block';
            span.style.opacity = '0';
            span.style.transform = 'translateY(100%)';
            el.appendChild(span);
        });
    });
}



// Apply Split Text
splitTextToSpans('.section-header h2');
splitTextToSpans('.project-info h3');

// Hero Animation (On Load)
const tl = gsap.timeline();

tl.to('.hero-title span', {
    y: 0,
    duration: 1.5,
    stagger: 0.1,
    ease: 'power4.out',
    delay: 0.5
})
.to('.hero-subtitle', {
    y: 0,
    opacity: 1,
    duration: 1,
    ease: 'power3.out'
}, '-=1')
.to('.scroll-indicator', {
    opacity: 1,
    duration: 1
}, '-=0.5');

// Marquee Animation
gsap.to('.marquee-track', {
    scrollTrigger: {
        trigger: '.marquee-section',
        start: 'top bottom',
        end: 'bottom top',
        scrub: 1
    },
    xPercent: 20, // Move Right (Left to Right) on scroll down
    ease: 'none'
});



// Hero Watery/Elastic Grid Effect
const canvas = document.getElementById('hero-canvas');
const ctx = canvas.getContext('2d');
let width, height;
let points = [];
let mouse = { x: -1000, y: -1000 };
const GAP = 40; // Spacing between grid points

class Point {
    constructor(x, y) {
        this.x = x;
        this.y = y;
        this.originX = x;
        this.originY = y;
        this.vx = 0;
        this.vy = 0;
        this.force = 0;
        this.size = 2; // Base size
        this.color = '#eaff00'; // Nano Banana
    }

    update() {
        // Distance from mouse
        const dx = mouse.x - this.x;
        const dy = mouse.y - this.y;
        const dist = Math.sqrt(dx * dx + dy * dy);
        
        // Mouse Repulsion (Water Ripple source)
        const interactionRadius = 200;
        if (dist < interactionRadius) {
            const force = (interactionRadius - dist) / interactionRadius;
            const angle = Math.atan2(dy, dx);
            const push = force * 15; // Power of the push
            
            this.vx -= Math.cos(angle) * push;
            this.vy -= Math.sin(angle) * push;
        }

        // Spring Physics (Return to origin)
        const springs = 0.1; // Stiffness
        const damp = 0.9;   // Friction

        const ax = (this.originX - this.x) * springs;
        const ay = (this.originY - this.y) * springs;

        this.vx += ax;
        this.vy += ay;
        
        this.vx *= damp;
        this.vy *= damp;

        this.x += this.vx;
        this.y += this.vy;
    }

    draw() {
        // Visual flair: Size increases with velocity/displacement
        const speed = Math.abs(this.vx) + Math.abs(this.vy);
        const dynamicSize = this.size + (speed * 0.3);
        const alpha = Math.min(0.1 + (speed * 0.05), 0.8);

        ctx.fillStyle = this.color;
        ctx.globalAlpha = alpha;
        ctx.beginPath();
        // Draw bigger circle for "watery" feel
        ctx.arc(this.x, this.y, dynamicSize, 0, Math.PI * 2);
        ctx.fill();
        ctx.globalAlpha = 1;

        // Optional: Draw lines to neighbors if highly active (simulating surface tension)
        // Kept simple for performance first
    }
}

function initGrid() {
    points = [];
    width = canvas.width = window.innerWidth;
    height = canvas.height = window.innerHeight;
    
    // Create grid covering slightly more than viewport to handle edges
    for (let x = 0; x < width + GAP; x += GAP) {
        for (let y = 0; y < height + GAP; y += GAP) {
            points.push(new Point(x, y));
        }
    }
}

function animateGrid() {
    ctx.clearRect(0, 0, width, height);

    points.forEach(p => {
        p.update();
        p.draw();
    });

    requestAnimationFrame(animateGrid);
}

window.addEventListener('resize', initGrid);
// Mouse tracking
document.addEventListener('mousemove', (e) => {
    mouse.x = e.clientX;
    mouse.y = e.clientY;
});

// Init
if (canvas) {
    initGrid();
    animateGrid();
}


// Horizontal Scroll & Pinning
const projectsGrid = document.querySelector('.projects-grid');
const progressBar = document.querySelector('.work-progress-bar');

function getScrollAmount() {
    return -(projectsGrid.scrollWidth - window.innerWidth);
}

const scrollTween = gsap.to(projectsGrid, {
    x: getScrollAmount,
    ease: "none",
    scrollTrigger: {
        trigger: ".work",
        start: "top top",
        end: () => "+=" + (projectsGrid.scrollWidth - window.innerWidth),
        pin: true,
        scrub: 1,
        invalidateOnRefresh: true
    }
});

// Progress Bar Animation (Linked to same scroll distance)
gsap.to(progressBar, {
    width: "100%",
    ease: "none",
    scrollTrigger: {
        trigger: ".work",
        start: "top top",
        end: () => "+=" + (projectsGrid.scrollWidth - window.innerWidth),
        scrub: 1
    }
});

// Individual Card Entrance (Fade Up)
gsap.utils.toArray('.project-card').forEach((card, i) => {
    // Spotlight Effect (Keep existing logic or minimal version)
    card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        card.style.setProperty('--mouse-x', `${x}px`);
        card.style.setProperty('--mouse-y', `${y}px`);
    });

    gsap.to(card, {
        y: 0,
        opacity: 1,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: {
            trigger: card,
            containerAnimation: scrollTween, // Link to horizontal scroll
            start: "left 120%", // Animate before it fully enters
            toggleActions: "play none none reverse"
        }
    });

    // Staggered Text Reveal inside card (Optional, keeping simple fade up for now as requested)
});

// Chat Widget Scroll Fix (Lenis Compatibility)
function fixChatScroll() {
    // Common selectors for Support Board and similar widgets
    const chatSelectors = ['#sb-main', '.sb-main', '#sb-chat', '.sb-chat', '.sb-container'];
    
    const applyFix = () => {
        chatSelectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(el => {
                if (!el.hasAttribute('data-lenis-prevent')) {
                    el.setAttribute('data-lenis-prevent', 'true');
                    // Also force stop propagation on wheel events just in case
                    el.addEventListener('wheel', (e) => {
                        e.stopPropagation();
                    }, { passive: false });
                }
            });
        });
    };

    // Watch for widget injection
    const observer = new MutationObserver(() => {
        applyFix();
    });

    observer.observe(document.body, { childList: true, subtree: true });
    
    // Initial and delayed checks
    applyFix();
    setTimeout(applyFix, 1000);
    setTimeout(applyFix, 3000);
}

// Initialize fix
if (typeof MutationObserver !== 'undefined') {
    fixChatScroll();
}

gsap.to('.section-header h2 span', {
    scrollTrigger: {
        trigger: '.section-header',
        start: 'top 80%'
    },
    y: 0,
    opacity: 1,
    stagger: 0.02,
    duration: 1,
    ease: 'power4.out'
});

gsap.from('.about-content h2', {

    scrollTrigger: {
        trigger: '.about',
        start: 'top 80%'
    },
    y: 50,
    opacity: 0,
    duration: 1,
    ease: 'power3.out'
});

gsap.from('.skills-list li', {
    scrollTrigger: {
        trigger: '.skills-list',
        start: 'top 85%'
    },
    y: 20,
    opacity: 0,
    stagger: 0.1,
    duration: 0.8,
    ease: 'back.out(1.7)'
});

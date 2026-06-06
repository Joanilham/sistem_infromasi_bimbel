/**
 * Error Page — 3D Interactive Particle System
 * Antigravity-style particle sphere that follows mouse/touch.
 * 
 * Usage: Add <canvas id="interactive-bg" data-theme-color="#HEX"></canvas>
 */
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('interactive-bg');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let width, height;
    let particles = [];

    // Mouse target and current smoothed position
    let target = { x: 0, y: 0 };
    let mouse = { x: 0, y: 0 };

    function resize() {
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width;
        canvas.height = height;
    }
    window.addEventListener('resize', resize);
    resize();

    const themeColor = canvas.dataset.themeColor || '#3B82F6';
    // Antigravity style colors + theme color
    const colors = ['#4285F4', '#EA4335', '#FBBC05', '#34A853', themeColor, themeColor, themeColor];

    // Generate particles in a 3D sphere
    for (let i = 0; i < 300; i++) {
        const theta = Math.random() * 2 * Math.PI;
        const phi = Math.acos((Math.random() * 2) - 1);
        const r = 100 + Math.random() * (Math.min(width, height) * 0.6);

        particles.push({
            x0: r * Math.sin(phi) * Math.cos(theta),
            y0: r * Math.sin(phi) * Math.sin(theta),
            z0: r * Math.cos(phi),
            color: colors[Math.floor(Math.random() * colors.length)],
            size: Math.random() * 2.5 + 1.5,
            baseAlpha: Math.random() * 0.5 + 0.3
        });
    }

    // Mouse interaction
    window.addEventListener('mousemove', (e) => {
        target.x = (e.clientX - width / 2) * 0.002;
        target.y = (e.clientY - height / 2) * 0.002;
    });

    // Touch interaction
    window.addEventListener('touchmove', (e) => {
        if (e.touches.length > 0) {
            target.x = (e.touches[0].clientX - width / 2) * 0.003;
            target.y = (e.touches[0].clientY - height / 2) * 0.003;
        }
    });

    // Base continuous rotation
    let baseAngleX = 0;
    let baseAngleY = 0;

    function animate() {
        requestAnimationFrame(animate);
        ctx.clearRect(0, 0, width, height);

        // Smooth easing towards mouse target
        mouse.x += (target.x - mouse.x) * 0.05;
        mouse.y += (target.y - mouse.y) * 0.05;

        // Add slow continuous spin
        baseAngleX += 0.001;
        baseAngleY += 0.0015;

        // Final angles = base spin + mouse offset
        const angleY = baseAngleY + mouse.x;
        const angleX = baseAngleX + mouse.y;

        const cx = width / 2;
        const cy = height / 2;

        particles.forEach(p => {
            // Rotate X
            let y1 = p.y0 * Math.cos(angleX) - p.z0 * Math.sin(angleX);
            let z1 = p.y0 * Math.sin(angleX) + p.z0 * Math.cos(angleX);

            // Rotate Y
            let x2 = p.x0 * Math.cos(angleY) + z1 * Math.sin(angleY);
            let z2 = -p.x0 * Math.sin(angleY) + z1 * Math.cos(angleY);

            // 3D Projection
            const fov = 800;
            const scale = fov / (fov + z2);
            const x2d = cx + x2 * scale;
            const y2d = cy + y1 * scale;

            // Depth fading
            const depthAlpha = Math.min(1, Math.max(0.1, (z2 + 500) / 1000));

            ctx.beginPath();
            ctx.arc(x2d, y2d, p.size * scale, 0, 2 * Math.PI);
            ctx.fillStyle = p.color;
            ctx.globalAlpha = p.baseAlpha * depthAlpha;
            ctx.fill();
        });
    }
    animate();
});

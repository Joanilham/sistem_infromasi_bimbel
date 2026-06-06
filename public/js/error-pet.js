/**
 * Error Page — Pixel Art Cat (VSCode Mascot style)
 * A cute pixel cat that walks back and forth inside the error card.
 * 
 * Usage: Add <canvas id="pet-canvas"></canvas> inside a .pet-floor container.
 */
document.addEventListener('DOMContentLoaded', () => {
    const petCanvas = document.getElementById('pet-canvas');
    if (!petCanvas) return;

    const card = petCanvas.closest('.error-card');
    if (!card) return;

    const pc = petCanvas.getContext('2d');
    petCanvas.width = card.offsetWidth;
    petCanvas.height = 50;
    const PX = 2; // pixel size

    // Color palette
    const palette = {
        0: 'transparent',
        1: '#E8923E', // orange
        2: '#C07030', // dark orange
        3: '#FFFFFF', // white
        4: '#2D2D2D', // black
        5: '#FFB6C1', // pink (inner ear)
        6: '#FFBF6B', // light orange
        7: '#F5DEB3', // cream (paws)
    };

    // Frame 1: walking pose A
    const frame1 = [
        [0,0,0,6,6,0,0,0,0,6,6,0,0,0],
        [0,0,6,5,6,0,0,0,6,5,6,0,0,0],
        [0,0,6,6,6,6,6,6,6,6,6,0,0,0],
        [0,0,6,4,3,6,6,4,3,6,0,0,0,0],
        [0,0,6,4,4,6,6,4,4,6,0,0,0,0],
        [0,0,0,6,5,6,5,6,0,0,0,0,0,0],
        [0,0,0,6,6,6,6,6,0,0,0,0,0,0],
        [0,6,6,1,1,1,1,1,1,6,0,0,0,0],
        [6,1,1,1,1,1,1,1,1,1,6,0,0,0],
        [6,1,1,6,6,6,6,6,1,1,6,0,0,0],
        [0,6,1,1,1,1,1,1,1,6,2,2,0,0],
        [0,0,6,6,0,0,6,6,0,2,0,2,2,0],
        [0,0,7,7,0,0,7,7,0,0,2,0,2,0],
        [0,0,0,0,0,0,0,0,0,0,0,2,2,0],
    ];

    // Frame 2: walking pose B
    const frame2 = [
        [0,0,0,6,6,0,0,0,0,6,6,0,0,0],
        [0,0,6,5,6,0,0,0,6,5,6,0,0,0],
        [0,0,6,6,6,6,6,6,6,6,6,0,0,0],
        [0,0,6,4,3,6,6,4,3,6,0,0,0,0],
        [0,0,6,4,4,6,6,4,4,6,0,0,0,0],
        [0,0,0,6,5,6,5,6,0,0,0,0,0,0],
        [0,0,0,6,6,6,6,6,0,0,0,0,0,0],
        [0,6,6,1,1,1,1,1,1,6,0,0,0,0],
        [6,1,1,1,1,1,1,1,1,1,6,0,0,0],
        [6,1,1,6,6,6,6,6,1,1,6,0,0,0],
        [0,6,1,1,1,1,1,1,1,6,0,2,2,0],
        [0,6,6,0,0,6,6,0,0,0,2,0,2,0],
        [0,7,7,0,0,7,7,0,0,2,0,0,0,2],
        [0,0,0,0,0,0,0,0,0,0,0,0,2,2],
    ];

    const frames = [frame1, frame2];
    let catX = -40;
    let catDir = 1;
    let catSpeed = 0.8;
    let frameIdx = 0;
    let frameTick = 0;

    function drawPixelCat(x, y, dir, fIdx) {
        const sprite = frames[fIdx];
        pc.save();
        pc.translate(x, y);
        if (dir === -1) {
            pc.scale(-1, 1);
            pc.translate(-sprite[0].length * PX, 0);
        }
        for (let row = 0; row < sprite.length; row++) {
            for (let col = 0; col < sprite[row].length; col++) {
                const c = sprite[row][col];
                if (c === 0) continue;
                pc.fillStyle = palette[c];
                pc.fillRect(col * PX, row * PX, PX, PX);
            }
        }
        pc.restore();
    }

    function animateCat() {
        requestAnimationFrame(animateCat);
        pc.clearRect(0, 0, petCanvas.width, petCanvas.height);

        catX += catSpeed * catDir;
        frameTick++;
        if (frameTick % 12 === 0) frameIdx = (frameIdx + 1) % 2;

        if (catX > petCanvas.width + 10) catDir = -1;
        else if (catX < -40) catDir = 1;

        drawPixelCat(catX, petCanvas.height - frames[0].length * PX - 2, catDir, frameIdx);
    }

    animateCat();

    // Resize canvas on window resize
    window.addEventListener('resize', () => {
        petCanvas.width = card.offsetWidth;
    });
});

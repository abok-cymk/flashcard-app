import Swup from 'https://unpkg.com/swup@4?module';
import { animate } from 'https://cdn.jsdelivr.net/npm/motion@10.18.0/+esm';

const swup = new Swup();

swup.hooks.replace('animation:out:await', async () => {
    const target = document.querySelector('#swup');
    if (target) {
        await animate(
            target, 
            { opacity: [1, 0], x: [0, 15] }, 
            { duration: 0.25 }
        ).finished;
    }
});

swup.hooks.replace('animation:in:await', async () => {
    const target = document.querySelector('#swup');
    if (target) {
        await animate(
            target, 
            { opacity: [0, 1], x: [15, 0] }, 
            { duration: 0.3 }
        ).finished;
    }
});
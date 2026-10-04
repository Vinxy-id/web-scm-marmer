import { chromium } from 'playwright';
import path from 'path';
import fs from 'fs';
import { execSync } from 'child_process';

const ASSETS_DIR = path.resolve('Docs/videos/assets');
const AUDIO_DIR = path.resolve('Docs/videos/audio');

if (!fs.existsSync(ASSETS_DIR)) {
    fs.mkdirSync(ASSETS_DIR, { recursive: true });
}

async function recordIntro() {
    console.log('🎬 Recording 2K 60FPS ESCM Motion Graphic Intro...');
    
    const htmlPath = path.resolve('scripts/assets/intro_motion_graphic.html');
    const fileUrl = 'file:///' + htmlPath.replace(/\\/g, '/');

    const browser = await chromium.launch({
        headless: true,
        args: [
            '--enable-font-antialiasing',
            '--font-render-hinting=medium',
            '--force-color-profile=srgb',
            '--no-sandbox',
            '--window-size=2560,1440'
        ]
    });

    const context = await browser.newContext({
        viewport: { width: 2560, height: 1440 },
        deviceScaleFactor: 1,
        recordVideo: {
            dir: ASSETS_DIR,
            size: { width: 2560, height: 1440 }
        }
    });

    const page = await context.newPage();
    console.log(`🌐 Navigating to intro motion graphic: ${fileUrl}`);
    await page.goto(fileUrl, { waitUntil: 'load' });

    // Wait exactly 5.6 seconds for the complete motion sequence
    await page.waitForTimeout(5600);

    await page.close();
    const video = page.video();
    let rawPath = null;
    if (video) {
        rawPath = await video.path();
    }

    await context.close();
    await browser.close();

    if (rawPath && fs.existsSync(rawPath)) {
        console.log(`🎞️ Raw intro video captured at: ${rawPath}`);
        const introMp4 = path.join(ASSETS_DIR, 'intro_2k.mp4');
        const introSfx = path.join(AUDIO_DIR, 'sfx_intro_swell.wav');

        console.log('⚙️ Encoding Intro Video with Audio Swell via FFmpeg (2K 60FPS)...');
        
        // Encode intro with 60 FPS and audio
        let ffmpegCmd = '';
        if (fs.existsSync(introSfx)) {
            ffmpegCmd = `ffmpeg -y -i "${rawPath}" -i "${introSfx}" -c:v libx264 -preset slow -crf 17 -pix_fmt yuv420p -r 60 -c:a aac -b:a 256k -shortest "${introMp4}"`;
        } else {
            ffmpegCmd = `ffmpeg -y -i "${rawPath}" -c:v libx264 -preset slow -crf 17 -pix_fmt yuv420p -r 60 "${introMp4}"`;
        }

        execSync(ffmpegCmd, { stdio: 'inherit' });
        console.log(`🎉 Intro Motion Graphic Master ready at: ${introMp4}`);

        try { fs.unlinkSync(rawPath); } catch (e) {}
    } else {
        throw new Error('Failed to record intro video');
    }
}

recordIntro().catch(err => {
    console.error('❌ Error recording intro:', err);
    process.exit(1);
});

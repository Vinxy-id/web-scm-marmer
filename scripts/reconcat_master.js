import path from 'path';
import fs from 'fs';
import { execSync } from 'child_process';

const ASSETS_DIR = path.resolve('Docs/videos/assets');
const VIDEO_DIR = path.resolve('Docs/videos');

const introMp4 = path.join(ASSETS_DIR, 'intro_2k.mp4');
const walkthroughMp4 = path.join(ASSETS_DIR, 'walkthrough_2k.mp4');
const outroMp4 = path.join(ASSETS_DIR, 'outro_2k.mp4');

const targetMp4_VO_SFX = path.join(VIDEO_DIR, 'Demo_Sistem_ESCM_Marmer_2K_VO_SFX.mp4');
const targetMp4_2K = path.join(VIDEO_DIR, 'Demo_Sistem_ESCM_Marmer_2K_60FPS.mp4');
const targetMp4_Standard = path.join(VIDEO_DIR, 'Demo_Sistem_ESCM_Marmer.mp4');
const targetWebm = path.join(VIDEO_DIR, 'Demo_Sistem_ESCM_Marmer.webm');

const concatListPath = path.join(ASSETS_DIR, 'concat_list.txt');
let concatContent = '';
if (fs.existsSync(introMp4)) {
    concatContent += `file '${introMp4.replace(/\\/g, '/')}'\n`;
}
concatContent += `file '${walkthroughMp4.replace(/\\/g, '/')}'\n`;
if (fs.existsSync(outroMp4)) {
    concatContent += `file '${outroMp4.replace(/\\/g, '/')}'\n`;
}
fs.writeFileSync(concatListPath, concatContent);

console.log('🎬 Concatenating Intro + Walkthrough + Outro into Master Video...');
console.log(`Concat input list:\n${concatContent}`);

const concatCmd = `ffmpeg -y -f concat -safe 0 -i "${concatListPath}" -c:v libx264 -preset slow -crf 17 -pix_fmt yuv420p -r 60 -c:a aac -b:a 256k "${targetMp4_VO_SFX}"`;
console.log(`Running: ${concatCmd}`);
execSync(concatCmd, { stdio: 'inherit' });
try { fs.unlinkSync(concatListPath); } catch (e) {}

console.log('📋 Copying master to 2K and standard MP4 distributions...');
fs.copyFileSync(targetMp4_VO_SFX, targetMp4_2K);
fs.copyFileSync(targetMp4_VO_SFX, targetMp4_Standard);
console.log(`🎉 Master Video with VO & SFX ready at: ${targetMp4_VO_SFX}`);
console.log(`🎉 2K 60FPS Distribution updated at: ${targetMp4_2K}`);

console.log('🌐 Generating optimized WebM distribution...');
const webmCmd = `ffmpeg -y -i "${targetMp4_VO_SFX}" -c:v libvpx-vp9 -crf 32 -b:v 0 -row-mt 1 -threads 16 -cpu-used 4 -deadline realtime -c:a libopus -b:a 128k "${targetWebm}"`;
try {
    execSync(webmCmd, { stdio: 'inherit' });
    console.log(`🎉 WebM Master ready at: ${targetWebm}`);
} catch (webmErr) {
    console.warn('⚠️ WebM encoding warning:', webmErr.message);
}

console.log('✅ All master videos successfully updated with corrected outro!');

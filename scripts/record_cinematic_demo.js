import { chromium } from 'playwright';
import fs from 'fs';
import path from 'path';
import { execSync } from 'child_process';

const BASE_URL = 'http://127.0.0.1:8000';
const VIDEO_DIR = path.resolve('Docs/videos');
const AUDIO_DIR = path.resolve('Docs/videos/audio');
const ASSETS_DIR = path.resolve('Docs/videos/assets');

if (!fs.existsSync(VIDEO_DIR)) fs.mkdirSync(VIDEO_DIR, { recursive: true });
if (!fs.existsSync(AUDIO_DIR)) fs.mkdirSync(AUDIO_DIR, { recursive: true });
if (!fs.existsSync(ASSETS_DIR)) fs.mkdirSync(ASSETS_DIR, { recursive: true });

// Load audio timings
const timingsPath = path.join(AUDIO_DIR, 'audio_timings.json');
let audioTimings = null;
if (fs.existsSync(timingsPath)) {
    audioTimings = JSON.parse(fs.readFileSync(timingsPath, 'utf-8'));
}

async function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// Global recording timeline tracker
let recordingStartTime = 0;
const timelineEvents = [];

function recordAudioEvent(type, meta = {}) {
    const time = recordingStartTime ? Math.max(0, (Date.now() - recordingStartTime) / 1000) : 0;
    timelineEvents.push({ type, time, ...meta });
    console.log(`   [AUDIO TIMELINE] ${type} at ${time.toFixed(2)}s`);
}

// Injects precision virtual cursor, smooth zoom engine, and lower-third HUD (Optimized for 2K)
async function injectOverlayEngine(page) {
    await page.evaluate(() => {
        if (document.getElementById('scm-virtual-cursor')) return;

        // 1. Precision Virtual Cursor (Scaled for 2K Quad HD)
        const cursor = document.createElement('div');
        cursor.id = 'scm-virtual-cursor';
        cursor.innerHTML = `
            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" style="overflow: visible; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.65));">
                <path d="M0 0 L20 15 L11.5 17 L7.5 26 L0 0 Z" fill="#0284C7" stroke="#FFFFFF" stroke-width="2.5" stroke-linejoin="round"/>
            </svg>
            <div id="scm-cursor-ripple" style="position: absolute; top: -24px; left: -24px; width: 48px; height: 48px; border-radius: 50%; border: 3px solid #38BDF8; opacity: 0; transform: scale(0.3); pointer-events: none; transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.5s ease-out;"></div>
        `;
        cursor.style.cssText = `
            position: fixed;
            top: 280px;
            left: 280px;
            width: 34px;
            height: 34px;
            z-index: 2147483647;
            pointer-events: none;
            transition: top 0.6s cubic-bezier(0.22, 1, 0.36, 1), left 0.6s cubic-bezier(0.22, 1, 0.36, 1);
        `;
        document.documentElement.appendChild(cursor);

        // 2. Lower-Third Glassmorphism Explanatory HUD (Crisp 2K typography & high contrast)
        const hud = document.createElement('div');
        hud.id = 'scm-demo-hud';
        hud.style.cssText = `
            position: fixed;
            bottom: 48px;
            left: 50%;
            transform: translateX(-50%) translateY(40px);
            z-index: 2147483646;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        `;
        hud.innerHTML = `
            <div style="background: rgba(15, 23, 42, 0.96); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 2px solid rgba(56, 189, 248, 0.55); border-radius: 24px; padding: 18px 36px; box-shadow: 0 30px 60px -15px rgba(0,0,0,0.75); display: flex; align-items: center; gap: 22px; max-width: 1100px; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                <div id="scm-hud-badge" style="background: linear-gradient(135deg, #0284C7, #0369A1); color: #FFFFFF; font-size: 13px; font-weight: 800; padding: 8px 18px; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.08em; white-space: nowrap; box-shadow: 0 4px 14px rgba(2,132,199,0.5);">
                    LANGKAH 01 / 13
                </div>
                <div>
                    <div id="scm-hud-title" style="color: #FFFFFF; font-size: 18px; font-weight: 700; letter-spacing: -0.01em;">Judul Fitur</div>
                    <div id="scm-hud-desc" style="color: #CBD5E1; font-size: 14.5px; margin-top: 4px; line-height: 1.45;">Deskripsi penjelasan alur operasional sistem.</div>
                </div>
            </div>
        `;
        document.documentElement.appendChild(hud);

        // Prepare page body for silky smooth camera zoom
        document.body.style.transition = 'transform 0.85s cubic-bezier(0.22, 1, 0.36, 1)';
        document.body.style.willChange = 'transform';
        document.body.style.transformOrigin = 'center center';
    });
}

// Updates caption text on lower-third HUD
async function setCaption(page, badge, title, desc) {
    await page.evaluate(({ badge, title, desc }) => {
        const hud = document.getElementById('scm-demo-hud');
        if (!hud) return;
        document.getElementById('scm-hud-badge').innerText = badge;
        document.getElementById('scm-hud-title').innerText = title;
        document.getElementById('scm-hud-desc').innerText = desc;
        hud.style.opacity = '1';
        hud.style.transform = 'translateX(-50%) translateY(0)';
    }, { badge, title, desc });
}

let curSceneStartTime = 0;

async function startScene(page, sceneId, badge, title, desc) {
    curSceneStartTime = Date.now();
    await setCaption(page, badge, title, desc);
    recordAudioEvent('vo', { scene: sceneId });
}

async function endScene(sceneId) {
    if (!audioTimings || !audioTimings.scenes) return;
    const key = `scene_${sceneId}`;
    const targetDur = audioTimings.scenes[key] ? audioTimings.scenes[key].duration : 8.0;
    const spentSec = (Date.now() - curSceneStartTime) / 1000;
    const remaining = (targetDur + 0.5) - spentSec;
    if (remaining > 0) {
        console.log(`   ⏳ Pacing Scene ${sceneId}: waiting ${remaining.toFixed(2)}s for voiceover narration to complete...`);
        await sleep(Math.round(remaining * 1000));
    }
}

// Smoothly moves virtual cursor to exact center of element and applies gentle zoom
async function pointTo(page, selectorOrCoords, { zoom = 1, pause = 1500, click = false } = {}) {
    let targetX = 1280;
    let targetY = 720;

    if (typeof selectorOrCoords === 'string') {
        if (selectorOrCoords === 'center') {
            targetX = 1280;
            targetY = 720;
        } else {
            try {
                const loc = page.locator(selectorOrCoords).first();
                if (await loc.isVisible({ timeout: 2500 }).catch(() => false)) {
                    await loc.scrollIntoViewIfNeeded().catch(() => {});
                    const rect = await loc.evaluate(el => {
                        const r = el.getBoundingClientRect();
                        const isInput = el.tagName === 'INPUT' || el.tagName === 'TEXTAREA';
                        return {
                            x: isInput ? Math.round(r.left + Math.min(28, r.width / 4)) : Math.round(r.left + r.width / 2),
                            y: Math.round(r.top + r.height / 2)
                        };
                    }).catch(() => null);

                    if (rect && typeof rect.x === 'number') {
                        targetX = rect.x;
                        targetY = rect.y;
                    }
                }
            } catch (e) {}
        }
    } else if (selectorOrCoords && typeof selectorOrCoords.x === 'number') {
        targetX = selectorOrCoords.x;
        targetY = selectorOrCoords.y;
    }

    // Move cursor smoothly to exact viewport coordinates
    await page.evaluate(({ targetX, targetY }) => {
        const cursor = document.getElementById('scm-virtual-cursor');
        if (cursor) {
            cursor.style.left = `${targetX}px`;
            cursor.style.top = `${targetY}px`;
        }
    }, { targetX, targetY });

    await sleep(400);

    if (zoom > 1) {
        recordAudioEvent('sfx_whoosh');
    }

    // Apply scroll-aware zoom origin & subtle camera scale
    await page.evaluate(({ targetX, targetY, zoom, click }) => {
        if (zoom > 1) {
            const scrollX = window.scrollX || window.pageXOffset || 0;
            const scrollY = window.scrollY || window.pageYOffset || 0;
            document.body.style.transformOrigin = `${targetX + scrollX}px ${targetY + scrollY}px`;
            document.body.style.transform = `scale(${zoom})`;
        } else if (zoom === 1) {
            document.body.style.transform = 'scale(1)';
        }

        if (click) {
            const ripple = document.getElementById('scm-cursor-ripple');
            if (ripple) {
                ripple.style.transform = 'scale(2.4)';
                ripple.style.opacity = '1';
                setTimeout(() => {
                    ripple.style.transform = 'scale(0.3)';
                    ripple.style.opacity = '0';
                }, 450);
            }
        }
    }, { targetX, targetY, zoom, click });

    if (click) {
        recordAudioEvent('sfx_click');
    }

    await sleep(pause);
}

// Reset camera zoom back to 1.0 (overview)
async function zoomReset(page, pause = 600) {
    recordAudioEvent('sfx_whoosh');
    await page.evaluate(() => {
        document.body.style.transform = 'scale(1)';
    });
    await sleep(pause);
}

// Simulates instant Midtrans payment success & SPK creation for the exact order
function markOrderPaid(orderNumber) {
    console.log(`💳 Simulating Midtrans payment gateway success for order: ${orderNumber}...`);
    const phpScript = `<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\\\\Contracts\\\\Console\\\\Kernel')->bootstrap();

$o = \\App\\Models\\Order::where('order_number', '${orderNumber}')->first();
if ($o) {
    $spkNumber = \\App\\Services\\CodeGeneratorService::generateSpkNumber();
    $workOrder = \\App\\Models\\WorkOrder::create([
        'spk_number' => $spkNumber,
        'product_id' => $o->product_id,
        'customer_id' => $o->customer_id,
        'target_quantity' => $o->quantity,
        'completed_quantity' => 0,
        'scrap_quantity' => 0,
        'status' => 'scheduled',
        'priority' => ($o->payment_scheme === 'full_100') ? 'high' : 'normal',
        'start_date' => now()->toDateString(),
        'due_date' => now()->addDays(7)->toDateString(),
        'notes' => 'Pesanan E-Commerce: ' . $o->order_number . ' - Pembeli: ' . $o->receiver_name . ' (' . $o->shipping_city . ')',
        'created_by' => 1,
    ]);

    $paidAmount = ($o->payment_scheme === 'dp_50') ? round($o->total_amount * 0.5) : $o->total_amount;

    $o->update([
        'payment_status' => ($o->payment_scheme === 'dp_50') ? 'paid_dp' : 'paid_full',
        'paid_amount' => $paidAmount,
        'order_status' => 'in_production',
        'midtrans_transaction_id' => 'TRX-MIDTRANS-' . strtoupper(substr(md5(time()), 0, 10)),
        'midtrans_payment_type' => 'qris_gopay',
        'midtrans_status' => 'settlement',
        'work_order_id' => $workOrder->id,
    ]);
    echo "SUCCESS: Order " . $o->order_number . " marked as paid_dp with SPK " . $spkNumber . "\\n";
} else {
    echo "ERROR: Order ${orderNumber} not found\\n";
}
`;
    const tempFilePath = path.resolve('temp_pay_order.php');
    fs.writeFileSync(tempFilePath, phpScript);
    try {
        const out = execSync(`php "${tempFilePath}"`).toString();
        console.log(out.trim());
    } finally {
        if (fs.existsSync(tempFilePath)) fs.unlinkSync(tempFilePath);
    }
}

async function recordCinematicDemo() {
    const isHeaded = process.argv.includes('--headed') || process.env.HEADED === 'true';
    console.log(`🎬 Launching High-Definition Cinematic Walkthrough with AI Voice-Over & SaaS SFX (2K Quad HD, Mode: ${isHeaded ? 'HEADED' : 'HEADLESS'})...`);

    const browser = await chromium.launch({
        headless: !isHeaded,
        slowMo: isHeaded ? 350 : 60,
        args: [
            '--enable-font-antialiasing',
            '--font-render-hinting=medium',
            '--force-color-profile=srgb',
            '--disable-gpu-vsync',
            '--no-sandbox',
            '--window-size=2560,1440'
        ]
    });

    const context = await browser.newContext({
        viewport: { width: 2560, height: 1440 },
        deviceScaleFactor: 1,
        recordVideo: {
            dir: VIDEO_DIR,
            size: { width: 2560, height: 1440 }
        }
    });

    const page = await context.newPage();
    recordingStartTime = Date.now();

    try {
        // ==========================================
        // SCENE 1: BERANDA PUBLIK KLASTER IKM
        // ==========================================
        console.log('🎥 Scene 1: Beranda Landing Page (2K)...');
        await page.goto(`${BASE_URL}/`, { waitUntil: 'load' });
        await injectOverlayEngine(page);
        await startScene(
            page,
            1,
            'Langkah 01 / 13',
            'Beranda Publik Klaster IKM Marmer & Onyx Tulungagung',
            'Platform terpadu digitalisasi rantai pasok dan etalase komersial pengrajin marmer Campurdarat.'
        );
        await pointTo(page, { x: 700, y: 480 }, { zoom: 1, pause: 2000 });
        await pointTo(page, 'a[href*="/katalog"], button:has-text("Katalog")', { zoom: 1.15, pause: 2400, click: true });
        await zoomReset(page, 600);
        await endScene(1);

        // ==========================================
        // SCENE 2: KATALOG MULTI-FILTER
        // ==========================================
        console.log('🎥 Scene 2: Katalog & Multi-Filter...');
        await page.goto(`${BASE_URL}/katalog`, { waitUntil: 'load' });
        await injectOverlayEngine(page);
        await startScene(
            page,
            2,
            'Langkah 02 / 13',
            'Katalog Terkurasi & Multi-Filter Toko IKM',
            'Menyaring produk berdasarkan mitra pengrajin (UD Cahaya Onix & UD Putra Abadi) dan kategori batu alam.'
        );
        await pointTo(page, 'input[name="ikm"], label:has-text("Cahaya Onix")', { zoom: 1.2, pause: 2000, click: true });
        await zoomReset(page, 500);
        await pointTo(page, 'a[href*="/katalog/4"], .product-card:first-child', { zoom: 1.18, pause: 2400, click: true });
        await zoomReset(page, 500);
        await endScene(2);

        // ==========================================
        // SCENE 3: DETAIL SPESIFIKASI PRODUK
        // ==========================================
        console.log('🎥 Scene 3: Detail Spesifikasi Produk (Wastafel Marmer)...');
        await page.goto(`${BASE_URL}/katalog/4`, { waitUntil: 'load' });
        await injectOverlayEngine(page);
        await startScene(
            page,
            3,
            'Langkah 03 / 13',
            'Rincian Spesifikasi Teknis & Opsi Pembelian',
            'Menampilkan harga pengrajin langsung (Rp 450.000), dimensi D40 T15, uji kilap Hi-Glossy, dan garansi peti kayu solid.'
        );
        await pointTo(page, 'table, .specifications, dl', { zoom: 1.18, pause: 2600 });
        await pointTo(page, 'a[href*="/checkout"], button:has-text("Beli")', { zoom: 1.22, pause: 2400, click: true });
        await zoomReset(page, 500);
        await endScene(3);

        // ==========================================
        // SCENE 4: CHECKOUT FORM (DP 50% / LUNAS)
        // ==========================================
        console.log('🎥 Scene 4: Formulir Checkout E-Commerce (Wastafel Marmer)...');
        await page.goto(`${BASE_URL}/checkout/4`, { waitUntil: 'load' });
        await injectOverlayEngine(page);
        await startScene(
            page,
            4,
            'Langkah 04 / 13',
            'Checkout Online Fleksibel: DP 50% atau Lunas 100%',
            'Mendukung uang muka (DP 50%) Rp 225.000 untuk pengerjaan bengkel serta metode pembayaran online otomatis.'
        );
        const nameInput = page.locator('input[name="receiver_name"]');
        if (await nameInput.isVisible()) {
            await pointTo(page, 'input[name="receiver_name"]', { zoom: 1.15, pause: 600, click: true });
            await nameInput.fill('Bpk. Hendra Wijaya');
            await page.locator('input[name="receiver_phone"]').fill('081234567890');
            await page.locator('input[name="shipping_city"]').fill('Surabaya');
            
            // Map Search Input & Pinpoint
            const mapSearch = page.locator('#map-search-input');
            if (await mapSearch.isVisible().catch(() => false)) {
                await pointTo(page, '#map-search-input', { zoom: 1.18, pause: 800, click: true });
                await mapSearch.fill('Jl. Dharmahusada Indah No. 45, Surabaya');
            }

            // Fill hidden form coordinate values for submission
            await page.evaluate(() => {
                const addr = document.getElementById('input-shipping-address');
                const lat = document.getElementById('input-latitude');
                const lng = document.getElementById('input-longitude');
                const mapsUrl = document.getElementById('input-maps-url');
                if (addr) addr.value = 'Jl. Dharmahusada Indah No. 45, Mulyorejo, Surabaya, Jawa Timur';
                if (lat) lat.value = '-7.2654';
                if (lng) lng.value = '112.7561';
                if (mapsUrl) mapsUrl.value = 'https://www.google.com/maps?q=-7.2654,112.7561';
            });
            await sleep(600);
        }
        await pointTo(page, 'input[value="dp_50"], label:has-text("DP 50%")', { zoom: 1.22, pause: 1600, click: true });
        await pointTo(page, 'button[type="submit"]:has-text("Konfirmasi & Buat Pesanan")', { zoom: 1.2, pause: 1800, click: true });
        await Promise.all([
            page.waitForURL(url => url.pathname.includes('/order/invoice/'), { timeout: 20000 }),
            page.click('button[type="submit"]:has-text("Konfirmasi & Buat Pesanan")')
        ]);
        await zoomReset(page, 500);
        await endScene(4);

        // Retrieve dynamic order number from redirected invoice URL
        const invoiceUrl = page.url();
        const dynamicOrderNumber = invoiceUrl.split('/').pop().split('?')[0];
        console.log(`✅ Dynamically generated order: ${dynamicOrderNumber}`);

        // ==========================================
        // SCENE 5: FAKTUR TAGIHAN DIGITAL & SNAP
        // ==========================================
        console.log(`🎥 Scene 5: Faktur Tagihan Digital (${dynamicOrderNumber})...`);
        await injectOverlayEngine(page);
        await startScene(
            page,
            5,
            'Langkah 05 / 13',
            'Faktur Tagihan Digital & Payment Gateway Midtrans',
            'Faktur otomatis terbit untuk Wastafel Marmer Putih B1: DP 50% (Rp 225.000) dengan opsi bayar instan QRIS/VA.'
        );
        await pointTo(page, 'span:has-text("TAGIHAN UANG MUKA"), span:has-text("Menunggu")', { zoom: 1.18, pause: 2400 });
        
        const payBtn = page.locator('#pay-button, button:has-text("Bayar Sekarang")');
        if (await payBtn.isVisible()) {
            await pointTo(page, '#pay-button, button:has-text("Bayar Sekarang")', { zoom: 1.25, pause: 1800, click: true });
            await payBtn.click().catch(() => {});
            await sleep(2500);
        }
        await zoomReset(page, 500);
        await endScene(5);

        // ==========================================
        // SCENE 6: VERIFIKASI PEMBAYARAN BERHASIL (PAYMENT GATEWAY SUCCESS)
        // ==========================================
        console.log(`🎥 Scene 6: Pembayaran Berhasil Terverifikasi (${dynamicOrderNumber})...`);
        markOrderPaid(dynamicOrderNumber);

        await page.goto(`${BASE_URL}/order/check-status/${dynamicOrderNumber}`, { waitUntil: 'load' });
        await injectOverlayEngine(page);
        await startScene(
            page,
            6,
            'Langkah 06 / 13',
            'Verifikasi Pembayaran Berhasil (Payment Gateway Success)',
            'Sistem otomatis memverifikasi pembayaran DP 50% (Rp 225.000), menerbitkan SPK bengkel, dan mengaktifkan tombol lacak.'
        );
        recordAudioEvent('sfx_success_chime');
        await pointTo(page, 'div:has-text("Pembayaran Berhasil Terverifikasi!"), .bg-emerald-50', { zoom: 1.18, pause: 3000 });
        await pointTo(page, 'a:has-text("Lacak Progres")', { zoom: 1.22, pause: 2000, click: true });
        await zoomReset(page, 500);
        await endScene(6);

        // ==========================================
        // SCENE 7: LACAK PESANAN REAL-TIME
        // ==========================================
        console.log(`🎥 Scene 7: Pelacakan Pesanan Real-Time (${dynamicOrderNumber})...`);
        await page.goto(`${BASE_URL}/lacak-pesanan?order_number=${dynamicOrderNumber}`, { waitUntil: 'load' });
        await injectOverlayEngine(page);
        await startScene(
            page,
            7,
            'Langkah 07 / 13',
            'Pelacakan Pesanan Real-Time (Live Tracking)',
            'Transparansi 5 tahap: Antrean Bengkel, Produksi Bubut/Poles, QC 2-Tahap, hingga Ekspedisi Kargo.'
        );
        await pointTo(page, '.tracking-milestones, .timeline, h2:has-text("Status")', { zoom: 1.18, pause: 3000 });
        await zoomReset(page, 500);
        await endScene(7);

        // ==========================================
        // SCENE 8: LOGIN ADMIN & RBAC
        // ==========================================
        console.log('🎥 Scene 8: Login Admin & RBAC...');
        await page.goto(`${BASE_URL}/login`, { waitUntil: 'load' });
        await injectOverlayEngine(page);
        await startScene(
            page,
            8,
            'Langkah 08 / 13',
            'Autentikasi Multi-Role RBAC Petugas IKM',
            'Pintu masuk terproteksi untuk 5 peran operasional: Owner, Admin, Gudang, Produksi, dan Distribusi.'
        );
        await pointTo(page, 'input[name="email"]', { zoom: 1.15, pause: 800, click: true });
        await page.fill('input[name="email"]', 'owner@cahayaonix.com');
        await page.fill('input[name="password"]', 'role123');
        await pointTo(page, 'button[type="submit"]', { zoom: 1.22, pause: 1400, click: true });
        await Promise.all([
            page.waitForURL(url => url.pathname.includes('/dashboard'), { timeout: 20000 }),
            page.click('button[type="submit"]')
        ]);
        await zoomReset(page, 500);
        await endScene(8);

        // ==========================================
        // SCENE 9: DASHBOARD & CONTEXTUAL MICRO-TOOLTIPS
        // ==========================================
        console.log('🎥 Scene 9: Dashboard KPI & Micro-Tooltips (2K)...');
        await page.goto(`${BASE_URL}/dashboard`, { waitUntil: 'load' });
        await injectOverlayEngine(page);
        await startScene(
            page,
            9,
            'Langkah 09 / 13',
            'Dashboard SCM: Micro-Tooltips Panduan Tombol',
            'Setiap tombol kritis rantai pasok dilengkapi panduan mengambang untuk mencegah kesalahan operasional staf.'
        );
        recordAudioEvent('sfx_pop');
        await pointTo(page, 'a:has-text("Verifikasi & Buat SPK")', { zoom: 1.18, pause: 2400 });
        recordAudioEvent('sfx_pop');
        await pointTo(page, 'a:has-text("AI Forecast")', { zoom: 1.18, pause: 2400 });
        await zoomReset(page, 500);
        await endScene(9);

        // ==========================================
        // SCENE 10: INTERACTIVE FEATURE TOUR (SPOTLIGHT)
        // ==========================================
        console.log('🎥 Scene 10: Tur Panduan Fitur Interaktif (2K)...');
        await zoomReset(page, 400);
        await startScene(
            page,
            10,
            'Langkah 10 / 13',
            'Tur Fitur Interaktif Dashboard (Spotlight Onboarding)',
            'Panduan ramah pengguna untuk pengrajin marmer Campurdarat: sorotan terarah tanpa blur ke 5 modul utama dashboard.'
        );
        await pointTo(page, '#btn-dashboard-tour', { zoom: 1, pause: 1200, click: true });
        recordAudioEvent('sfx_pop');
        await page.click('#btn-dashboard-tour');
        await sleep(2000);

        // Step 1: Quick Actions
        await pointTo(page, '#scm-tour-btn-next', { zoom: 1, pause: 1400, click: true });
        recordAudioEvent('sfx_pop');
        await page.click('#scm-tour-btn-next');
        await sleep(2000);

        // Step 2: 2-Gate SPK & Stock Alert
        await pointTo(page, '#scm-tour-btn-next', { zoom: 1, pause: 1400, click: true });
        recordAudioEvent('sfx_pop');
        await page.click('#scm-tour-btn-next');
        await sleep(2000);

        // Step 3: 5 KPI Cards
        await pointTo(page, '#scm-tour-btn-next', { zoom: 1, pause: 1400, click: true });
        recordAudioEvent('sfx_pop');
        await page.click('#scm-tour-btn-next');
        await sleep(2000);

        // Step 4: 8-Stage Flow
        await pointTo(page, '#scm-tour-btn-next', { zoom: 1, pause: 1400, click: true });
        recordAudioEvent('sfx_pop');
        await page.click('#scm-tour-btn-next');
        await sleep(2000);

        // Step 5: Charts & Finish Tour
        await pointTo(page, '#scm-tour-btn-next', { zoom: 1, pause: 1400, click: true });
        await page.click('#scm-tour-btn-next');
        await sleep(1500);
        await zoomReset(page, 500);
        await endScene(10);

        // ==========================================
        // SCENE 11: ORDERS MANAGEMENT & 2-GATE SPK
        // ==========================================
        console.log('🎥 Scene 11: Orders Management & 2-Gate SPK (2K)...');
        await page.goto(`${BASE_URL}/orders`, { waitUntil: 'load' });
        await injectOverlayEngine(page);
        await startScene(
            page,
            11,
            'Langkah 11 / 13',
            'Manajemen Pesanan & Mekanisme 2-Gate SPK',
            'Mencegah pesanan palsu mencemari bengkel; dokumen SPK resmi hanya diterbitkan setelah pembayaran tervalidasi.'
        );
        await pointTo(page, 'table tbody tr:first-child', { zoom: 1.15, pause: 2500 });
        await pointTo(page, 'button:has-text("Verifikasi"), a:has-text("SPK")', { zoom: 1.22, pause: 2500 });
        await zoomReset(page, 500);
        await endScene(11);

        // ==========================================
        // SCENE 12: PAPAN KANBAN PENJADWALAN
        // ==========================================
        console.log('🎥 Scene 12: Papan Kanban Produksi (2K)...');
        await page.goto(`${BASE_URL}/production/kanban`, { waitUntil: 'load' });
        await injectOverlayEngine(page);
        await startScene(
            page,
            12,
            'Langkah 12 / 13',
            'Papan Kanban Penjadwalan Produksi Digital',
            'Memantau kartu SPK di 5 stasiun kerja bengkel: Antrean, Potong Blok, Bubut/Pahat, Poles, dan Siap QC.'
        );
        await pointTo(page, '.kanban-column, .grid-cols-5', { zoom: 1.14, pause: 3000 });
        await zoomReset(page, 500);
        await endScene(12);

        // ==========================================
        // SCENE 13: PERAMALAN AI ARIMA(2,0,2)
        // ==========================================
        console.log('🎥 Scene 13: AI Demand Forecasting ARIMA(2,0,2) (2K)...');
        await page.goto(`${BASE_URL}/forecasting`, { waitUntil: 'load' });
        await injectOverlayEngine(page);
        await startScene(
            page,
            13,
            'Langkah 13 / 13',
            'Peramalan Permintaan AI ARIMA(2,0,2)',
            'Model time-series terbaik hasil riset empiris (MAPE 5.73%) untuk memproyeksikan kebutuhan bahan baku 3 bulan ke depan.'
        );
        await pointTo(page, 'canvas, #forecastChart, .chart-container', { zoom: 1.18, pause: 3200 });
        await pointTo(page, 'button:has-text("Hitung"), button:has-text("Forecast")', { zoom: 1.22, pause: 2400 });
        await zoomReset(page, 1000);
        await endScene(13);

        console.log('✅ All 13 High-Definition 2K Scenes Recorded Successfully!');

    } catch (err) {
        console.error('❌ Error during Cinematic Demo recording:', err);
    } finally {
        const totalDurationSec = (Date.now() - recordingStartTime) / 1000;
        console.log(`🎬 Finalizing raw video recording (Total Duration: ${totalDurationSec.toFixed(2)}s)...`);
        
        // Save timeline events
        const eventsPath = path.join(AUDIO_DIR, 'timeline_events.json');
        fs.writeFileSync(eventsPath, JSON.stringify(timelineEvents, null, 4));
        console.log(`📝 Audio timeline events saved to: ${eventsPath}`);

        await page.close();
        const video = page.video();
        let rawVideoPath = null;
        if (video) {
            rawVideoPath = await video.path();
        }
        await context.close();
        await browser.close();

        if (rawVideoPath && fs.existsSync(rawVideoPath)) {
            console.log(`🎞️ Raw video captured at: ${rawVideoPath}`);
            const mixedAudioWav = path.join(AUDIO_DIR, 'master_walkthrough_audio.wav');
            const walkthroughMp4 = path.join(ASSETS_DIR, 'walkthrough_2k.mp4');
            const introMp4 = path.join(ASSETS_DIR, 'intro_2k.mp4');
            
            const targetMp4_VO_SFX = path.join(VIDEO_DIR, 'Demo_Sistem_ESCM_Marmer_2K_VO_SFX.mp4');
            const targetMp4_2K = path.join(VIDEO_DIR, 'Demo_Sistem_ESCM_Marmer_2K_60FPS.mp4');
            const targetMp4_Standard = path.join(VIDEO_DIR, 'Demo_Sistem_ESCM_Marmer.mp4');
            const targetWebm = path.join(VIDEO_DIR, 'Demo_Sistem_ESCM_Marmer.webm');

            // 1. Run Python audio mixing & ducking engine
            console.log('🎛️ Running Python audio mixing & dynamic BGM ducking engine...');
            const mixCmd = `python scripts/mix_demo_audio.py ${totalDurationSec.toFixed(2)}`;
            execSync(mixCmd, { stdio: 'inherit' });

            // 2. Mux raw video with mixed audio into walkthrough_2k.mp4
            console.log('⚙️ Muxing Walkthrough Video with Master VO + SFX + BGM Audio...');
            const muxCmd = `ffmpeg -y -i "${rawVideoPath}" -i "${mixedAudioWav}" -map 0:v -map 1:a -c:v libx264 -preset slow -crf 17 -pix_fmt yuv420p -r 60 -c:a aac -b:a 256k -shortest "${walkthroughMp4}"`;
            console.log(`Running: ${muxCmd}`);
            execSync(muxCmd, { stdio: 'inherit' });

            // 3. Concatenate Intro + Walkthrough + Outro into Master Video
            const outroMp4 = path.join(ASSETS_DIR, 'outro_2k.mp4');
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
            const concatCmd = `ffmpeg -y -f concat -safe 0 -i "${concatListPath}" -c:v libx264 -preset slow -crf 17 -pix_fmt yuv420p -r 60 -c:a aac -b:a 256k "${targetMp4_VO_SFX}"`;
            console.log(`Running: ${concatCmd}`);
            execSync(concatCmd, { stdio: 'inherit' });
            try { fs.unlinkSync(concatListPath); } catch (e) {}

            // 4. Update standard 2K and MP4 distributions
            fs.copyFileSync(targetMp4_VO_SFX, targetMp4_2K);
            fs.copyFileSync(targetMp4_VO_SFX, targetMp4_Standard);
            console.log(`🎉 Master Video with VO & SFX ready at: ${targetMp4_VO_SFX}`);
            console.log(`🎉 2K 60FPS Distribution updated at: ${targetMp4_2K}`);

            // 5. Generate WebM version
            console.log('🌐 Generating optimized WebM distribution...');
            const webmCmd = `ffmpeg -y -i "${targetMp4_VO_SFX}" -c:v libvpx-vp9 -crf 32 -b:v 0 -row-mt 1 -threads 16 -cpu-used 4 -deadline realtime -c:a libopus -b:a 128k "${targetWebm}"`;
            try {
                execSync(webmCmd, { stdio: 'inherit' });
                console.log(`🎉 WebM Master ready at: ${targetWebm}`);
            } catch (webmErr) {
                console.warn('⚠️ WebM encoding warning:', webmErr.message);
            }

            // Clean up raw playwright temp file
            try { fs.unlinkSync(rawVideoPath); } catch (e) {}
        }
    }
}

recordCinematicDemo();

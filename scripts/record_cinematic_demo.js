import { chromium } from 'playwright';
import fs from 'fs';
import path from 'path';

const BASE_URL = 'http://127.0.0.1:8000';
const VIDEO_DIR = path.resolve('Docs/videos');

if (!fs.existsSync(VIDEO_DIR)) {
    fs.mkdirSync(VIDEO_DIR, { recursive: true });
}

async function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// Injects virtual cursor and lower-third explanation HUD into the page
async function injectOverlayEngine(page) {
    await page.evaluate(() => {
        if (document.getElementById('scm-virtual-cursor')) return;

        // 1. Virtual Cursor (Apple-style SVG pointer with blue accent & click ripple)
        const cursor = document.createElement('div');
        cursor.id = 'scm-virtual-cursor';
        cursor.innerHTML = `
            <svg width="34" height="34" viewBox="0 0 32 32" fill="none" style="filter: drop-shadow(0 4px 8px rgba(0,0,0,0.55));">
                <path d="M6 3L24 15L15 18L11 27L6 3Z" fill="#0284C7" stroke="#FFFFFF" stroke-width="2.4" stroke-linejoin="round"/>
            </svg>
            <div id="scm-cursor-ripple" style="position: absolute; top: -6px; left: -6px; width: 44px; height: 44px; border-radius: 50%; border: 3px solid #38BDF8; opacity: 0; transform: scale(0.4); pointer-events: none; transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.4s ease-out;"></div>
        `;
        cursor.style.cssText = `
            position: fixed;
            top: 200px;
            left: 200px;
            width: 34px;
            height: 34px;
            z-index: 2147483647;
            pointer-events: none;
            transition: top 0.5s cubic-bezier(0.25, 1, 0.5, 1), left 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        `;
        document.documentElement.appendChild(cursor);

        // 2. Lower-Third Glassmorphism Explanatory HUD
        const hud = document.createElement('div');
        hud.id = 'scm-demo-hud';
        hud.style.cssText = `
            position: fixed;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%) translateY(30px);
            z-index: 2147483646;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        `;
        hud.innerHTML = `
            <div style="background: rgba(15, 23, 42, 0.94); backdrop-filter: blur(18px); border: 1.5px solid rgba(56, 189, 248, 0.45); border-radius: 20px; padding: 14px 28px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.65); display: flex; align-items: center; gap: 18px; max-width: 860px; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                <div id="scm-hud-badge" style="background: linear-gradient(135deg, #0284C7, #0369A1); color: #FFFFFF; font-size: 11.5px; font-weight: 800; padding: 6px 14px; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.08em; white-space: nowrap; box-shadow: 0 4px 12px rgba(2,132,199,0.45);">
                    LANGKAH 01 / 12
                </div>
                <div>
                    <div id="scm-hud-title" style="color: #FFFFFF; font-size: 15.5px; font-weight: 700; letter-spacing: -0.01em;">Judul Fitur</div>
                    <div id="scm-hud-desc" style="color: #94A3B8; font-size: 12.5px; margin-top: 3px; line-height: 1.45;">Deskripsi penjelasan alur dan kegunaan sistem.</div>
                </div>
            </div>
        `;
        document.documentElement.appendChild(hud);

        // Prepare page body for smooth camera zoom
        document.body.style.transition = 'transform 0.75s cubic-bezier(0.25, 1, 0.5, 1)';
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

// Smoothly moves virtual cursor to selector and applies camera zoom-in or zoom-out
async function pointTo(page, selectorOrCoords, { zoom = 1, pause = 1200, click = false } = {}) {
    let targetX = 960;
    let targetY = 540;

    if (typeof selectorOrCoords === 'string') {
        if (selectorOrCoords === 'center') {
            targetX = 960;
            targetY = 540;
        } else {
            try {
                const loc = page.locator(selectorOrCoords).first();
                if (await loc.isVisible({ timeout: 2500 }).catch(() => false)) {
                    const box = await loc.boundingBox();
                    if (box) {
                        targetX = box.x + Math.min(box.width / 2, 90);
                        targetY = box.y + Math.min(box.height / 2, 45);
                    }
                }
            } catch (e) {}
        }
    } else if (selectorOrCoords && typeof selectorOrCoords.x === 'number') {
        targetX = selectorOrCoords.x;
        targetY = selectorOrCoords.y;
    }

    await page.evaluate(({ targetX, targetY, zoom, click }) => {
        const cursor = document.getElementById('scm-virtual-cursor');
        if (!cursor) return;

        cursor.style.left = `${targetX}px`;
        cursor.style.top = `${targetY}px`;

        if (zoom > 1) {
            document.body.style.transformOrigin = `${targetX}px ${targetY}px`;
            document.body.style.transform = `scale(${zoom})`;
        } else if (zoom === 1) {
            document.body.style.transform = 'scale(1)';
        }

        if (click) {
            const ripple = document.getElementById('scm-cursor-ripple');
            if (ripple) {
                setTimeout(() => {
                    ripple.style.transform = 'scale(2.2)';
                    ripple.style.opacity = '1';
                    setTimeout(() => {
                        ripple.style.transform = 'scale(0.4)';
                        ripple.style.opacity = '0';
                    }, 350);
                }, 400);
            }
        }
    }, { targetX, targetY, zoom, click });

    await sleep(pause);
}

// Reset camera zoom back to 1.0 (overview)
async function zoomReset(page, pause = 800) {
    await page.evaluate(() => {
        document.body.style.transform = 'scale(1)';
    });
    await sleep(pause);
}

async function recordCinematicDemo() {
    const isHeaded = process.argv.includes('--headed') || process.env.HEADED === 'true';
    console.log(`🎬 Launching Cinematic Walkthrough Recording (1920x1080 Full HD, Mode: ${isHeaded ? 'HEADED' : 'HEADLESS'})...`);

    const browser = await chromium.launch({
        headless: !isHeaded,
        slowMo: isHeaded ? 350 : 80,
    });

    const context = await browser.newContext({
        viewport: { width: 1920, height: 1080 },
        recordVideo: {
            dir: VIDEO_DIR,
            size: { width: 1920, height: 1080 }
        }
    });

    const page = await context.newPage();

    try {
        // ==========================================
        // SCENE 1: BERANDA PUBLIK KLASTER IKM
        // ==========================================
        console.log('🎥 Scene 1: Beranda Landing Page...');
        await page.goto(`${BASE_URL}/`, { waitUntil: 'networkidle' });
        await injectOverlayEngine(page);
        await setCaption(
            page,
            'Langkah 01 / 12',
            'Beranda Publik Klaster IKM Marmer & Onyx Tulungagung',
            'Platform terpadu digitalisasi rantai pasok dan etalase komersial untuk pengrajin Campurdarat.'
        );
        await pointTo(page, { x: 400, y: 350 }, { zoom: 1, pause: 1500 });
        // Cursor points to "Katalog Produk" navigation
        await pointTo(page, 'a[href*="/katalog"], button:has-text("Katalog")', { zoom: 1.2, pause: 1800, click: true });
        await zoomReset(page, 600);

        // ==========================================
        // SCENE 2: KATALOG MULTI-FILTER
        // ==========================================
        console.log('🎥 Scene 2: Katalog & Multi-Filter...');
        await page.goto(`${BASE_URL}/katalog`, { waitUntil: 'networkidle' });
        await injectOverlayEngine(page);
        await setCaption(
            page,
            'Langkah 02 / 12',
            'Katalog Terkurasi & Multi-Filter Toko IKM',
            'Menyaring produk berdasarkan mitra pengrajin (UD Cahaya Onix / UD Putra Abadi) dan jenis bahan batuan alam.'
        );
        // Point to filters sidebar with zoom
        await pointTo(page, 'aside, input[name="ikm"], label:has-text("Cahaya Onix")', { zoom: 1.25, pause: 1800, click: true });
        await zoomReset(page, 600);
        // Point to first product card
        await pointTo(page, 'a[href*="/katalog/"], .product-card', { zoom: 1.25, pause: 2000, click: true });
        await zoomReset(page, 600);

        // ==========================================
        // SCENE 3: DETAIL SPESIFIKASI PRODUK
        // ==========================================
        console.log('🎥 Scene 3: Detail Spesifikasi Produk...');
        await page.goto(`${BASE_URL}/katalog/4`, { waitUntil: 'networkidle' });
        await injectOverlayEngine(page);
        await setCaption(
            page,
            'Langkah 03 / 12',
            'Rincian Spesifikasi Teknis & Opsi Pembelian',
            'Menampilkan harga pengrajin langsung, ukuran fisik (D40 T15), uji kilap Hi-Glossy, dan stok siap kirim.'
        );
        // Zoom into specifications table
        await pointTo(page, 'table, dl, .specs', { zoom: 1.25, pause: 2000 });
        // Move to "Beli / Checkout Online" button with zoom
        await pointTo(page, 'a[href*="/checkout"], button:has-text("Beli")', { zoom: 1.35, pause: 1800, click: true });
        await zoomReset(page, 600);

        // ==========================================
        // SCENE 4: CHECKOUT FORM (DP 50% / LUNAS)
        // ==========================================
        console.log('🎥 Scene 4: Formulir Checkout E-Commerce...');
        await page.goto(`${BASE_URL}/checkout/4`, { waitUntil: 'networkidle' });
        await injectOverlayEngine(page);
        await setCaption(
            page,
            'Langkah 04 / 12',
            'Checkout Online Fleksibel: DP 50% atau Lunas',
            'Mendukung pembayaran uang muka (DP 50%) untuk pesanan custom serta payment gateway Midtrans otomatis.'
        );
        // Fill sample info smoothly
        const nameInput = page.locator('input[name="receiver_name"]');
        if (await nameInput.isVisible()) {
            await pointTo(page, 'input[name="receiver_name"]', { zoom: 1.2, pause: 600 });
            await nameInput.fill('Bpk. Hendra Wijaya');
            await page.locator('input[name="receiver_phone"]').fill('081234567890');
            await page.locator('input[name="shipping_city"]').fill('Surabaya');
            await page.locator('textarea[name="shipping_address"]').fill('Jl. Dharmahusada Indah No. 45, Surabaya');
            await sleep(1000);
        }
        // Focus on payment scheme radio
        await pointTo(page, 'input[value="dp"], label:has-text("DP 50%")', { zoom: 1.28, pause: 1500, click: true });
        await zoomReset(page, 800);

        // ==========================================
        // SCENE 5: FAKTUR TAGIHAN DIGITAL & SNAP
        // ==========================================
        console.log('🎥 Scene 5: Faktur Tagihan Digital & Snap...');
        await page.goto(`${BASE_URL}/order/invoice/ORD-20260917-BXGV`, { waitUntil: 'networkidle' });
        await injectOverlayEngine(page);
        await setCaption(
            page,
            'Langkah 05 / 12',
            'Faktur Tagihan Digital & Fitur Anti-Kehilangan Tagihan',
            'Dilengkapi QR Code, nomor invoice resmi, serta penyimpanan otomatis di local storage browser pembeli.'
        );
        // Point to invoice header & status badge
        await pointTo(page, 'span:has-text("Menunggu Pembayaran"), .badge', { zoom: 1.25, pause: 1600 });
        // Point to "Bayar Sekarang" button and click
        const payBtn = page.locator('#pay-button, button:has-text("Bayar Sekarang")');
        if (await payBtn.isVisible()) {
            await pointTo(page, '#pay-button, button:has-text("Bayar Sekarang")', { zoom: 1.35, pause: 1500, click: true });
            await sleep(2500);
            // Snap modal iframe focus if present
            await setCaption(
                page,
                'Langkah 06 / 12',
                'Integrasi Pembayaran Digital Midtrans Snap',
                'Transaksi seketika melalui QRIS Nasional (GoPay/ShopeePay/DANA/OVO) atau Virtual Account Bank resmi.'
            );
            await sleep(3000);
        }
        await zoomReset(page, 800);

        // ==========================================
        // SCENE 7: LACAK PESANAN REAL-TIME
        // ==========================================
        console.log('🎥 Scene 7: Pelacakan Pesanan Real-Time...');
        await page.goto(`${BASE_URL}/lacak-pesanan?search=ORD-20260917-QYN1`, { waitUntil: 'networkidle' });
        await injectOverlayEngine(page);
        await setCaption(
            page,
            'Langkah 07 / 12',
            'Pelacakan Pesanan Real-Time (Live Tracking)',
            'Transparansi progres pengerjaan: Antrean, Produksi di Bengkel, QC 2-Tahap, hingga Ekspedisi Kargo.'
        );
        // Trace the 5-step milestone bar
        await pointTo(page, '.tracking-milestones, .timeline, h2:has-text("Status")', { zoom: 1.2, pause: 2500 });
        await zoomReset(page, 600);

        // ==========================================
        // SCENE 8: LOGIN ADMIN & RBAC
        // ==========================================
        console.log('🎥 Scene 8: Login Admin & RBAC...');
        await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
        await injectOverlayEngine(page);
        await setCaption(
            page,
            'Langkah 08 / 12',
            'Autentikasi Multi-Role RBAC Petugas IKM',
            'Pintu masuk terproteksi untuk 5 peran operasional: Owner, Admin, Gudang, Produksi, dan Distribusi.'
        );
        await pointTo(page, 'input[name="email"]', { zoom: 1.2, pause: 600 });
        await page.fill('input[name="email"]', 'owner@cahayaonix.com');
        await page.fill('input[name="password"]', 'role123');
        await pointTo(page, 'button[type="submit"]', { zoom: 1.25, pause: 1000, click: true });
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle' }),
            page.click('button[type="submit"]')
        ]);
        await zoomReset(page, 600);

        // ==========================================
        // SCENE 9: DASHBOARD EKSEKUTIF KPI
        // ==========================================
        console.log('🎥 Scene 9: Dashboard KPI Eksekutif...');
        await page.goto(`${BASE_URL}/dashboard`, { waitUntil: 'networkidle' });
        await injectOverlayEngine(page);
        await setCaption(
            page,
            'Langkah 09 / 12',
            'Dashboard KPI Operasional & Tren Bisnis',
            'Visualisasi nilai persediaan stok batu, SPK aktif, rasio kelulusan QC, serta grafik tren penjualan bulanan.'
        );
        // Point to KPI cards with zoom
        await pointTo(page, '.grid-cols-4, .kpi-cards, .grid', { zoom: 1.22, pause: 2200 });
        // Point to Alert order baru
        await pointTo(page, '.bg-amber-50, .alert, canvas', { zoom: 1.25, pause: 2200 });
        await zoomReset(page, 600);

        // ==========================================
        // SCENE 10: ORDERS MANAGEMENT & 2-GATE SPK
        // ==========================================
        console.log('🎥 Scene 10: Orders Management & 2-Gate SPK...');
        await page.goto(`${BASE_URL}/orders`, { waitUntil: 'networkidle' });
        await injectOverlayEngine(page);
        await setCaption(
            page,
            'Langkah 10 / 12',
            'Manajemen Pesanan & Mekanisme 2-Gate SPK',
            'Mencegah pesanan palsu mencemari bengkel; dokumen SPK resmi hanya diterbitkan setelah pembayaran tervalidasi.'
        );
        await pointTo(page, 'table tbody tr:first-child', { zoom: 1.2, pause: 2200 });
        await pointTo(page, 'button:has-text("Verifikasi"), a:has-text("SPK")', { zoom: 1.35, pause: 2000 });
        await zoomReset(page, 600);

        // ==========================================
        // SCENE 11: PAPAN KANBAN PENJADWALAN
        // ==========================================
        console.log('🎥 Scene 11: Papan Kanban Produksi...');
        await page.goto(`${BASE_URL}/production/kanban`, { waitUntil: 'networkidle' });
        await injectOverlayEngine(page);
        await setCaption(
            page,
            'Langkah 11 / 12',
            'Papan Kanban Penjadwalan Produksi Digital',
            'Memantau kartu SPK di 5 stasiun kerja bengkel: Antrean, Potong Blok, Bubut/Pahat, Poles, dan Siap QC.'
        );
        // Glide cursor across columns with zoom
        await pointTo(page, '.kanban-column, .grid-cols-5', { zoom: 1.18, pause: 2500 });
        await zoomReset(page, 600);

        // ==========================================
        // SCENE 12: PERAMALAN AI ARIMA(2,0,2)
        // ==========================================
        console.log('🎥 Scene 12: AI Demand Forecasting ARIMA(2,0,2)...');
        await page.goto(`${BASE_URL}/forecasting`, { waitUntil: 'networkidle' });
        await injectOverlayEngine(page);
        await setCaption(
            page,
            'Langkah 12 / 12',
            'Peramalan Permintaan AI ARIMA(2,0,2)',
            'Model deret waktu terbaik hasil riset empiris (MAPE 5.73%) untuk memproyeksikan kebutuhan bahan baku 3 bulan ke depan.'
        );
        await pointTo(page, 'canvas, #forecastChart, .chart-container', { zoom: 1.25, pause: 2600 });
        await pointTo(page, 'button:has-text("Hitung"), button:has-text("Forecast")', { zoom: 1.3, pause: 1800 });
        await zoomReset(page, 1000);

        console.log('✅ All 12 Cinematic Scenes Recorded with Animated Cursor & Zoom!');

    } catch (err) {
        console.error('❌ Error during Cinematic Demo recording:', err);
    } finally {
        console.log('🎬 Finalizing and compressing video file...');
        await page.close();
        const video = page.video();
        let videoPath = null;
        if (video) {
            videoPath = await video.path();
        }
        await context.close();
        await browser.close();

        if (videoPath && fs.existsSync(videoPath)) {
            const targetVideoPath = path.join(VIDEO_DIR, 'Demo_Sistem_ESCM_Marmer.webm');
            if (fs.existsSync(targetVideoPath)) {
                try { fs.unlinkSync(targetVideoPath); } catch (e) {}
            }
            fs.renameSync(videoPath, targetVideoPath);
            console.log(`🎉 Final Video saved successfully to: ${targetVideoPath}`);
        }
    }
}

recordCinematicDemo();

import { chromium } from 'playwright';
import fs from 'fs';
import path from 'path';

const BASE_URL = 'http://127.0.0.1:8000';
const PNG_DIR = path.resolve('Docs/laporan_kegiatan/PNG');
const VIDEO_DIR = path.resolve('Docs/videos');

if (!fs.existsSync(PNG_DIR)) {
    fs.mkdirSync(PNG_DIR, { recursive: true });
}
if (!fs.existsSync(VIDEO_DIR)) {
    fs.mkdirSync(VIDEO_DIR, { recursive: true });
}

async function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function run() {
    const isHeaded = process.argv.includes('--headed') || process.env.HEADED === 'true';
    console.log(`🚀 Starting Playwright Automation for E-SCM Marmer (Mode: ${isHeaded ? 'HEADED / RECORDLY' : 'HEADLESS'})...`);
    const browser = await chromium.launch({
        headless: !isHeaded,
        slowMo: isHeaded ? 400 : 100,
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
        // --- 1. BERANDA (LANDING PAGE) ---
        console.log('📸 1. Navigating to Landing Page...');
        await page.goto(`${BASE_URL}/`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_01_beranda_landing_page.png') });

        // Scroll down slightly to showcase featured products in video
        await page.evaluate(() => window.scrollBy({ top: 500, behavior: 'smooth' }));
        await sleep(1500);
        await page.evaluate(() => window.scrollBy({ top: -500, behavior: 'smooth' }));
        await sleep(1000);

        // --- 2. KATALOG PRODUK MULTI-FILTER ---
        console.log('📸 2. Navigating to Public Catalog...');
        await page.goto(`${BASE_URL}/katalog`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_02_katalog_multi_filter.png') });

        // --- 3. DETAIL PRODUK ---
        console.log('📸 3. Navigating to Product Detail (ID: 4)...');
        await page.goto(`${BASE_URL}/katalog/4`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_03_detail_produk.png') });

        // --- 4. CHECKOUT FORM ---
        console.log('📸 4. Navigating to Checkout Form (Product 4)...');
        await page.goto(`${BASE_URL}/checkout/4`, { waitUntil: 'networkidle' });
        await sleep(1500);
        // Fill sample info for demo realism
        const nameInput = page.locator('input[name="receiver_name"]');
        if (await nameInput.isVisible()) {
            await nameInput.fill('Bpk. Hendra Wijaya');
            await page.locator('input[name="receiver_phone"]').fill('081234567890');
            await page.locator('input[name="shipping_city"]').fill('Surabaya');
            await page.locator('textarea[name="shipping_address"]').fill('Jl. Dharmahusada Indah No. 45, Gubeng, Surabaya');
            await sleep(1000);
        }
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_04_checkout_form.png') });

        // --- 5. DIGITAL INVOICE & SNAP ---
        console.log('📸 5. Navigating to Digital Invoice...');
        await page.goto(`${BASE_URL}/order/invoice/ORD-20260917-BXGV`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_05_invoice_digital.png') });

        // Attempt to trigger Midtrans Snap modal if button exists
        console.log('📸 6. Triggering Midtrans Snap Payment modal...');
        const payBtn = page.locator('#pay-button, button:has-text("Bayar Sekarang")');
        let snapCaptured = false;
        if (await payBtn.isVisible()) {
            await payBtn.click();
            await sleep(2500);
            // Check if iframe or modal is present
            const snapIframe = page.locator('iframe#snap-midtrans');
            if (await snapIframe.isVisible({ timeout: 4000 }).catch(() => false)) {
                await page.screenshot({ path: path.join(PNG_DIR, 'manual_06_midtrans_snap.png') });
                snapCaptured = true;
            }
        }
        if (!snapCaptured) {
            // Fallback screenshot of the invoice payment section
            await page.screenshot({ path: path.join(PNG_DIR, 'manual_06_midtrans_snap.png') });
        }
        await sleep(1500);

        // --- 7. LACAK PESANAN REAL-TIME ---
        console.log('📸 7. Navigating to Live Tracking (/lacak-pesanan)...');
        await page.goto(`${BASE_URL}/lacak-pesanan?search=ORD-20260917-QYN1`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_07_lacak_pesanan.png') });

        // --- 8. LOGIN ADMIN / OWNER ---
        console.log('📸 8. Navigating to Admin Login...');
        await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
        await sleep(1200);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_08_login_admin.png') });

        console.log('🔑 Performing Login as Owner...');
        await page.fill('input[name="email"]', 'owner@cahayaonix.com');
        await page.fill('input[name="password"]', 'role123');
        await sleep(800);
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle' }),
            page.click('button[type="submit"]')
        ]);
        await sleep(1500);

        // --- 9. EXECUTIVE KPI DASHBOARD ---
        console.log('📸 9. Dashboard KPI...');
        await page.goto(`${BASE_URL}/dashboard`, { waitUntil: 'networkidle' });
        await sleep(2000); // Allow Chart.js animations to complete
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_09_dashboard_kpi.png') });

        // --- 10. ORDER MANAGEMENT (2-GATE SPK) ---
        console.log('📸 10. Orders Management...');
        await page.goto(`${BASE_URL}/orders`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_10_orders_management.png') });

        // --- 11. RAW MATERIALS & STOCK MUTATION ---
        console.log('📸 11. Raw Materials Inventory...');
        await page.goto(`${BASE_URL}/materials`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_11_materials_stock.png') });

        // --- 12. PRODUCTION KANBAN BOARD ---
        console.log('📸 12. Production Kanban Board...');
        await page.goto(`${BASE_URL}/production/kanban`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_12_kanban_board.png') });

        // --- 13. WIP MONITORING ---
        console.log('📸 13. Production WIP Tracking...');
        await page.goto(`${BASE_URL}/production/wip`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_13_wip_tracking.png') });

        // --- 14. QUALITY CONTROL (2-STAGE INSPECTION) ---
        console.log('📸 14. Quality Control...');
        await page.goto(`${BASE_URL}/qc`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_14_qc_inspection.png') });

        // --- 15. WASTE CONTROL & RESIDUE LOGS ---
        console.log('📸 15. Industrial Waste Logs...');
        await page.goto(`${BASE_URL}/waste`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_15_waste_logs.png') });

        // --- 16. DISTRIBUTION & WOODEN CRATE PACKING ---
        console.log('📸 16. Distribution & Packing Checklist...');
        await page.goto(`${BASE_URL}/distribution`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_16_distribution_packing.png') });

        // --- 17. AI FORECASTING ARIMA(2,0,2) ---
        console.log('📸 17. AI Demand Forecasting...');
        await page.goto(`${BASE_URL}/forecasting`, { waitUntil: 'networkidle' });
        await sleep(2000);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_17_forecasting_arima.png') });

        // --- 18. END-TO-END SUPPLY CHAIN FLOW ---
        console.log('📸 18. Supply Chain Flow...');
        await page.goto(`${BASE_URL}/supply-chain-flow`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_18_supply_chain_flow.png') });

        // --- 19. MASTER PRODUK ---
        console.log('📸 19. Master Produk...');
        await page.goto(`${BASE_URL}/products`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_19_master_produk.png') });

        // --- 20. USER MANAGEMENT & RBAC ---
        console.log('📸 20. User Management & RBAC...');
        await page.goto(`${BASE_URL}/users`, { waitUntil: 'networkidle' });
        await sleep(1500);
        await page.screenshot({ path: path.join(PNG_DIR, 'manual_20_user_management.png') });

        console.log('✅ All 20 Screenshots Captured Successfully!');

    } catch (err) {
        console.error('❌ Error during Playwright execution:', err);
    } finally {
        // Close page and context to finalize video recording
        console.log('🎬 Finalizing video recording...');
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
            console.log(`🎥 Video saved successfully to: ${targetVideoPath}`);
        } else {
            console.log('⚠️ Video file was not found or already moved.');
        }
    }
}

run();

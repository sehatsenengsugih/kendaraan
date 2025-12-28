const { chromium } = require('playwright');

const BASE_URL = 'http://localhost:8000';

async function login(page) {
    await page.goto(`${BASE_URL}/login`);
    await page.waitForLoadState('networkidle');
    await page.fill('input[name="email"]', 'admin@kas.or.id');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/dashboard', { timeout: 10000 });
    console.log('  ✓ Logged in');
}

async function testDashboardCalendarWidget(page) {
    console.log('\n=== Test: Dashboard Calendar Widget ===');

    await page.goto(`${BASE_URL}/dashboard`);
    await page.waitForLoadState('networkidle');

    // Check widget title
    const widgetTitle = await page.locator('h3:has-text("Jadwal Mendatang")').count();
    if (widgetTitle > 0) {
        console.log('  ✓ Widget title "Jadwal Mendatang" found');
    } else {
        console.log('  ✗ Widget title not found');
        return false;
    }

    // Check "Lihat Kalender" link
    const calendarLink = await page.locator('a:has-text("Lihat Kalender")').count();
    if (calendarLink > 0) {
        console.log('  ✓ "Lihat Kalender" link found');
    } else {
        console.log('  ✗ "Lihat Kalender" link not found');
    }

    // Check legend
    const legendPajakTerlambat = await page.locator('text=Pajak Terlambat').count();
    const legendServis = await page.locator('div.flex.flex-wrap.gap-4 >> text=Servis').count();
    if (legendPajakTerlambat > 0 && legendServis > 0) {
        console.log('  ✓ Widget legend found');
    } else {
        console.log('  ✗ Widget legend not complete');
    }

    // Check if events are displayed or empty message is shown
    const eventItems = await page.locator('.space-y-3 > a.block').count();
    const emptyMessage = await page.locator('text=Tidak ada jadwal dalam 14 hari ke depan').count();

    if (eventItems > 0) {
        console.log(`  ✓ Found ${eventItems} upcoming event(s) in widget`);

        // Check first event has required elements
        const firstEvent = page.locator('.space-y-3 > a.block').first();
        const hasIcon = await firstEvent.locator('svg').count();
        const hasDate = await firstEvent.locator('.text-xs.font-medium').count();

        if (hasIcon > 0 && hasDate > 0) {
            console.log('  ✓ Event items have proper structure (icon, date)');
        }
    } else if (emptyMessage > 0) {
        console.log('  ✓ Empty state message displayed (no upcoming events)');
    } else {
        console.log('  ✗ Neither events nor empty message found');
        return false;
    }

    // Test clicking "Lihat Kalender" link
    await page.click('a:has-text("Lihat Kalender")');
    await page.waitForLoadState('networkidle');

    const onCalendarPage = page.url().includes('/kalender');
    if (onCalendarPage) {
        console.log('  ✓ "Lihat Kalender" link navigates to calendar page');
    } else {
        console.log('  ✗ Navigation to calendar page failed');
        return false;
    }

    return true;
}

async function runTests() {
    console.log('📅 Testing Dashboard Calendar Widget\n');
    console.log('='.repeat(50));

    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext();
    const page = await context.newPage();

    let passed = false;

    try {
        await login(page);
        passed = await testDashboardCalendarWidget(page);
    } catch (error) {
        console.error('\n❌ Error during tests:', error.message);
    } finally {
        await browser.close();
    }

    console.log('\n' + '='.repeat(50));
    console.log('📊 Test Results Summary:');
    console.log('='.repeat(50));
    console.log(`  ${passed ? '✓ PASS' : '✗ FAIL'}: Dashboard Calendar Widget`);
    console.log('='.repeat(50));

    if (passed) {
        console.log('\n✅ Dashboard widget test passed!');
    } else {
        console.log('\n⚠️  Test failed - review output above');
    }
}

runTests().catch(console.error);

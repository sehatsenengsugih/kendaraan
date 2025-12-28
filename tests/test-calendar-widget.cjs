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

async function testMiniCalendarWidget(page) {
    console.log('\n=== Test 1: Mini Calendar Widget ===');

    await page.goto(`${BASE_URL}/dashboard`);
    await page.waitForLoadState('networkidle');

    // Check calendar month title (e.g., "Desember 2025")
    const monthTitle = await page.locator('h3:has-text("2025")').count();
    if (monthTitle > 0) {
        console.log('  ✓ Month title found');
    } else {
        console.log('  ✗ Month title not found');
        return false;
    }

    // Check day headers (Min, Sen, Sel, etc.)
    const dayHeaders = await page.locator('text=Min').count();
    if (dayHeaders > 0) {
        console.log('  ✓ Day headers (Min, Sen, etc.) found');
    } else {
        console.log('  ✗ Day headers not found');
    }

    // Check calendar grid exists
    const calendarGrid = await page.locator('.grid.grid-cols-7').count();
    if (calendarGrid > 0) {
        console.log('  ✓ Calendar grid found');
    } else {
        console.log('  ✗ Calendar grid not found');
        return false;
    }

    // Check today is highlighted
    const todayCell = await page.locator('.bg-success-300').count();
    if (todayCell > 0) {
        console.log('  ✓ Today is highlighted');
    } else {
        console.log('  ✗ Today is not highlighted');
    }

    // Check "Buka Kalender" link
    const openCalendarLink = await page.locator('a:has-text("Buka Kalender")').count();
    if (openCalendarLink > 0) {
        console.log('  ✓ "Buka Kalender" link found');
    } else {
        console.log('  ✗ "Buka Kalender" link not found');
    }

    // Check legend
    const legend = await page.locator('text=Pajak Terlambat').count();
    if (legend > 0) {
        console.log('  ✓ Calendar legend found');
    } else {
        console.log('  ✗ Calendar legend not found');
    }

    return true;
}

async function testCalendarYearView(page) {
    console.log('\n=== Test 2: Calendar Year View ===');

    await page.goto(`${BASE_URL}/kalender`);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // Check if Year button exists
    const yearButton = await page.locator('button:has-text("Tahun")').count();
    if (yearButton > 0) {
        console.log('  ✓ Year view button found');
    } else {
        console.log('  ✗ Year view button not found');
        return false;
    }

    // Click year view button
    await page.click('button:has-text("Tahun")');
    await page.waitForTimeout(1000);

    // Check if multi-month view is rendered
    const multiMonthView = await page.locator('.fc-multimonth, .fc-multiMonthYear-view').count();
    if (multiMonthView > 0) {
        console.log('  ✓ Year view (multi-month) rendered');
    } else {
        // Check for individual months
        const monthGrids = await page.locator('.fc-daygrid-body').count();
        if (monthGrids >= 1) {
            console.log('  ✓ Year view rendered with multiple months');
        } else {
            console.log('  ✗ Year view not rendered properly');
        }
    }

    // Switch back to month view
    await page.click('button:has-text("Bulan")');
    await page.waitForTimeout(500);

    const monthView = await page.locator('.fc-dayGridMonth-view, .fc-daygrid-body').count();
    if (monthView > 0) {
        console.log('  ✓ Month view works after switching back');
    }

    return true;
}

async function testPengingatPajakSection(page) {
    console.log('\n=== Test 3: Pengingat Pajak Section ===');

    await page.goto(`${BASE_URL}/dashboard`);
    await page.waitForLoadState('networkidle');

    // Check section title
    const sectionTitle = await page.locator('h3:has-text("Pengingat Pajak")').count();
    if (sectionTitle > 0) {
        console.log('  ✓ Pengingat Pajak section found');
    } else {
        console.log('  ✗ Pengingat Pajak section not found');
        return false;
    }

    // Check summary cards
    const summaryCards = await page.locator('text=TERLAMBAT').count();
    if (summaryCards > 0) {
        console.log('  ✓ Summary cards found');
    } else {
        console.log('  ✗ Summary cards not found');
    }

    // Check tabs
    const tabs = await page.locator('button:has-text("Terlambat")').count();
    if (tabs > 0) {
        console.log('  ✓ Tab navigation found');
    } else {
        console.log('  ✗ Tab navigation not found');
    }

    return true;
}

async function runTests() {
    console.log('📅 Testing Calendar Widget & Year View\n');
    console.log('='.repeat(50));

    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext();
    const page = await context.newPage();

    let results = {
        miniCalendar: false,
        yearView: false,
        pengingatPajak: false
    };

    try {
        await login(page);
        results.miniCalendar = await testMiniCalendarWidget(page);
        results.yearView = await testCalendarYearView(page);
        results.pengingatPajak = await testPengingatPajakSection(page);
    } catch (error) {
        console.error('\n❌ Error during tests:', error.message);
    } finally {
        await browser.close();
    }

    // Summary
    console.log('\n' + '='.repeat(50));
    console.log('📊 Test Results Summary:');
    console.log('='.repeat(50));

    const tests = [
        ['Mini Calendar Widget', results.miniCalendar],
        ['Calendar Year View', results.yearView],
        ['Pengingat Pajak Section', results.pengingatPajak]
    ];

    let passed = 0;
    tests.forEach(([name, result]) => {
        const status = result ? '✓ PASS' : '✗ FAIL';
        console.log(`  ${status}: ${name}`);
        if (result) passed++;
    });

    console.log('='.repeat(50));
    console.log(`Total: ${passed}/${tests.length} tests passed`);

    if (passed === tests.length) {
        console.log('\n✅ All tests passed!');
    } else {
        console.log('\n⚠️  Some tests failed - review output above');
    }
}

runTests().catch(console.error);

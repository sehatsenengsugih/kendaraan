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

// =====================================
// TEST 1: Dashboard Mini Calendar Widget
// =====================================
async function testMiniCalendarWidget(page) {
    console.log('\n=== Test 1: Dashboard Mini Calendar Widget ===');

    await page.goto(`${BASE_URL}/dashboard`);
    await page.waitForLoadState('networkidle');

    let passed = true;

    // Check calendar month title
    const monthTitle = await page.locator('.card h3.text-lg').first().textContent();
    if (monthTitle && monthTitle.includes('2025')) {
        console.log('  ✓ Month title displayed:', monthTitle.trim());
    } else {
        console.log('  ✗ Month title not found');
        passed = false;
    }

    // Check day headers
    const dayHeaders = await page.locator('text=Min').count();
    if (dayHeaders > 0) {
        console.log('  ✓ Day headers (Min, Sen, etc.) present');
    } else {
        console.log('  ✗ Day headers not found');
        passed = false;
    }

    // Check calendar grid
    const calendarGrid = await page.locator('.grid.grid-cols-7').count();
    if (calendarGrid >= 2) {
        console.log('  ✓ Calendar grid rendered');
    } else {
        console.log('  ✗ Calendar grid not found');
        passed = false;
    }

    // Check today highlight
    const todayCell = await page.locator('.bg-success-300').count();
    if (todayCell > 0) {
        console.log('  ✓ Today is highlighted (green)');
    } else {
        console.log('  ✗ Today not highlighted');
    }

    // Check legend
    const legend = await page.locator('.card:first-child >> text=Pajak Terlambat').count();
    if (legend > 0) {
        console.log('  ✓ Calendar legend present');
    } else {
        console.log('  ✗ Calendar legend not found');
    }

    return passed;
}

// =====================================
// TEST 2: Mini Calendar Navigation
// =====================================
async function testMiniCalendarNavigation(page) {
    console.log('\n=== Test 2: Mini Calendar Month Navigation ===');

    await page.goto(`${BASE_URL}/dashboard`);
    await page.waitForLoadState('networkidle');

    let passed = true;

    // Get initial month
    const initialMonth = await page.locator('.card h3.text-lg').first().textContent();
    console.log(`  Current month: ${initialMonth.trim()}`);

    // Find nav buttons
    const prevButton = page.locator('.card button').first();
    const nextButton = page.locator('.card button').nth(1);

    // Test prev month
    await prevButton.click();
    await page.waitForTimeout(1000);
    const afterPrev = await page.locator('.card h3.text-lg').first().textContent();
    if (afterPrev.trim() !== initialMonth.trim()) {
        console.log(`  ✓ Prev button works: ${afterPrev.trim()}`);
    } else {
        console.log('  ✗ Prev button did not change month');
        passed = false;
    }

    // Test next month (go forward 2 months)
    await nextButton.click();
    await page.waitForTimeout(500);
    await nextButton.click();
    await page.waitForTimeout(1000);
    const afterNext = await page.locator('.card h3.text-lg').first().textContent();
    if (afterNext.trim() !== afterPrev.trim()) {
        console.log(`  ✓ Next button works: ${afterNext.trim()}`);
    } else {
        console.log('  ✗ Next button did not change month');
        passed = false;
    }

    // Test "Hari Ini" button
    const todayBtn = await page.locator('button:has-text("Hari Ini")').count();
    if (todayBtn > 0) {
        await page.click('button:has-text("Hari Ini")');
        await page.waitForTimeout(1000);
        const backToToday = await page.locator('.card h3.text-lg').first().textContent();
        if (backToToday.trim() === initialMonth.trim()) {
            console.log('  ✓ "Hari Ini" button returns to current month');
        } else {
            console.log('  ✗ "Hari Ini" button did not work');
        }
    }

    return passed;
}

// =====================================
// TEST 3: Calendar Page Load
// =====================================
async function testCalendarPageLoad(page) {
    console.log('\n=== Test 3: Calendar Page Load ===');

    await page.goto(`${BASE_URL}/kalender`);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    let passed = true;

    // Check page title
    const title = await page.locator('h2:has-text("Kalender Kendaraan")').count();
    if (title > 0) {
        console.log('  ✓ Page title "Kalender Kendaraan" found');
    } else {
        console.log('  ✗ Page title not found');
        passed = false;
    }

    // Check FullCalendar rendered
    const calendar = await page.locator('.fc').count();
    if (calendar > 0) {
        console.log('  ✓ FullCalendar rendered');
    } else {
        console.log('  ✗ FullCalendar not rendered');
        passed = false;
    }

    // Check legend
    const legend = await page.locator('text=Pajak Terlambat').count();
    if (legend > 0) {
        console.log('  ✓ Color legend displayed');
    } else {
        console.log('  ✗ Legend not found');
    }

    // Check action buttons
    const addPajakBtn = await page.locator('button:has-text("Tambah Pajak")').count();
    const addServisBtn = await page.locator('button:has-text("Tambah Servis")').count();
    if (addPajakBtn > 0 && addServisBtn > 0) {
        console.log('  ✓ Action buttons (Tambah Pajak/Servis) found');
    } else {
        console.log('  ✗ Action buttons not found');
    }

    return passed;
}

// =====================================
// TEST 4: Calendar Year View
// =====================================
async function testCalendarYearView(page) {
    console.log('\n=== Test 4: Calendar Year View (12 Months) ===');

    await page.goto(`${BASE_URL}/kalender`);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    let passed = true;

    // Check "Tahun" button exists
    const yearButton = await page.locator('button:has-text("Tahun")').count();
    if (yearButton > 0) {
        console.log('  ✓ "Tahun" view button found');
    } else {
        console.log('  ✗ "Tahun" view button not found');
        passed = false;
        return passed;
    }

    // Click year view
    await page.click('button:has-text("Tahun")');
    await page.waitForTimeout(1500);

    // Check if multi-month view rendered
    const multiMonth = await page.locator('.fc-multimonth, .fc-multiMonthYear-view, .fc-multimonth-month').count();
    if (multiMonth > 0) {
        console.log('  ✓ Year view (multi-month) rendered');
        console.log(`    Found ${multiMonth} month grids`);
    } else {
        // Fallback check
        const dayGrids = await page.locator('.fc-daygrid-body').count();
        if (dayGrids > 1) {
            console.log(`  ✓ Year view rendered with ${dayGrids} calendar grids`);
        } else {
            console.log('  ✗ Year view not rendering multiple months');
            passed = false;
        }
    }

    // Switch back to month view
    await page.click('button:has-text("Bulan")');
    await page.waitForTimeout(500);

    const monthView = await page.locator('.fc-dayGridMonth-view, .fc-daygrid-body').count();
    if (monthView > 0) {
        console.log('  ✓ Switched back to month view successfully');
    }

    return passed;
}

// =====================================
// TEST 5: Calendar Navigation
// =====================================
async function testCalendarNavigation(page) {
    console.log('\n=== Test 5: Calendar Navigation (Prev/Next) ===');

    await page.goto(`${BASE_URL}/kalender`);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    let passed = true;

    // Get current title
    const initialTitle = await page.locator('.fc-toolbar-title').textContent();
    console.log(`  Current view: ${initialTitle}`);

    // Click next
    await page.click('.fc-next-button');
    await page.waitForTimeout(500);
    const afterNext = await page.locator('.fc-toolbar-title').textContent();
    if (afterNext !== initialTitle) {
        console.log(`  ✓ Next button works: ${afterNext}`);
    } else {
        console.log('  ✗ Next button did not change view');
        passed = false;
    }

    // Click prev
    await page.click('.fc-prev-button');
    await page.waitForTimeout(500);
    const afterPrev = await page.locator('.fc-toolbar-title').textContent();
    if (afterPrev === initialTitle) {
        console.log('  ✓ Prev button works');
    } else {
        console.log('  ✗ Prev button did not return to original');
    }

    // Test week view
    const weekBtn = await page.locator('button:has-text("Minggu")').count();
    if (weekBtn > 0) {
        await page.click('button:has-text("Minggu")');
        await page.waitForTimeout(500);
        const weekView = await page.locator('.fc-timeGridWeek-view, .fc-timegrid').count();
        if (weekView > 0) {
            console.log('  ✓ Week view works');
        }
    }

    // Test list view
    const listBtn = await page.locator('button:has-text("Daftar")').count();
    if (listBtn > 0) {
        await page.click('button:has-text("Daftar")');
        await page.waitForTimeout(500);
        const listView = await page.locator('.fc-listMonth-view, .fc-list').count();
        if (listView > 0) {
            console.log('  ✓ List view works');
        }
    }

    return passed;
}

// =====================================
// TEST 6: Calendar API Endpoints
// =====================================
async function testCalendarAPIs(page) {
    console.log('\n=== Test 6: Calendar API Endpoints ===');

    let passed = true;

    // Test main events API
    const eventsResponse = await page.request.get(`${BASE_URL}/api/calendar/events?start=2024-01-01&end=2025-12-31`);
    if (eventsResponse.ok()) {
        const events = await eventsResponse.json();
        console.log(`  ✓ Events API: ${events.length} events found`);
        if (events.length > 0) {
            console.log(`    First event: ${events[0].title}`);
        }
    } else {
        console.log(`  ✗ Events API failed: ${eventsResponse.status()}`);
        passed = false;
    }

    // Test mini-events API
    const miniResponse = await page.request.get(`${BASE_URL}/api/calendar/mini-events?year=2025&month=12`);
    if (miniResponse.ok()) {
        const data = await miniResponse.json();
        console.log(`  ✓ Mini-events API: ${data.monthName}`);
        console.log(`    - daysInMonth: ${data.daysInMonth}`);
        console.log(`    - startDayOfWeek: ${data.startDayOfWeek}`);
        console.log(`    - Events on ${Object.keys(data.events || {}).length} dates`);
    } else {
        console.log(`  ✗ Mini-events API failed: ${miniResponse.status()}`);
        passed = false;
    }

    return passed;
}

// =====================================
// TEST 7: Add Pajak Modal
// =====================================
async function testAddPajakModal(page) {
    console.log('\n=== Test 7: Add Pajak Modal ===');

    await page.goto(`${BASE_URL}/kalender`);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    let passed = true;

    // Click add pajak button
    await page.click('button:has-text("Tambah Pajak")');
    await page.waitForTimeout(500);

    // Check modal opened
    const modal = await page.locator('#eventModal:not(.hidden)').count();
    if (modal > 0) {
        console.log('  ✓ Modal opened');
    } else {
        console.log('  ✗ Modal did not open');
        passed = false;
        return passed;
    }

    // Check modal title
    const modalTitle = await page.locator('#modalTitle').textContent();
    if (modalTitle === 'Tambah Pajak') {
        console.log('  ✓ Modal title correct: "Tambah Pajak"');
    } else {
        console.log(`  ✗ Wrong modal title: ${modalTitle}`);
    }

    // Check form fields
    const kendaraanSelect = await page.locator('#kendaraan_id').count();
    const jenisSelect = await page.locator('#pajak_jenis').count();
    const tanggalInput = await page.locator('#tanggal_jatuh_tempo').count();

    if (kendaraanSelect > 0 && jenisSelect > 0 && tanggalInput > 0) {
        console.log('  ✓ Pajak form fields present');
    } else {
        console.log('  ✗ Some form fields missing');
        passed = false;
    }

    // Close modal
    await page.click('button:has-text("Batal")');
    await page.waitForTimeout(300);

    return passed;
}

// =====================================
// TEST 8: Add Servis Modal
// =====================================
async function testAddServisModal(page) {
    console.log('\n=== Test 8: Add Servis Modal ===');

    await page.goto(`${BASE_URL}/kalender`);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    let passed = true;

    // Click add servis button
    await page.click('button:has-text("Tambah Servis")');
    await page.waitForTimeout(500);

    // Check modal title
    const modalTitle = await page.locator('#modalTitle').textContent();
    if (modalTitle === 'Tambah Servis') {
        console.log('  ✓ Modal opened with title: "Tambah Servis"');
    } else {
        console.log(`  ✗ Wrong modal title: ${modalTitle}`);
        passed = false;
    }

    // Check servis fields visible
    const servisFields = await page.locator('#servisFields:not(.hidden)').count();
    if (servisFields > 0) {
        console.log('  ✓ Servis form fields visible');
    } else {
        console.log('  ✗ Servis form fields not visible');
        passed = false;
    }

    // Check servis-specific fields
    const servisJenis = await page.locator('#servis_jenis').count();
    const tanggalServis = await page.locator('#tanggal_servis').count();
    const bengkel = await page.locator('#servis_bengkel').count();

    if (servisJenis > 0 && tanggalServis > 0 && bengkel > 0) {
        console.log('  ✓ All servis-specific fields present');
    } else {
        console.log('  ✗ Some servis fields missing');
    }

    // Close modal
    await page.click('button:has-text("Batal")');

    return passed;
}

// =====================================
// TEST 9: Sidebar Calendar Link
// =====================================
async function testSidebarLink(page) {
    console.log('\n=== Test 9: Sidebar Calendar Link ===');

    await page.goto(`${BASE_URL}/dashboard`);
    await page.waitForLoadState('networkidle');

    let passed = true;

    // Check if Kalender link exists in sidebar
    const kalenderLink = await page.locator('aside a:has-text("Kalender")').count();
    if (kalenderLink > 0) {
        console.log('  ✓ "Kalender" link found in sidebar');

        // Click the link
        await page.click('aside a:has-text("Kalender")');
        await page.waitForLoadState('networkidle');

        if (page.url().includes('/kalender')) {
            console.log('  ✓ Navigation to /kalender works');
        } else {
            console.log(`  ✗ Navigation failed, URL: ${page.url()}`);
            passed = false;
        }
    } else {
        console.log('  ✗ "Kalender" link not found in sidebar');
        passed = false;
    }

    return passed;
}

// =====================================
// MAIN TEST RUNNER
// =====================================
async function runTests() {
    console.log('🗓️  COMPREHENSIVE CALENDAR FEATURE TESTS');
    console.log('='.repeat(55));

    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext();
    const page = await context.newPage();

    const results = {
        miniCalendarWidget: false,
        miniCalendarNav: false,
        calendarPageLoad: false,
        calendarYearView: false,
        calendarNavigation: false,
        calendarAPIs: false,
        addPajakModal: false,
        addServisModal: false,
        sidebarLink: false
    };

    try {
        await login(page);

        results.miniCalendarWidget = await testMiniCalendarWidget(page);
        results.miniCalendarNav = await testMiniCalendarNavigation(page);
        results.calendarPageLoad = await testCalendarPageLoad(page);
        results.calendarYearView = await testCalendarYearView(page);
        results.calendarNavigation = await testCalendarNavigation(page);
        results.calendarAPIs = await testCalendarAPIs(page);
        results.addPajakModal = await testAddPajakModal(page);
        results.addServisModal = await testAddServisModal(page);
        results.sidebarLink = await testSidebarLink(page);

    } catch (error) {
        console.error('\n❌ Error during tests:', error.message);
    } finally {
        await browser.close();
    }

    // Summary
    console.log('\n' + '='.repeat(55));
    console.log('📊 TEST RESULTS SUMMARY');
    console.log('='.repeat(55));

    const tests = [
        ['1. Dashboard Mini Calendar Widget', results.miniCalendarWidget],
        ['2. Mini Calendar Month Navigation', results.miniCalendarNav],
        ['3. Calendar Page Load', results.calendarPageLoad],
        ['4. Calendar Year View (12 Months)', results.calendarYearView],
        ['5. Calendar Navigation (Prev/Next)', results.calendarNavigation],
        ['6. Calendar API Endpoints', results.calendarAPIs],
        ['7. Add Pajak Modal', results.addPajakModal],
        ['8. Add Servis Modal', results.addServisModal],
        ['9. Sidebar Calendar Link', results.sidebarLink]
    ];

    let passed = 0;
    tests.forEach(([name, result]) => {
        const status = result ? '✓ PASS' : '✗ FAIL';
        console.log(`  ${status}: ${name}`);
        if (result) passed++;
    });

    console.log('='.repeat(55));
    console.log(`📈 Total: ${passed}/${tests.length} tests passed`);
    console.log('='.repeat(55));

    if (passed === tests.length) {
        console.log('\n✅ ALL CALENDAR TESTS PASSED!');
    } else {
        console.log(`\n⚠️  ${tests.length - passed} test(s) failed - review output above`);
    }
}

runTests().catch(console.error);

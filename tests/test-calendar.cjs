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

async function testCalendarPage(page) {
    console.log('\n=== Test 1: Calendar Page Load ===');

    await page.goto(`${BASE_URL}/kalender`);
    await page.waitForLoadState('networkidle');

    // Check page title
    const title = await page.locator('h2:has-text("Kalender Kendaraan")').count();
    if (title > 0) {
        console.log('  ✓ Calendar page title found');
    } else {
        console.log('  ✗ Calendar page title not found');
        return false;
    }

    // Check if FullCalendar rendered
    await page.waitForTimeout(1000); // Wait for calendar to render
    const calendar = await page.locator('.fc').count();
    if (calendar > 0) {
        console.log('  ✓ FullCalendar rendered');
    } else {
        console.log('  ✗ FullCalendar not rendered');
        return false;
    }

    // Check legend
    const legend = await page.locator('text=Pajak Terlambat').count();
    if (legend > 0) {
        console.log('  ✓ Legend displayed');
    } else {
        console.log('  ✗ Legend not found');
    }

    // Check action buttons
    const addPajakBtn = await page.locator('button:has-text("Tambah Pajak")').count();
    const addServisBtn = await page.locator('button:has-text("Tambah Servis")').count();
    if (addPajakBtn > 0 && addServisBtn > 0) {
        console.log('  ✓ Action buttons found');
    } else {
        console.log('  ✗ Action buttons not found');
    }

    return true;
}

async function testCalendarAPI(page) {
    console.log('\n=== Test 2: Calendar API ===');

    // Test events API
    const response = await page.request.get(`${BASE_URL}/api/calendar/events?start=2024-01-01&end=2025-12-31`);

    if (response.ok()) {
        console.log('  ✓ Events API responded successfully');
        const events = await response.json();
        console.log(`  ✓ Found ${events.length} events`);

        if (events.length > 0) {
            const firstEvent = events[0];
            console.log(`    First event: ${firstEvent.title}`);
            console.log(`    Event type: ${firstEvent.extendedProps?.type}`);
        }
        return true;
    } else {
        console.log('  ✗ Events API failed:', response.status());
        return false;
    }
}

async function testAddPajakModal(page) {
    console.log('\n=== Test 3: Add Pajak Modal ===');

    await page.goto(`${BASE_URL}/kalender`);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // Click add pajak button
    await page.click('button:has-text("Tambah Pajak")');
    await page.waitForTimeout(500);

    // Check if modal opened
    const modal = await page.locator('#eventModal:not(.hidden)').count();
    if (modal > 0) {
        console.log('  ✓ Modal opened');
    } else {
        console.log('  ✗ Modal did not open');
        return false;
    }

    // Check modal title
    const modalTitle = await page.locator('#modalTitle').textContent();
    if (modalTitle === 'Tambah Pajak') {
        console.log('  ✓ Modal title correct');
    } else {
        console.log('  ✗ Modal title incorrect:', modalTitle);
    }

    // Check form fields
    const kendaraanSelect = await page.locator('#kendaraan_id').count();
    const jenisSelect = await page.locator('#pajak_jenis').count();
    const tanggalInput = await page.locator('#tanggal_jatuh_tempo').count();

    if (kendaraanSelect > 0 && jenisSelect > 0 && tanggalInput > 0) {
        console.log('  ✓ Form fields found');
    } else {
        console.log('  ✗ Some form fields missing');
    }

    // Close modal
    await page.click('button:has-text("Batal")');
    await page.waitForTimeout(300);

    return true;
}

async function testAddServisModal(page) {
    console.log('\n=== Test 4: Add Servis Modal ===');

    await page.goto(`${BASE_URL}/kalender`);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // Click add servis button
    await page.click('button:has-text("Tambah Servis")');
    await page.waitForTimeout(500);

    // Check modal title
    const modalTitle = await page.locator('#modalTitle').textContent();
    if (modalTitle === 'Tambah Servis') {
        console.log('  ✓ Servis modal opened');
    } else {
        console.log('  ✗ Wrong modal title:', modalTitle);
        return false;
    }

    // Check servis fields are visible
    const servisFields = await page.locator('#servisFields:not(.hidden)').count();
    if (servisFields > 0) {
        console.log('  ✓ Servis fields visible');
    } else {
        console.log('  ✗ Servis fields not visible');
    }

    // Close modal
    await page.click('button:has-text("Batal")');

    return true;
}

async function testSidebarLink(page) {
    console.log('\n=== Test 5: Sidebar Link ===');

    await page.goto(`${BASE_URL}/dashboard`);
    await page.waitForLoadState('networkidle');

    // Check if Kalender menu exists in sidebar
    const kalenderLink = await page.locator('aside a:has-text("Kalender")').count();
    if (kalenderLink > 0) {
        console.log('  ✓ Kalender link found in sidebar');

        // Click the link
        await page.click('aside a:has-text("Kalender")');
        await page.waitForLoadState('networkidle');

        const url = page.url();
        if (url.includes('/kalender')) {
            console.log('  ✓ Navigation to calendar page works');
        } else {
            console.log('  ✗ Navigation failed, URL:', url);
        }
        return true;
    } else {
        console.log('  ✗ Kalender link not found in sidebar');
        return false;
    }
}

async function testCalendarNavigation(page) {
    console.log('\n=== Test 6: Calendar Navigation ===');

    await page.goto(`${BASE_URL}/kalender`);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // Get current month title
    const initialTitle = await page.locator('.fc-toolbar-title').textContent();
    console.log('  Current view:', initialTitle);

    // Click next month
    await page.click('.fc-next-button');
    await page.waitForTimeout(500);
    const nextTitle = await page.locator('.fc-toolbar-title').textContent();

    if (nextTitle !== initialTitle) {
        console.log('  ✓ Next button works:', nextTitle);
    } else {
        console.log('  ✗ Next button did not change month');
    }

    // Click previous month
    await page.click('.fc-prev-button');
    await page.waitForTimeout(500);
    const prevTitle = await page.locator('.fc-toolbar-title').textContent();

    if (prevTitle === initialTitle) {
        console.log('  ✓ Prev button works');
    } else {
        console.log('  ✗ Prev button issue');
    }

    // Test view switching
    const weekBtn = await page.locator('button:has-text("Minggu")').count();
    if (weekBtn > 0) {
        await page.click('button:has-text("Minggu")');
        await page.waitForTimeout(500);
        const weekView = await page.locator('.fc-timeGridWeek-view, .fc-timegrid').count();
        if (weekView > 0) {
            console.log('  ✓ Week view works');
        }
    }

    return true;
}

async function runTests() {
    console.log('🗓️  Testing Calendar Feature\n');
    console.log('='.repeat(50));

    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext();
    const page = await context.newPage();

    let results = {
        pageLoad: false,
        api: false,
        addPajak: false,
        addServis: false,
        sidebar: false,
        navigation: false
    };

    try {
        await login(page);

        results.pageLoad = await testCalendarPage(page);
        results.api = await testCalendarAPI(page);
        results.addPajak = await testAddPajakModal(page);
        results.addServis = await testAddServisModal(page);
        results.sidebar = await testSidebarLink(page);
        results.navigation = await testCalendarNavigation(page);

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
        ['Calendar Page Load', results.pageLoad],
        ['Calendar API', results.api],
        ['Add Pajak Modal', results.addPajak],
        ['Add Servis Modal', results.addServis],
        ['Sidebar Link', results.sidebar],
        ['Calendar Navigation', results.navigation]
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
        console.log('\n✅ All calendar tests passed!');
    } else {
        console.log('\n⚠️  Some tests failed - review output above');
    }
}

runTests().catch(console.error);

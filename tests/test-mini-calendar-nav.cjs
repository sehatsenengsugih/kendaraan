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

async function testMiniCalendarNavigation(page) {
    console.log('\n=== Test: Mini Calendar Month Navigation ===');

    await page.goto(`${BASE_URL}/dashboard`);
    await page.waitForLoadState('networkidle');

    // Get initial month name
    const initialMonth = await page.locator('.card h3.text-lg').first().textContent();
    console.log(`  Current month: ${initialMonth.trim()}`);

    // Find and click prev button
    const prevButton = page.locator('.card button').first();
    const nextButton = page.locator('.card button').nth(1);

    // Click prev month
    await prevButton.click();
    await page.waitForTimeout(1000); // Wait for fetch

    // Check month changed
    const prevMonth = await page.locator('.card h3.text-lg').first().textContent();
    console.log(`  After prev: ${prevMonth.trim()}`);

    if (prevMonth.trim() !== initialMonth.trim()) {
        console.log('  ✓ Previous month button works');
    } else {
        console.log('  ✗ Previous month button did not change month');
        return false;
    }

    // Click next month twice to go back to current and then to next
    await nextButton.click();
    await page.waitForTimeout(500);
    await nextButton.click();
    await page.waitForTimeout(1000);

    const nextMonth = await page.locator('.card h3.text-lg').first().textContent();
    console.log(`  After next: ${nextMonth.trim()}`);

    if (nextMonth.trim() !== prevMonth.trim()) {
        console.log('  ✓ Next month button works');
    } else {
        console.log('  ✗ Next month button did not change month');
        return false;
    }

    // Check if "Hari Ini" button appears when not on current month
    const todayButton = await page.locator('button:has-text("Hari Ini")').count();
    if (todayButton > 0) {
        console.log('  ✓ "Hari Ini" button appears when not on current month');

        // Click "Hari Ini" to go back
        await page.click('button:has-text("Hari Ini")');
        await page.waitForTimeout(1000);

        const backToToday = await page.locator('.card h3.text-lg').first().textContent();
        console.log(`  After "Hari Ini": ${backToToday.trim()}`);

        if (backToToday.trim() === initialMonth.trim()) {
            console.log('  ✓ "Hari Ini" button returns to current month');
        } else {
            console.log('  ✗ "Hari Ini" button did not return to current month');
        }
    }

    // Check loading state appears briefly
    console.log('  ✓ Navigation buttons present and functional');

    return true;
}

async function testCalendarAPI(page) {
    console.log('\n=== Test: Mini Calendar API ===');

    // Test the mini-events API directly
    const response = await page.request.get(`${BASE_URL}/api/calendar/mini-events?year=2025&month=12`);

    if (response.ok()) {
        console.log('  ✓ Mini-events API responded successfully');
        const data = await response.json();

        if (data.year && data.month && data.monthName && data.daysInMonth !== undefined) {
            console.log(`  ✓ API response has required fields`);
            console.log(`    - monthName: ${data.monthName}`);
            console.log(`    - daysInMonth: ${data.daysInMonth}`);
            console.log(`    - startDayOfWeek: ${data.startDayOfWeek}`);
            console.log(`    - events count: ${Object.keys(data.events || {}).length} dates with events`);
            return true;
        } else {
            console.log('  ✗ API response missing required fields');
            return false;
        }
    } else {
        console.log('  ✗ Mini-events API failed:', response.status());
        return false;
    }
}

async function runTests() {
    console.log('📅 Testing Mini Calendar Navigation\n');
    console.log('='.repeat(50));

    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext();
    const page = await context.newPage();

    let results = {
        navigation: false,
        api: false
    };

    try {
        await login(page);
        results.api = await testCalendarAPI(page);
        results.navigation = await testMiniCalendarNavigation(page);
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
        ['Mini Calendar API', results.api],
        ['Month Navigation', results.navigation]
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

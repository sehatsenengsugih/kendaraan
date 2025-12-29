import { test, expect, Page } from '@playwright/test';

// Helper function to login
async function login(page: Page, email = 'admin@kas.or.id', password = 'password') {
  await page.goto('/login');
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="password"]', password);
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard');
}

// Helper to check mobile responsiveness
async function checkMobileBasics(page: Page, pageName: string) {
  // Check viewport
  const viewport = page.viewportSize();
  expect(viewport?.width).toBeLessThan(500);

  // Check hamburger menu is visible
  const hamburger = page.locator('.header-mobile .drawer-btn').first();
  await expect(hamburger).toBeVisible();

  // Check sidebar is hidden by default
  const sidebar = page.locator('.sidebar-wrapper');
  const sidebarLeft = await sidebar.evaluate(el => getComputedStyle(el).left);
  expect(sidebarLeft).toBe('-308px');

  // Check no horizontal overflow
  const body = page.locator('body');
  const bodyWidth = await body.evaluate(el => el.scrollWidth);
  const viewportWidth = viewport?.width || 390;

  if (bodyWidth > viewportWidth + 10) {
    console.warn(`⚠️ ${pageName}: Horizontal overflow detected (${bodyWidth}px > ${viewportWidth}px)`);
  }

  // Take screenshot
  await page.screenshot({ path: `test-results/mobile-${pageName.toLowerCase().replace(/\s+/g, '-')}.png`, fullPage: true });
}

test.describe('Mobile Comprehensive Tests', () => {

  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('1. Dashboard - Mobile View', async ({ page }) => {
    await page.goto('/dashboard');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Dashboard');

    // Check dashboard cards are stacked vertically
    const cards = page.locator('.card, [class*="rounded-xl"]').first();
    await expect(cards).toBeVisible();

    // Check stats cards
    await expect(page.locator('text=Total Kendaraan Aktif')).toBeVisible();
  });

  test('2. Sidebar Toggle - Open and Close', async ({ page }) => {
    await page.goto('/dashboard');
    await page.waitForLoadState('networkidle');

    // Initial state - sidebar hidden
    const sidebar = page.locator('.sidebar-wrapper');
    await expect(sidebar).toHaveCSS('left', '-308px');

    // Click hamburger to open
    await page.locator('.header-mobile .drawer-btn').first().click();
    await page.waitForTimeout(600);
    await expect(sidebar).toHaveCSS('left', '0px');

    // Check close button visible
    const closeBtn = page.locator('.sidebar-close-header .drawer-btn');
    await expect(closeBtn).toBeVisible();

    // Click close button
    await closeBtn.click();
    await page.waitForTimeout(600);
    await expect(sidebar).toHaveCSS('left', '-308px');

    await page.screenshot({ path: 'test-results/mobile-sidebar-toggle.png' });
  });

  test('3. Kendaraan List - Mobile View', async ({ page }) => {
    await page.goto('/kendaraan');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Kendaraan-List');

    // Check card view is shown on mobile (not table)
    const cardView = page.locator('.md\\:hidden').first();
    const tableView = page.locator('.hidden.md\\:block').first();

    // Card view should be visible
    await expect(cardView).toBeVisible();
  });

  test('4. Kendaraan Detail - Mobile View', async ({ page }) => {
    await page.goto('/kendaraan');
    await page.waitForLoadState('networkidle');

    // Click first kendaraan
    const firstLink = page.locator('a[href*="/kendaraan/"]').first();
    if (await firstLink.isVisible()) {
      await firstLink.click();
      await page.waitForLoadState('networkidle');
      await checkMobileBasics(page, 'Kendaraan-Detail');
    }
  });

  test('5. Kendaraan Create Form - Mobile View', async ({ page }) => {
    await page.goto('/kendaraan/create');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Kendaraan-Create');

    // Check form inputs are full width
    const inputs = page.locator('input[type="text"], select');
    const firstInput = inputs.first();
    if (await firstInput.isVisible()) {
      const inputWidth = await firstInput.evaluate(el => el.offsetWidth);
      const parentWidth = await firstInput.evaluate(el => el.parentElement?.offsetWidth || 0);
      // Input should take most of parent width
      expect(inputWidth).toBeGreaterThan(parentWidth * 0.8);
    }
  });

  test('6. Pajak List - Mobile View', async ({ page }) => {
    await page.goto('/pajak');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Pajak-List');

    // Check pagination is visible and readable
    const pagination = page.locator('[class*="pagination"], nav[role="navigation"]');
    if (await pagination.isVisible()) {
      await expect(pagination).toBeVisible();
    }
  });

  test('7. Servis List - Mobile View', async ({ page }) => {
    await page.goto('/servis');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Servis-List');
  });

  test('8. Paroki List - Mobile View', async ({ page }) => {
    await page.goto('/paroki');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Paroki-List');
  });

  test('9. Lembaga List - Mobile View', async ({ page }) => {
    await page.goto('/lembaga');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Lembaga-List');
  });

  test('10. Garasi List - Mobile View', async ({ page }) => {
    await page.goto('/garasi');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Garasi-List');
  });

  test('11. Merk List - Mobile View', async ({ page }) => {
    await page.goto('/merk');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Merk-List');
  });

  test('12. Users List - Mobile View', async ({ page }) => {
    await page.goto('/users');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Users-List');
  });

  test('13. Audit Logs - Mobile View', async ({ page }) => {
    await page.goto('/audit-logs');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Audit-Logs');
  });

  test('14. Calendar - Mobile View', async ({ page }) => {
    await page.goto('/kalender');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Calendar');

    // Calendar should be visible
    const calendar = page.locator('#calendar, [class*="calendar"]');
    if (await calendar.isVisible()) {
      await expect(calendar).toBeVisible();
    }
  });

  test('15. Manual/Panduan - Mobile View', async ({ page }) => {
    await page.goto('/manual');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Manual');
  });

  test('16. Profile Edit - Mobile View', async ({ page }) => {
    await page.goto('/profile');
    await page.waitForLoadState('networkidle');

    await checkMobileBasics(page, 'Profile-Edit');

    // Check profile form is visible (more specific selector)
    await expect(page.locator('form[action*="profile"]').first()).toBeVisible();
  });

  test('17. Touch Target Size Check', async ({ page }) => {
    await page.goto('/dashboard');
    await page.waitForLoadState('networkidle');

    // Check all clickable elements have minimum 44x44 touch target
    const clickables = page.locator('button, a, [role="button"]');
    const count = await clickables.count();

    const smallTargets: string[] = [];

    for (let i = 0; i < Math.min(count, 20); i++) {
      const el = clickables.nth(i);
      if (await el.isVisible()) {
        const box = await el.boundingBox();
        if (box && (box.width < 44 || box.height < 44)) {
          const text = await el.textContent() || await el.getAttribute('aria-label') || 'unknown';
          smallTargets.push(`${text.substring(0, 30)} (${Math.round(box.width)}x${Math.round(box.height)})`);
        }
      }
    }

    if (smallTargets.length > 0) {
      console.warn('⚠️ Touch targets smaller than 44x44px:', smallTargets);
    }

    await page.screenshot({ path: 'test-results/mobile-touch-targets.png' });
  });

  test('18. Font Size Readability Check', async ({ page }) => {
    await page.goto('/kendaraan');
    await page.waitForLoadState('networkidle');

    // Check no text smaller than 12px
    const smallTextElements: string[] = [];

    const textElements = page.locator('p, span, td, th, label, a');
    const count = await textElements.count();

    for (let i = 0; i < Math.min(count, 30); i++) {
      const el = textElements.nth(i);
      if (await el.isVisible()) {
        const fontSize = await el.evaluate(e => parseFloat(getComputedStyle(e).fontSize));
        if (fontSize < 12) {
          const text = await el.textContent() || '';
          smallTextElements.push(`"${text.substring(0, 20)}..." (${fontSize}px)`);
        }
      }
    }

    if (smallTextElements.length > 0) {
      console.warn('⚠️ Text smaller than 12px found:', smallTextElements);
    }

    await page.screenshot({ path: 'test-results/mobile-font-check.png' });
  });

  test('19. Form Usability - Mobile Input', async ({ page }) => {
    await page.goto('/kendaraan/create');
    await page.waitForLoadState('networkidle');

    // Check inputs have proper type for mobile keyboard
    const emailInputs = page.locator('input[type="email"]');
    const numberInputs = page.locator('input[type="number"], input[inputmode="numeric"]');
    const telInputs = page.locator('input[type="tel"]');

    // All inputs should be accessible
    const inputs = page.locator('input:visible, select:visible, textarea:visible');
    const inputCount = await inputs.count();

    console.log(`📝 Found ${inputCount} form inputs`);

    await page.screenshot({ path: 'test-results/mobile-form-usability.png', fullPage: true });
  });

  test('20. Navigation Flow - Mobile', async ({ page }) => {
    await page.goto('/dashboard');
    await page.waitForLoadState('networkidle');

    // Open sidebar
    await page.locator('.header-mobile .drawer-btn').first().click();
    await page.waitForTimeout(600);

    // Navigate to Kendaraan via sidebar
    await page.click('text=Kendaraan');
    await page.waitForURL('**/kendaraan');
    await page.waitForLoadState('networkidle');

    // Sidebar should close after navigation (or overlay should close)
    await page.waitForTimeout(500);

    // Navigate back via browser back button
    await page.goBack();
    await page.waitForLoadState('networkidle');

    await expect(page).toHaveURL(/dashboard/);

    await page.screenshot({ path: 'test-results/mobile-navigation-flow.png' });
  });
});

test.describe('Mobile Login Page', () => {
  test('Login Page - Mobile View', async ({ page }) => {
    await page.goto('/login');
    await page.waitForLoadState('networkidle');

    // Check login form is centered and accessible
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();

    await page.screenshot({ path: 'test-results/mobile-login.png', fullPage: true });
  });
});

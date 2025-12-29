import { test, expect } from '@playwright/test';

async function login(page) {
  await page.goto('/login');
  await page.fill('input[name="email"]', 'admin@kas.or.id');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard');
}

test.describe('Version History', () => {
  test.use({ viewport: { width: 1440, height: 900 } });

  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('Version history page loads correctly', async ({ page }) => {
    await page.goto('/manual/riwayat-versi');
    await page.waitForLoadState('networkidle');

    // Take screenshot
    await page.screenshot({ path: 'test-results/version-history.png', fullPage: true });

    // Check page title
    await expect(page.locator('h2:has-text("Riwayat Versi")')).toBeVisible();

    // On desktop: Check fixed TOC with current version
    // On mobile: Check floating button
    const isDesktop = (await page.viewportSize())?.width >= 1024;

    if (isDesktop) {
      await expect(page.locator('#desktop-version-toc')).toBeVisible();
      await expect(page.locator('#desktop-version-toc').locator('text=Versi Saat Ini')).toBeVisible();
    } else {
      // Mobile: floating button visible
      await expect(page.locator('#floating-version-btn')).toBeVisible();
    }

    // Check changelog content (visible on all viewports)
    await expect(page.locator('text=Changelog')).toBeVisible();

    // Check version badge in changelog area
    const changelogArea = page.locator('.changelog-content');
    await expect(changelogArea.locator('text=v2.4.0').first()).toBeVisible();
  });

  test('Can navigate to version history from manual page', async ({ page }) => {
    await page.goto('/manual');
    await page.waitForLoadState('networkidle');

    // On desktop, the link is in the fixed TOC on right side
    // Click on Riwayat Versi link (use visible one)
    await page.locator('a:has-text("Riwayat Versi"):visible').first().click();
    await page.waitForURL('**/manual/riwayat-versi');

    // Verify we're on version history page
    await expect(page.locator('h2:has-text("Riwayat Versi")')).toBeVisible();
  });
});

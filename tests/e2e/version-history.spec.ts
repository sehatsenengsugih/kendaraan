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

    // Check current version badge
    await expect(page.locator('text=Versi Saat Ini')).toBeVisible();
    await expect(page.locator('text=v2.4.0').first()).toBeVisible();

    // Check version list
    await expect(page.locator('text=Semua Versi')).toBeVisible();

    // Check changelog content
    await expect(page.locator('text=Changelog')).toBeVisible();
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

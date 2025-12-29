import { test, expect } from '@playwright/test';

async function login(page) {
  await page.goto('/login');
  await page.fill('input[name="email"]', 'admin@kas.or.id');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard');
}

test.describe('Footer Version', () => {
  test.use({ viewport: { width: 1440, height: 900 } });

  test('Footer shows version number', async ({ page }) => {
    await login(page);
    await page.waitForLoadState('networkidle');

    // Scroll to bottom to see footer
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
    await page.waitForTimeout(500);

    // Take screenshot
    await page.screenshot({ path: 'test-results/footer-version.png', fullPage: true });

    // Check footer exists
    const footer = page.locator('footer');
    await expect(footer).toBeVisible();

    // Check version text
    await expect(footer).toContainText('Kendaraan');
    await expect(footer).toContainText('v2.4.0');
    await expect(footer).toContainText('Keuskupan Agung Semarang');
  });
});

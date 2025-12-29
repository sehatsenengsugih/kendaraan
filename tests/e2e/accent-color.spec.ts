import { test, expect } from '@playwright/test';

async function login(page) {
  await page.goto('/login');
  await page.fill('input[name="email"]', 'admin@kas.or.id');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard');
}

test.describe('Accent Color Feature', () => {
  test.use({ viewport: { width: 1440, height: 900 } });

  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('Profile page has color picker', async ({ page }) => {
    await page.goto('/profile');
    await page.waitForLoadState('networkidle');

    // Screenshot the color picker section
    await page.screenshot({ path: 'test-results/profile-accent-color.png', fullPage: true });

    // Check preset colors exist
    const presetColors = page.locator('input[name="accent_color"]');
    await expect(presetColors.first()).toBeVisible();

    // Check custom color picker exists
    const customPicker = page.locator('#custom-color-picker');
    await expect(customPicker).toBeVisible();
  });

  test('Can change accent color', async ({ page }) => {
    await page.goto('/profile');
    await page.waitForLoadState('networkidle');

    // Click on blue preset
    await page.locator('input[name="accent_color"][value="#3B82F6"]').click();

    // Submit form
    await page.locator('button:has-text("Simpan")').first().click();
    await page.waitForLoadState('networkidle');

    // Take screenshot after color change
    await page.screenshot({ path: 'test-results/profile-accent-blue.png', fullPage: true });

    // Verify the color was applied (check CSS variable)
    const accentColor = await page.evaluate(() => {
      return getComputedStyle(document.documentElement).getPropertyValue('--accent-300').trim();
    });

    expect(accentColor).toBe('#3B82F6');
  });
});

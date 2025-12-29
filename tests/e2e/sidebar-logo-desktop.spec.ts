import { test, expect } from '@playwright/test';

async function login(page) {
  await page.goto('/login');
  await page.fill('input[name="email"]', 'admin@kas.or.id');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard');
}

test.describe('Sidebar Logo - Desktop', () => {
  // Use desktop viewport
  test.use({ viewport: { width: 1440, height: 900 } });

  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('Logo is centered and proportional', async ({ page }) => {
    await page.waitForLoadState('networkidle');

    // Take screenshot of sidebar header
    const sidebarHeader = page.locator('.sidebar-header');
    await sidebarHeader.screenshot({ path: 'test-results/sidebar-logo-desktop.png' });

    // Verify logo is visible
    const logo = page.locator('.sidebar-header img');
    await expect(logo).toBeVisible();

    // Verify subtitle text
    const subtitle = page.locator('.sidebar-header span:has-text("Keuskupan")');
    await expect(subtitle).toBeVisible();

    // Take full sidebar screenshot
    const sidebar = page.locator('aside.sidebar-wrapper');
    await sidebar.screenshot({ path: 'test-results/sidebar-full-desktop.png' });
  });
});

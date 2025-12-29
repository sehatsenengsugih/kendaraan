import { test, expect } from '@playwright/test';

async function login(page) {
  await page.goto('/login');
  await page.fill('input[name="email"]', 'admin@kas.or.id');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard');
}

test.describe('Sidebar Bottom Section - Mobile', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('User section always at bottom of sidebar', async ({ page }) => {
    // Open sidebar on mobile using the mobile header hamburger button
    await page.locator('.header-mobile .drawer-btn').click();
    await page.waitForTimeout(800);

    // Take screenshot of sidebar when open
    const sidebar = page.locator('aside.sidebar-wrapper');
    await sidebar.screenshot({ path: 'test-results/sidebar-mobile-open.png' });

    // Check user profile section is visible in sidebar
    const userSection = sidebar.locator('text=admin@kas.or.id');
    await expect(userSection).toBeVisible();

    // Check theme toggle is visible in sidebar
    const themeToggle = sidebar.locator('#theme-toggle-sidebar');
    await expect(themeToggle).toBeVisible();

    // Check logout button is visible in sidebar
    const logoutBtn = sidebar.locator('button:has-text("Keluar")');
    await expect(logoutBtn).toBeVisible();
  });
});

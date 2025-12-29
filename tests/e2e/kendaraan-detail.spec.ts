import { test, expect } from '@playwright/test';

async function login(page) {
  await page.goto('/login');
  await page.fill('input[name="email"]', 'admin@kas.or.id');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard');
}

test.describe('Kendaraan Detail Page - Mobile', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('1. Detail kendaraan - tampilan lengkap', async ({ page }) => {
    await page.goto('/kendaraan/1');
    await page.waitForLoadState('networkidle');

    await page.screenshot({ path: 'test-results/kendaraan-detail-full.png', fullPage: true });

    await expect(page.locator('text=Spesifikasi')).toBeVisible();
    await expect(page.locator('text=Dokumen')).toBeVisible();
  });

  test('2. Detail kendaraan - header responsive', async ({ page }) => {
    await page.goto('/kendaraan/1');
    await page.waitForLoadState('networkidle');

    const backBtn = page.locator('i.fa-arrow-left').first();
    await expect(backBtn).toBeVisible();

    const editBtn = page.locator('a:has-text("Edit")');
    await expect(editBtn).toBeVisible();

    await page.screenshot({ path: 'test-results/kendaraan-detail-header.png' });
  });

  test('3. Detail kendaraan - photo gallery', async ({ page }) => {
    await page.goto('/kendaraan/1');
    await page.waitForLoadState('networkidle');

    const photoArea = page.locator('.aspect-video').first();
    await expect(photoArea).toBeVisible();

    await page.screenshot({ path: 'test-results/kendaraan-detail-photo.png' });
  });

  test('4. Detail kendaraan - sidebar cards', async ({ page }) => {
    await page.goto('/kendaraan/1');
    await page.waitForLoadState('networkidle');

    await expect(page.locator('text=Lokasi')).toBeVisible();
    await expect(page.locator('text=Pengguna Saat Ini')).toBeVisible();

    await page.screenshot({ path: 'test-results/kendaraan-detail-sidebar.png', fullPage: true });
  });

  test('5. Detail kendaraan - no horizontal overflow', async ({ page }) => {
    await page.goto('/kendaraan/1');
    await page.waitForLoadState('networkidle');

    const hasOverflow = await page.evaluate(() => {
      return document.body.scrollWidth > window.innerWidth;
    });

    expect(hasOverflow).toBe(false);
  });

  test('6. Detail kendaraan - scroll to bottom', async ({ page }) => {
    await page.goto('/kendaraan/1');
    await page.waitForLoadState('networkidle');

    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
    await page.waitForTimeout(500);

    await page.screenshot({ path: 'test-results/kendaraan-detail-bottom.png' });
  });
});

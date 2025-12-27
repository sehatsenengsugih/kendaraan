import { test, expect } from '@playwright/test';

const BASE_URL = 'http://127.0.0.1:8002';

// Helper function to login
async function login(page, email = 'admin@kas.or.id', password = 'password123') {
  await page.goto('/login');
  await page.waitForSelector('input[name="email"]', { state: 'visible' });
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="password"]', password);
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard', { timeout: 15000 });
}

test.describe('Authentication', () => {
  test('login page loads', async ({ page }) => {
    await page.goto('/login');
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
  });

  test('can login as admin', async ({ page }) => {
    await login(page);
    await expect(page).toHaveURL(/dashboard/);
  });
});

test.describe('Dashboard', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('dashboard loads with stats', async ({ page }) => {
    await expect(page.getByRole('heading', { name: 'Dashboard' })).toBeVisible();
    await expect(page.locator('.stat-card, .bg-white').first()).toBeVisible();
  });
});

test.describe('Kendaraan Module', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('kendaraan index page loads', async ({ page }) => {
    await page.goto('/kendaraan');
    await expect(page.getByRole('heading', { name: 'Daftar Kendaraan' })).toBeVisible();
  });

  test('kendaraan create page loads', async ({ page }) => {
    await page.goto('/kendaraan/create');
    await expect(page.getByRole('heading', { name: 'Tambah Kendaraan' })).toBeVisible();
    await expect(page.locator('select[name="garasi_id"]')).toBeVisible();
  });

  test('can view kendaraan detail', async ({ page }) => {
    await page.goto('/kendaraan');
    const firstViewLink = page.locator('a[title="Detail"]').first();
    if (await firstViewLink.isVisible()) {
      await firstViewLink.click();
      await expect(page.getByRole('heading', { name: 'Detail Kendaraan' })).toBeVisible();
    }
  });
});

test.describe('Pajak Module', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('pajak index page loads', async ({ page }) => {
    await page.goto('/pajak');
    await expect(page.getByRole('heading', { name: 'Daftar Pajak Kendaraan' })).toBeVisible();
  });

  test('pajak create page loads', async ({ page }) => {
    await page.goto('/pajak/create');
    await expect(page.getByRole('heading', { name: 'Tambah Pajak' })).toBeVisible();
    await expect(page.locator('select[name="kendaraan_id"]')).toBeVisible();
  });

  test('pajak stats are visible', async ({ page }) => {
    await page.goto('/pajak');
    // Check for stat cards container
    await expect(page.locator('.grid').first()).toBeVisible();
  });

  test('can view pajak detail', async ({ page }) => {
    await page.goto('/pajak');
    const firstViewLink = page.locator('a[title="Detail"]').first();
    if (await firstViewLink.isVisible()) {
      await firstViewLink.click();
      await expect(page.getByRole('heading', { name: 'Detail Pajak' })).toBeVisible();
    }
  });
});

test.describe('Servis Module', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('servis index page loads', async ({ page }) => {
    await page.goto('/servis');
    await expect(page.getByRole('heading', { name: 'Daftar Servis Kendaraan' })).toBeVisible();
  });

  test('servis create page loads', async ({ page }) => {
    await page.goto('/servis/create');
    await expect(page.getByRole('heading', { name: 'Tambah Servis' })).toBeVisible();
    await expect(page.locator('select[name="kendaraan_id"]')).toBeVisible();
  });

  test('servis stats are visible', async ({ page }) => {
    await page.goto('/servis');
    // Check for stat cards container
    await expect(page.locator('.grid').first()).toBeVisible();
  });

  test('can view servis detail', async ({ page }) => {
    await page.goto('/servis');
    await page.waitForSelector('table', { state: 'visible' });
    const firstViewLink = page.locator('a[title="Detail"]').first();
    if (await firstViewLink.isVisible({ timeout: 2000 }).catch(() => false)) {
      await firstViewLink.click();
      await page.waitForURL('**/servis/*', { timeout: 10000 });
      await expect(page.getByRole('heading', { name: 'Detail Servis' })).toBeVisible();
    }
  });
});

test.describe('Penugasan Module', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('penugasan index page loads', async ({ page }) => {
    await page.goto('/penugasan');
    await expect(page.getByRole('heading', { name: 'Daftar Penugasan Kendaraan' })).toBeVisible();
  });

  test('penugasan create page loads', async ({ page }) => {
    await page.goto('/penugasan/create');
    await expect(page.getByRole('heading', { name: 'Tambah Penugasan' })).toBeVisible();
    await expect(page.locator('select[name="kendaraan_id"]')).toBeVisible();
  });

  test('penugasan stats are visible', async ({ page }) => {
    await page.goto('/penugasan');
    // Check for stat cards container
    await expect(page.locator('.grid').first()).toBeVisible();
  });

  test('can view penugasan detail', async ({ page }) => {
    await page.goto('/penugasan');
    const firstViewLink = page.locator('a[title="Detail"]').first();
    if (await firstViewLink.isVisible()) {
      await firstViewLink.click();
      await expect(page.getByRole('heading', { name: 'Detail Penugasan' })).toBeVisible();
    }
  });
});

test.describe('Master Data - Garasi', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('garasi index page loads', async ({ page }) => {
    await page.goto('/garasi');
    await expect(page.getByRole('heading', { name: 'Daftar Garasi' })).toBeVisible();
  });

  test('garasi create page loads', async ({ page }) => {
    await page.goto('/garasi/create');
    await expect(page.getByRole('heading', { name: 'Tambah Garasi' })).toBeVisible();
  });
});

test.describe('Master Data - Merk', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('merk index page loads', async ({ page }) => {
    await page.goto('/merk');
    await expect(page.getByRole('heading', { name: 'Daftar Merk' })).toBeVisible();
  });

  test('merk create page loads', async ({ page }) => {
    await page.goto('/merk/create');
    await expect(page.getByRole('heading', { name: 'Tambah Merk' })).toBeVisible();
  });
});

test.describe('Navigation', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('sidebar navigation works', async ({ page }) => {
    // Check sidebar links using role with exact match
    await expect(page.getByRole('link', { name: 'Dashboard', exact: true })).toBeVisible();
    await expect(page.getByRole('link', { name: 'Kendaraan', exact: true })).toBeVisible();
  });
});

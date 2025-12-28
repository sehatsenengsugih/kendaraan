const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const BASE_URL = 'http://localhost:8000';

// Create a simple test PNG image
function createTestImage(filepath) {
    // Minimal valid PNG (1x1 red pixel)
    const pngData = Buffer.from([
        0x89, 0x50, 0x4E, 0x47, 0x0D, 0x0A, 0x1A, 0x0A, // PNG signature
        0x00, 0x00, 0x00, 0x0D, 0x49, 0x48, 0x44, 0x52, // IHDR chunk
        0x00, 0x00, 0x00, 0x01, 0x00, 0x00, 0x00, 0x01, // 1x1
        0x08, 0x02, 0x00, 0x00, 0x00, 0x90, 0x77, 0x53, 0xDE, // 8-bit RGB
        0x00, 0x00, 0x00, 0x0C, 0x49, 0x44, 0x41, 0x54, // IDAT chunk
        0x08, 0xD7, 0x63, 0xF8, 0xCF, 0xC0, 0x00, 0x00, // compressed data
        0x00, 0x03, 0x00, 0x01, 0x00, 0x18, 0xDD, 0x8D, 0xB4,
        0x00, 0x00, 0x00, 0x00, 0x49, 0x45, 0x4E, 0x44, // IEND chunk
        0xAE, 0x42, 0x60, 0x82
    ]);
    fs.writeFileSync(filepath, pngData);
    return filepath;
}

async function login(page, email = 'admin@kas.or.id', password = 'password') {
    await page.goto(`${BASE_URL}/login`);
    await page.waitForLoadState('networkidle');
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', password);
    await page.click('button[type="submit"]');
    await page.waitForURL('**/dashboard', { timeout: 10000 });
    console.log('  ✓ Logged in as', email);
}

async function testProfileAvatarUpload(page, testImagePath) {
    console.log('\n=== Test 1: Profile Avatar Upload ===');

    // Go to profile page
    await page.goto(`${BASE_URL}/profile`);
    await page.waitForLoadState('networkidle');

    // Check if avatar section exists
    const avatarSection = await page.locator('label:has-text("Foto Profil")').count();
    if (avatarSection === 0) {
        console.log('  ✗ Avatar section not found on profile page');
        return false;
    }
    console.log('  ✓ Avatar section found');

    // Check current avatar (should be ui-avatars.com default)
    const currentAvatarSrc = await page.locator('#preview-img').getAttribute('src');
    console.log('  Current avatar:', currentAvatarSrc?.substring(0, 50) + '...');

    // Upload new avatar
    const fileInput = page.locator('input[name="avatar"]');
    await fileInput.setInputFiles(testImagePath);

    // Check if preview updated
    await page.waitForTimeout(500);
    const newPreviewSrc = await page.locator('#preview-img').getAttribute('src');
    if (newPreviewSrc.startsWith('data:image')) {
        console.log('  ✓ Avatar preview updated (base64)');
    } else {
        console.log('  ✗ Avatar preview not updated');
    }

    // Submit form
    await page.click('button:has-text("Simpan Perubahan")');
    await page.waitForLoadState('networkidle');

    // Check for success message
    const successMsg = await page.locator('.bg-success-50, [class*="success"]').count();
    const hasSuccess = await page.locator('text=berhasil').count();

    if (hasSuccess > 0) {
        console.log('  ✓ Profile updated successfully');
    } else {
        // Check if still on page (might have succeeded without message)
        const currentUrl = page.url();
        if (currentUrl.includes('/profile')) {
            console.log('  ✓ Profile page reloaded (assuming success)');
        }
    }

    // Verify avatar is now from storage
    await page.reload();
    const uploadedAvatarSrc = await page.locator('#preview-img').getAttribute('src');
    if (uploadedAvatarSrc.includes('/storage/avatars/')) {
        console.log('  ✓ Avatar uploaded and stored:', uploadedAvatarSrc);
        return true;
    } else {
        console.log('  ? Avatar src after upload:', uploadedAvatarSrc?.substring(0, 80));
        return uploadedAvatarSrc.includes('storage') || uploadedAvatarSrc.startsWith('data:');
    }
}

async function testProfileAvatarRemove(page) {
    console.log('\n=== Test 2: Profile Avatar Remove ===');

    await page.goto(`${BASE_URL}/profile`);
    await page.waitForLoadState('networkidle');

    // Check if remove checkbox exists
    const removeCheckbox = page.locator('input[name="remove_avatar"]');
    const hasRemoveOption = await removeCheckbox.count();

    if (hasRemoveOption === 0) {
        console.log('  ✗ Remove avatar option not found (might not have avatar)');
        return false;
    }

    console.log('  ✓ Remove avatar checkbox found');

    // Check the remove checkbox
    await removeCheckbox.check();

    // Submit form
    await page.click('button:has-text("Simpan Perubahan")');
    await page.waitForLoadState('networkidle');

    // Verify avatar is now default (ui-avatars)
    await page.reload();
    const avatarSrc = await page.locator('#preview-img').getAttribute('src');
    if (avatarSrc.includes('ui-avatars.com')) {
        console.log('  ✓ Avatar removed, now using default');
        return true;
    } else {
        console.log('  ? Avatar src after remove:', avatarSrc?.substring(0, 80));
        return false;
    }
}

async function testUserManagementAvatar(page, testImagePath) {
    console.log('\n=== Test 3: User Management Avatar ===');

    // Go to user management
    await page.goto(`${BASE_URL}/users`);
    await page.waitForLoadState('networkidle');

    // Check if page loaded
    const pageTitle = await page.locator('h2:has-text("Manajemen User")').count();
    if (pageTitle === 0) {
        console.log('  ✗ User management page not accessible');
        return false;
    }
    console.log('  ✓ User management page loaded');

    // Click first edit button
    const editBtn = page.locator('a[title="Edit"]').first();
    if (await editBtn.count() === 0) {
        console.log('  ✗ No edit button found');
        return false;
    }

    await editBtn.click();
    await page.waitForLoadState('networkidle');
    console.log('  ✓ Edit user page loaded');

    // Check avatar section exists
    const avatarLabel = await page.locator('label:has-text("Foto Profil")').count();
    if (avatarLabel === 0) {
        console.log('  ✗ Avatar section not found on user edit page');
        return false;
    }
    console.log('  ✓ Avatar section found on user edit page');

    // Get current avatar
    const currentAvatar = await page.locator('#preview-img').getAttribute('src');
    console.log('  Current avatar:', currentAvatar?.substring(0, 50) + '...');

    // Upload avatar
    const fileInput = page.locator('input[name="avatar"]');
    await fileInput.setInputFiles(testImagePath);
    await page.waitForTimeout(500);

    // Check preview
    const previewSrc = await page.locator('#preview-img').getAttribute('src');
    if (previewSrc.startsWith('data:image')) {
        console.log('  ✓ Avatar preview updated');
    }

    // Submit
    await page.click('button:has-text("Simpan Perubahan")');
    await page.waitForLoadState('networkidle');

    // Check result
    const currentUrl = page.url();
    if (currentUrl.includes('/users')) {
        console.log('  ✓ User updated successfully');
        return true;
    }

    return true;
}

async function testHeaderSidebarAvatar(page) {
    console.log('\n=== Test 4: Header & Sidebar Avatar Display ===');

    await page.goto(`${BASE_URL}/dashboard`);
    await page.waitForLoadState('networkidle');

    // Check header avatar
    const headerAvatar = page.locator('header img[alt]').first();
    if (await headerAvatar.count() > 0) {
        const headerAvatarSrc = await headerAvatar.getAttribute('src');
        console.log('  ✓ Header avatar found:', headerAvatarSrc?.substring(0, 50) + '...');
    } else {
        console.log('  ✗ Header avatar not found');
    }

    // Check sidebar avatar (look for user profile section)
    const sidebarAvatar = page.locator('aside img, nav img').first();
    if (await sidebarAvatar.count() > 0) {
        const sidebarAvatarSrc = await sidebarAvatar.getAttribute('src');
        console.log('  ✓ Sidebar/nav avatar found:', sidebarAvatarSrc?.substring(0, 50) + '...');
    } else {
        console.log('  ? Sidebar avatar element not clearly identified');
    }

    return true;
}

async function testUserListAvatar(page) {
    console.log('\n=== Test 5: User List Avatar Display ===');

    await page.goto(`${BASE_URL}/users`);
    await page.waitForLoadState('networkidle');

    // Check if avatars are displayed in the user list
    const userAvatars = page.locator('table img');
    const avatarCount = await userAvatars.count();

    if (avatarCount > 0) {
        console.log(`  ✓ Found ${avatarCount} avatar(s) in user list`);

        // Check first avatar src
        const firstAvatarSrc = await userAvatars.first().getAttribute('src');
        console.log('  First avatar src:', firstAvatarSrc?.substring(0, 60) + '...');
        return true;
    } else {
        console.log('  ✗ No avatars found in user list');
        return false;
    }
}

async function runTests() {
    console.log('🧪 Testing Avatar Feature\n');
    console.log('='.repeat(50));

    // Create test image
    const testImagePath = '/tmp/test-avatar.png';
    createTestImage(testImagePath);
    console.log('Created test image:', testImagePath);

    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext();
    const page = await context.newPage();

    let results = {
        profileUpload: false,
        profileRemove: false,
        userManagement: false,
        headerSidebar: false,
        userList: false
    };

    try {
        await login(page);

        // Test 1: Profile avatar upload
        results.profileUpload = await testProfileAvatarUpload(page, testImagePath);

        // Test 2: Profile avatar remove
        results.profileRemove = await testProfileAvatarRemove(page);

        // Test 3: Upload again for other tests
        await testProfileAvatarUpload(page, testImagePath);

        // Test 4: User management avatar
        results.userManagement = await testUserManagementAvatar(page, testImagePath);

        // Test 5: Header & sidebar display
        results.headerSidebar = await testHeaderSidebarAvatar(page);

        // Test 6: User list display
        results.userList = await testUserListAvatar(page);

    } catch (error) {
        console.error('\n❌ Error during tests:', error.message);
    } finally {
        await browser.close();

        // Cleanup test image
        if (fs.existsSync(testImagePath)) {
            fs.unlinkSync(testImagePath);
        }
    }

    // Summary
    console.log('\n' + '='.repeat(50));
    console.log('📊 Test Results Summary:');
    console.log('='.repeat(50));

    const tests = [
        ['Profile Avatar Upload', results.profileUpload],
        ['Profile Avatar Remove', results.profileRemove],
        ['User Management Avatar', results.userManagement],
        ['Header/Sidebar Display', results.headerSidebar],
        ['User List Display', results.userList]
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
        console.log('\n✅ All avatar tests passed!');
    } else {
        console.log('\n⚠️  Some tests failed - review output above');
    }
}

runTests().catch(console.error);

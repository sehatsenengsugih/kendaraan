<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ManualSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ManualController extends Controller
{
    /**
     * Show the user manual page.
     */
    public function index(): View
    {
        $sections = ManualSection::getTree();
        $activeSection = request('section');

        return view('manual.index', compact('sections', 'activeSection'));
    }

    /**
     * Show version history page.
     */
    public function versionHistory(): View
    {
        $sections = ManualSection::getTree();
        $changelogPath = base_path('CHANGELOG.md');
        $changelog = '';
        $versions = [];

        if (file_exists($changelogPath)) {
            $changelog = file_get_contents($changelogPath);
            // Parse versions from changelog
            preg_match_all('/## \[(\d+\.\d+\.\d+)\] - (\d{4}-\d{2}-\d{2})/', $changelog, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $versions[] = [
                    'version' => $match[1],
                    'date' => $match[2],
                ];
            }
        }

        $currentVersion = trim(file_get_contents(base_path('VERSION')));

        return view('manual.version-history', compact('sections', 'changelog', 'versions', 'currentVersion'));
    }

    /**
     * Show specific section of the manual.
     */
    public function section(string $slug): View
    {
        $section = ManualSection::where('slug', $slug)->active()->firstOrFail();
        $sections = ManualSection::getTree();

        return view('manual.index', [
            'sections' => $sections,
            'activeSection' => $slug,
            'currentSection' => $section,
        ]);
    }

    /**
     * Show admin listing of manual sections.
     */
    public function adminIndex(): View
    {
        $sections = ManualSection::root()
            ->ordered()
            ->with(['children' => function ($query) {
                $query->ordered();
            }])
            ->get();

        return view('manual.admin.index', compact('sections'));
    }

    /**
     * Show form to create a new section.
     */
    public function create(): View
    {
        $parentSections = ManualSection::root()->ordered()->get();
        return view('manual.admin.create', compact('parentSections'));
    }

    /**
     * Store a new manual section.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:manual_sections,slug',
            'icon' => 'nullable|string',
            'content' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:manual_sections,id',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Set default order if not provided
        if (!isset($validated['order'])) {
            $validated['order'] = ManualSection::max('order') + 1;
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        ManualSection::create($validated);

        return redirect()->route('manual.admin.index')
            ->with('success', 'Bagian panduan berhasil ditambahkan.');
    }

    /**
     * Show form to edit a section.
     */
    public function edit(ManualSection $manualSection): View
    {
        $parentSections = ManualSection::root()
            ->where('id', '!=', $manualSection->id)
            ->ordered()
            ->get();

        return view('manual.admin.edit', [
            'section' => $manualSection,
            'parentSections' => $parentSections,
        ]);
    }

    /**
     * Update a manual section.
     */
    public function update(Request $request, ManualSection $manualSection): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:manual_sections,slug,' . $manualSection->id,
            'icon' => 'nullable|string',
            'content' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:manual_sections,id',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        // Prevent self-referencing parent
        if (isset($validated['parent_id']) && $validated['parent_id'] == $manualSection->id) {
            $validated['parent_id'] = null;
        }

        $manualSection->update($validated);

        return redirect()->route('manual.admin.index')
            ->with('success', 'Bagian panduan berhasil diperbarui.');
    }

    /**
     * Delete a manual section.
     */
    public function destroy(ManualSection $manualSection): RedirectResponse
    {
        // Move children to root level before deleting
        $manualSection->children()->update(['parent_id' => null]);

        $manualSection->delete();

        return redirect()->route('manual.admin.index')
            ->with('success', 'Bagian panduan berhasil dihapus.');
    }

    /**
     * Reorder sections via AJAX.
     */
    public function reorder(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:manual_sections,id',
            'sections.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['sections'] as $item) {
            ManualSection::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}

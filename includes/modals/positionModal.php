<div id="positionModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4" role="dialog" aria-modal="true" aria-labelledby="positionModalTitle">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <h2 id="positionModalTitle" class="font-bold text-slate-900">Add Position</h2>
            <button type="button" data-modal-close="positionModal" class="text-2xl leading-none text-slate-400 hover:text-slate-600" aria-label="Close modal">&times;</button>
        </div>
        <form id="positionForm" method="post" action="<?= htmlspecialchars(app_url('views/admin/positions.php'), ENT_QUOTES, 'UTF-8') ?>" class="space-y-4 p-6">
            <input type="hidden" name="action" value="add" id="positionAction">
            <input type="hidden" name="id" value="" id="positionId">
            <?= csrfInput() ?>
            <div>
                <label for="positionTitle" class="mb-2 block text-sm font-semibold text-slate-700">Position title</label>
                <input id="positionTitle" name="title" required maxlength="100"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100"
                    placeholder="e.g. Council Member">
            </div>
            <div>
                <label for="positionDescription" class="mb-2 block text-sm font-semibold text-slate-700">Description</label>
                <textarea id="positionDescription" name="description" required rows="3"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100"
                    placeholder="Briefly describe this position"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" data-modal-close="positionModal" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-xl bg-purple-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-purple-800">Save Position</button>
            </div>
        </form>
    </div>
</div>
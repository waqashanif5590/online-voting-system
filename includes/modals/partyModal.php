<div id="partyModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4" role="dialog" aria-modal="true" aria-labelledby="partyModalTitle">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <h2 id="partyModalTitle" class="font-bold text-slate-900">Add Party</h2>
            <button type="button" data-modal-close="partyModal" class="text-2xl leading-none text-slate-400 hover:text-slate-600" aria-label="Close modal">&times;</button>
        </div>
        <form id="partyForm" method="post" action="<?= htmlspecialchars(app_url('views/admin/parties.php'), ENT_QUOTES, 'UTF-8') ?>" class="space-y-4 p-6">
            <input type="hidden" name="action" value="add" id="partyAction">
            <input type="hidden" name="id" value="" id="partyId">
            <?= csrfInput() ?>
            <div>
                <label for="partyName" class="mb-2 block text-sm font-semibold text-slate-700">Party name</label>
                <input id="partyName" name="name" required maxlength="100"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100"
                    placeholder="e.g. Independent Alliance">
            </div>
            <div>
                <label for="partyDescription" class="mb-2 block text-sm font-semibold text-slate-700">Description / abbreviation</label>
                <input id="partyDescription" name="description" required maxlength="255"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100"
                    placeholder="e.g. IA">
            </div>
            <div>
                <label for="partySymbol" class="mb-2 block text-sm font-semibold text-slate-700">Symbol</label>
                <input id="partySymbol" name="symbol" maxlength="255"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100"
                    placeholder="e.g. bell or elephant">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" data-modal-close="partyModal" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-xl bg-purple-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-purple-800">Save Party</button>
            </div>
        </form>
    </div>
</div>
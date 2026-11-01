function openModal(modalId, title) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    const titleId = modal.getAttribute('aria-labelledby');
    if (titleId) {
        document.getElementById(titleId).textContent = title;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.querySelector('input:not([type="hidden"]), textarea')?.focus();
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
    modal.querySelector('form')?.reset();
}

document.addEventListener('click', function (event) {
    const closeButton = event.target.closest('[data-modal-close]');
    if (closeButton) {
        closeModal(closeButton.dataset.modalClose);
    }

    const modalBackdrop = event.target.closest('[role="dialog"]');
    if (modalBackdrop && event.target === modalBackdrop) {
        closeModal(modalBackdrop.id);
    }
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('[role="dialog"].flex').forEach(function (modal) {
            closeModal(modal.id);
        });
    }
});

const positionForm = document.getElementById('positionForm');
if (positionForm) {
    const addPositionButton = document.querySelector('[data-open-modal="positionModal"]');
    const positionRows = document.getElementById('positionRows');

    addPositionButton.addEventListener('click', function () {
        positionForm.reset();
        document.getElementById('positionAction').value = 'add';
        document.getElementById('positionId').value = '';
        openModal('positionModal', 'Add Position');
    });

    positionRows.addEventListener('click', function (event) {
        const editButton = event.target.closest('[data-edit]');
        if (!editButton) return;

        const row = editButton.closest('[data-record]');
        positionForm.reset();
        document.getElementById('positionAction').value = 'edit';
        document.getElementById('positionId').value = row.dataset.id;
        document.getElementById('positionTitle').value = row.querySelector('[data-title]').textContent.trim();
        document.getElementById('positionDescription').value = row.querySelector('[data-description]').textContent.trim();
        openModal('positionModal', 'Edit Position');
    });

    document.querySelectorAll('[data-delete-form]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!confirm('Delete this position?')) {
                event.preventDefault();
            }
        });
    });
}

const partyForm = document.getElementById('partyForm');
if (partyForm) {
    const addPartyButton = document.querySelector('[data-open-modal="partyModal"]');
    const partyRows = document.getElementById('partyRows');

    addPartyButton.addEventListener('click', function () {
        partyForm.reset();
        document.getElementById('partyAction').value = 'add';
        document.getElementById('partyId').value = '';
        openModal('partyModal', 'Add Party');
    });

    partyRows.addEventListener('click', function (event) {
        const editButton = event.target.closest('[data-edit]');
        if (!editButton) return;

        const row = editButton.closest('[data-record]');
        partyForm.reset();
        document.getElementById('partyAction').value = 'edit';
        document.getElementById('partyId').value = row.dataset.id;
        document.getElementById('partyName').value = row.querySelector('[data-name]').textContent.trim();
        document.getElementById('partyDescription').value = row.querySelector('[data-description]').textContent.trim();
        document.getElementById('partySymbol').value = row.querySelector('[data-symbol]').textContent.trim();
        openModal('partyModal', 'Edit Party');
    });

    document.querySelectorAll('[data-delete-form]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!confirm('Delete this party?')) {
                event.preventDefault();
            }
        });
    });
}

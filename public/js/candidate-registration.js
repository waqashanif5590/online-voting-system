document.querySelectorAll('.application-toggle').forEach((button) => {
    button.addEventListener('click', () => {
        const target = document.getElementById(button.getAttribute('aria-controls'));
        const isOpen = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        button.querySelector('svg').classList.toggle('rotate-180', !isOpen);
        target.style.maxHeight = isOpen ? '0' : `${target.scrollHeight}px`;
        target.style.opacity = isOpen ? '0' : '1';
        button.querySelector('.sr-only').textContent = isOpen
            ? 'Show application details'
            : 'Hide application details';
    });
});

const election = document.getElementById('election');
if (election) {
const position = document.getElementById('position');
const party = document.getElementById('party');

election.addEventListener('change', async () => {
    position.innerHTML = '<option value="">Loading positions...</option>';
    party.innerHTML = '<option value="">Loading parties...</option>';

    if (!election.value) {
        position.innerHTML = '<option value="">Select Position</option>';
        party.innerHTML = '<option value="">Select Party</option>';
        return;
    }

    try {
        const response = await fetch(
            `get-election-options.php?election_id=${encodeURIComponent(election.value)}`
        );

        if (!response.ok) {
            throw new Error('Could not load election options');
        }

        const data = await response.json();

        position.innerHTML = '<option value="">Select Position</option>';
        data.positions.forEach(item => {
            position.innerHTML += `<option value="${item.id}">${item.title}</option>`;
        });
        party.innerHTML = '<option value="">Select Party</option>';
        data.parties.forEach(item => {
            party.innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
    } catch (error) {
        position.innerHTML = '<option value="">Could not load positions</option>';
        party.innerHTML = '<option value="">Could not load parties</option>';
        console.error(error);
    }
});
}
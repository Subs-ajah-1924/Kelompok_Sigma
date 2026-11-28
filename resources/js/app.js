

import Alpine from 'alpinejs';
import Sortable from 'sortablejs';

window.Alpine = Alpine;

window.togglePassword = (fieldId, iconId) => {
    const input = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);

    if (!input || !icon) {
        return;
    }

    input.type = input.type === 'password' ? 'text' : 'password';
    icon.textContent = input.type === 'password' ? 'visibility' : 'visibility_off';
};

const initializeCourseTimeFields = () => {
    document.querySelectorAll('[data-time-fields]').forEach((fields) => {
        const startInput = fields.querySelector('input[name="start_time"]');
        const endInput = fields.querySelector('input[name="end_time"]');
        const combinedInput = fields.querySelector('input[name="time"]');
        const existingValue = combinedInput.value;

        if (existingValue.includes('-')) {
            const [start, end] = existingValue.split('-').map((value) => value.trim());
            startInput.value = start || '';
            endInput.value = end || '';
        } else if (existingValue) {
            startInput.value = existingValue.trim();
        }

        const updateTime = () => {
            combinedInput.value = startInput.value && endInput.value
                ? `${startInput.value} - ${endInput.value}`
                : startInput.value || '';
        };

        startInput.addEventListener('change', updateTime);
        endInput.addEventListener('change', updateTime);
    });
};

const initializeKanban = () => {
    const board = document.querySelector('#kanban-board');

    if (!board) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const columns = [...board.querySelectorAll('.kanban-column')];

    const updateCounts = () => {
        columns.forEach((column) => {
            column.closest('section').querySelector('.kanban-count').textContent = column.querySelectorAll('.kanban-card').length;
        });
    };

    const updateStatus = async (card, status, previousColumn, previousNextSibling) => {
        try {
            const response = await fetch(`/tasks/${card.dataset.taskId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ status }),
            });

            if (!response.ok) {
                throw new Error('Status update failed');
            }
        } catch (error) {
            previousColumn.insertBefore(card, previousNextSibling);
            card.querySelector('select').value = previousColumn.dataset.status;
            updateCounts();
            window.alert('Status tugas gagal diperbarui. Perubahan dibatalkan.');
        }
    };

    columns.forEach((column) => {
        Sortable.create(column, {
            group: 'kanban',
            animation: 150,
            draggable: '.kanban-card',
            onStart: ({ item }) => {
                item.dataset.previousColumn = item.parentElement.dataset.status;
                item.dataset.previousIndex = [...item.parentElement.children].indexOf(item);
            },
            onEnd: ({ item, from }) => {
                const targetStatus = item.parentElement.dataset.status;
                const previousColumn = board.querySelector(`[data-status="${item.dataset.previousColumn}"]`);
                const previousNextSibling = previousColumn.children[Number(item.dataset.previousIndex)] || null;
                updateCounts();
                if (targetStatus !== item.dataset.previousColumn) {
                    updateStatus(item, targetStatus, previousColumn, previousNextSibling);
                }
            },
        });
    });

    board.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const card = form.closest('.kanban-card');
            const status = form.querySelector('select').value;
            const target = board.querySelector(`[data-status="${status}"]`);
            const previousColumn = card.parentElement;
            const previousNextSibling = card.nextElementSibling;
            target.appendChild(card);
            updateCounts();
            await updateStatus(card, status, previousColumn, previousNextSibling);
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initializeCourseTimeFields();
    initializeKanban();
});

Alpine.start();



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
    icon.innerHTML = input.type === 'password'
        ? '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />'
        : '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.322 16.167 7.256 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.744 0 8.678 3.333 10.066 7.5a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />';
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

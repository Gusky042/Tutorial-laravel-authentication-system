// Live "passwords match" indicator for forms that contain [data-password-match].
// The server still validates with the 'confirmed' rule; this only helps the user.
function initPasswordMatch() {
    const indicator = document.querySelector('[data-password-match]');

    if (!indicator) {
        return;
    }

    const password = document.getElementById(indicator.dataset.password);
    const confirmation = document.getElementById(indicator.dataset.confirmation);
    const matchText = indicator.querySelector('[data-match]');
    const mismatchText = indicator.querySelector('[data-mismatch]');

    if (!password || !confirmation || !matchText || !mismatchText) {
        return;
    }

    const update = () => {
        const isEmpty = confirmation.value === '';
        const isMatch = password.value === confirmation.value;

        matchText.classList.toggle('hidden', isEmpty || !isMatch);
        mismatchText.classList.toggle('hidden', isEmpty || isMatch);

        // Stops the browser from submitting while the passwords differ.
        confirmation.setCustomValidity(!isEmpty && !isMatch ? 'Passwords do not match' : '');
    };

    password.addEventListener('input', update);
    confirmation.addEventListener('input', update);
    update();
}

// Show/Hide buttons for password fields: <button data-toggle-password="input-id">.
function initPasswordToggles() {
    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        const input = document.getElementById(button.dataset.togglePassword);

        if (!input) {
            return;
        }

        const name = button.getAttribute('aria-label').replace(/^Show /, '');

        button.classList.remove('hidden');

        const setVisible = (visible) => {
            input.type = visible ? 'text' : 'password';
            button.textContent = visible ? 'Hide' : 'Show';
            button.setAttribute('aria-pressed', String(visible));
            button.setAttribute('aria-label', (visible ? 'Hide ' : 'Show ') + name);
        };

        button.addEventListener('click', () => setVisible(input.type === 'password'));

        // Always submit as a password field so browsers don't store it as plain text.
        input.form?.addEventListener('submit', () => setVisible(false));
    });
}

// Hover/focus tooltips for dashboard charts. Each [data-chart] holds one
// [data-chart-tooltip] and any number of targets with data-tip='{"title", "rows"}'.
// Text is set with textContent, so the data can never inject HTML.
function initChartTooltips() {
    document.querySelectorAll('[data-chart]').forEach((chart) => {
        const tooltip = chart.querySelector('[data-chart-tooltip]');

        if (!tooltip) {
            return;
        }

        const show = (target) => {
            let tip;

            try {
                tip = JSON.parse(target.dataset.tip);
            } catch {
                return;
            }

            const title = document.createElement('p');
            title.className = 'mb-1 font-semibold text-gray-900';
            title.textContent = tip.title;

            const rows = (tip.rows || []).map((row) => {
                const line = document.createElement('p');
                line.className = 'flex items-center justify-between gap-4 text-gray-600';

                const label = document.createElement('span');
                label.className = 'flex items-center gap-1.5';

                if (row.color) {
                    const key = document.createElement('span');
                    key.className = 'inline-block size-2 rounded-full';
                    key.style.backgroundColor = row.color;
                    label.append(key);
                }

                label.append(row.label);

                const value = document.createElement('span');
                value.className = 'font-medium tabular-nums text-gray-900';
                value.textContent = row.value;

                line.append(label, value);

                return line;
            });

            tooltip.replaceChildren(title, ...rows);
            tooltip.hidden = false;

            const box = chart.getBoundingClientRect();
            const rect = target.getBoundingClientRect();
            const centre = rect.left - box.left + rect.width / 2;
            const width = tooltip.offsetWidth;
            let left;
            let top;

            if (rect.height > 120) {
                // Tall targets (line chart bands): sit beside the crosshair, flipping left near the edge.
                left = centre + 12 + width <= box.width ? centre + 12 : centre - 12 - width;
                top = rect.top - box.top;
            } else {
                // Short targets (bars, columns): centre above.
                left = centre - width / 2;
                top = rect.top - box.top - tooltip.offsetHeight - 8;
            }

            tooltip.style.left = `${Math.min(Math.max(left, 0), box.width - width)}px`;
            tooltip.style.top = `${Math.max(top, 0)}px`;
        };

        const hide = () => {
            tooltip.hidden = true;
        };

        // Shapes such as donut segments: follow the pointer instead of the shape's box.
        const follow = (event) => {
            const box = chart.getBoundingClientRect();
            const width = tooltip.offsetWidth;
            let left = event.clientX - box.left + 14;

            if (left + width > box.width) {
                left = event.clientX - box.left - 14 - width;
            }

            tooltip.style.left = `${Math.max(left, 0)}px`;
            tooltip.style.top = `${Math.max(event.clientY - box.top - tooltip.offsetHeight - 10, 0)}px`;
        };

        chart.querySelectorAll('[data-tip]').forEach((target) => {
            const isShape = target instanceof SVGElement;

            target.addEventListener('mouseenter', (event) => {
                show(target);

                if (isShape) {
                    follow(event);
                }
            });

            if (isShape) {
                target.addEventListener('mousemove', follow);
            }

            target.addEventListener('focus', () => show(target));
            target.addEventListener('mouseleave', hide);
            target.addEventListener('blur', hide);
        });

        chart.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                hide();
            }
        });
    });
}

// Sidebar on small screens: the menu button opens it, the backdrop or Escape closes it.
function initSidebar() {
    const toggle = document.querySelector('[data-sidebar-toggle]');

    if (!toggle) {
        return;
    }

    const setOpen = (open) => {
        document.body.dataset.sidebar = open ? 'open' : 'closed';
        toggle.setAttribute('aria-expanded', String(open));
    };

    toggle.addEventListener('click', () => setOpen(document.body.dataset.sidebar !== 'open'));
    document.querySelector('[data-sidebar-close]')?.addEventListener('click', () => setOpen(false));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && document.body.dataset.sidebar === 'open') {
            setOpen(false);
            toggle.focus();
        }
    });
}

// <details data-dropdown> menus: close when clicking elsewhere, pressing Escape, or opening another.
function initDropdowns() {
    const dropdowns = [...document.querySelectorAll('details[data-dropdown]')];

    if (dropdowns.length === 0) {
        return;
    }

    document.addEventListener('click', (event) => {
        dropdowns.forEach((dropdown) => {
            if (dropdown.open && !dropdown.contains(event.target)) {
                dropdown.open = false;
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') {
            return;
        }

        dropdowns.forEach((dropdown) => {
            if (dropdown.open) {
                dropdown.open = false;
                dropdown.querySelector('summary')?.focus();
            }
        });
    });
}

// "Good morning/afternoon/evening" using the viewer's own clock.
function initGreeting() {
    const greeting = document.querySelector('[data-greeting]');

    if (!greeting) {
        return;
    }

    const hour = new Date().getHours();
    greeting.textContent = hour < 12 ? 'Good morning' : hour < 18 ? 'Good afternoon' : 'Good evening';
}

// Dashboard tabs. "overview" shows every card; other tabs show cards whose
// data-panel lists that tab. Arrow keys move between tabs.
function initDashboardTabs() {
    const tablist = document.querySelector('[data-tabs]');
    const container = document.querySelector('[data-tab-panels]');

    if (!tablist || !container) {
        return;
    }

    const tabs = [...tablist.querySelectorAll('[role="tab"]')];

    const select = (tab) => {
        const key = tab.dataset.tab;

        tabs.forEach((t) => {
            const selected = t === tab;
            t.setAttribute('aria-selected', String(selected));
            t.tabIndex = selected ? 0 : -1;
        });

        container.setAttribute('aria-labelledby', tab.id);
        container.querySelectorAll('[data-panel]').forEach((panel) => {
            panel.hidden = key !== 'overview' && !panel.dataset.panel.split(' ').includes(key);
        });
    };

    tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => select(tab));
        tab.addEventListener('keydown', (event) => {
            const step = { ArrowRight: 1, ArrowLeft: -1 }[event.key];

            if (step) {
                const next = tabs[(index + step + tabs.length) % tabs.length];
                next.focus();
                select(next);
            }
        });
    });
}

function initDashboardActions() {
    document.querySelector('[data-print]')?.addEventListener('click', () => window.print());

    const exportButton = document.querySelector('[data-export]');
    const source = document.getElementById('dashboard-export');

    if (!exportButton || !source) {
        return;
    }

    exportButton.addEventListener('click', () => {
        const { filename, rows } = JSON.parse(source.textContent);

        const cell = (value) => {
            let text = String(value);

            // Stop spreadsheet apps from treating a value as a formula.
            if (/^[=+\-@]/.test(text)) {
                text = `'${text}`;
            }

            return `"${text.replace(/"/g, '""')}"`;
        };

        const csv = rows.map((row) => row.map(cell).join(',')).join('\r\n');
        const url = URL.createObjectURL(new Blob(['﻿' + csv], { type: 'text/csv;charset=utf-8' }));
        const link = Object.assign(document.createElement('a'), { href: url, download: filename });

        document.body.append(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);
    });
}

function init() {
    initPasswordMatch();
    initPasswordToggles();
    initChartTooltips();
    initSidebar();
    initDropdowns();
    initGreeting();
    initDashboardTabs();
    initDashboardActions();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

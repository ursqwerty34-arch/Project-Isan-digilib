document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('select[data-cs]').forEach(initCS);
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.cs-wrap')) {
            document.querySelectorAll('.cs-wrap.open').forEach(w => w.classList.remove('open'));
        }
    });
});

function initCS(select) {
    const isForm  = select.hasAttribute('data-cs-form');
    const options = Array.from(select.options);
    const current = select.value;

    const wrap = document.createElement('div');
    wrap.className = 'cs-wrap';

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'cs-btn' + (isForm ? ' cs-form' : '');
    if (select.classList.contains('input-error')) btn.classList.add('input-error');

    const label = document.createElement('span');
    label.textContent = options.find(o => o.value === current)?.text || options[0]?.text || '-- Pilih --';

    const svg = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>`;
    btn.appendChild(label);
    btn.insertAdjacentHTML('beforeend', svg);

    const menu = document.createElement('div');
    menu.className = 'cs-menu' + (isForm ? ' cs-form-menu' : '');

    options.forEach(opt => {
        const item = document.createElement('div');
        item.className = 'cs-item' + (opt.value === current ? ' selected' : '');
        item.textContent = opt.text;
        item.dataset.value = opt.value;
        item.addEventListener('click', function () {
            select.value = opt.value;
            select.dispatchEvent(new Event('change', { bubbles: true }));
            label.textContent = opt.text;
            menu.querySelectorAll('.cs-item').forEach(i => i.classList.remove('selected'));
            item.classList.add('selected');
            wrap.classList.remove('open');
        });
        menu.appendChild(item);
    });

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        document.querySelectorAll('.cs-wrap.open').forEach(w => { if (w !== wrap) w.classList.remove('open'); });
        wrap.classList.toggle('open');
    });

    select.style.display = 'none';
    select.parentNode.insertBefore(wrap, select);
    wrap.appendChild(select);
    wrap.appendChild(btn);
    wrap.appendChild(menu);
}

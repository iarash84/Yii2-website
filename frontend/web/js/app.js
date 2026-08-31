(function () {
    'use strict';

    const root = document.documentElement;
    const themeSelector = document.querySelector('[data-theme-selector]');
    const themeMedia = window.matchMedia('(prefers-color-scheme: dark)');
    const allowedThemes = ['system', 'site-light', 'site-dark', 'corporate', 'nord', 'business'];
    const legacyThemes = {light: 'site-light', dark: 'site-dark'};
    const applyTheme = function (preference, persist) {
        preference = legacyThemes[preference] || preference;
        if (!allowedThemes.includes(preference)) preference = 'system';
        const resolved = preference === 'system' ? (themeMedia.matches ? 'site-dark' : 'site-light') : preference;
        root.dataset.theme = resolved;
        root.dataset.themePreference = preference;
        if (themeSelector) themeSelector.value = preference;
        if (persist) localStorage.setItem('color-theme', preference);
    };
    applyTheme(localStorage.getItem('color-theme') || 'system', false);
    if (themeSelector) themeSelector.addEventListener('change', function () { applyTheme(themeSelector.value, true); });
    const handleSystemTheme = function () { if (root.dataset.themePreference === 'system') applyTheme('system', false); };
    if (themeMedia.addEventListener) themeMedia.addEventListener('change', handleSystemTheme);
    else themeMedia.addListener(handleSystemTheme);

    const toggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-primary-nav]');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            const open = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            document.body.classList.toggle('is-nav-open', open);
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && nav.classList.contains('is-open')) {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('is-nav-open');
                toggle.focus();
            }
        });
    }

    const adminSidebar = document.querySelector('[data-admin-sidebar]');
    const adminSidebarToggle = document.querySelector('[data-admin-sidebar-toggle]');
    if (adminSidebar && adminSidebarToggle) {
        const setSidebar = function (open) {
            adminSidebar.classList.toggle('is-open', open);
            adminSidebarToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        };
        adminSidebarToggle.addEventListener('click', function () { setSidebar(!adminSidebar.classList.contains('is-open')); });
        adminSidebar.addEventListener('click', function (event) {
            if (event.target.closest('a') && window.matchMedia('(max-width: 64rem)').matches) setSidebar(false);
        });
        document.addEventListener('keydown', function (event) { if (event.key === 'Escape') setSidebar(false); });
    }

    document.querySelectorAll('.admin-content form .form-control, .admin-content form input, .admin-content form select, .admin-content form textarea').forEach(function (field) {
        if (field.type === 'hidden' || field.type === 'submit') return;
        if (field.type === 'checkbox') field.classList.add('d-checkbox');
        else if (field.type === 'radio') field.classList.add('d-radio');
        else if (field.type === 'file') field.classList.add('d-file-input', 'd-file-input-bordered');
        else if (field.tagName === 'SELECT') field.classList.add('d-select', 'd-select-bordered');
        else if (field.tagName === 'TEXTAREA') field.classList.add('d-textarea', 'd-textarea-bordered');
        else field.classList.add('d-input', 'd-input-bordered');
    });
    document.querySelectorAll('.admin-content .btn').forEach(function (button) {
        button.classList.add('d-btn');
        if (button.classList.contains('btn-danger')) button.classList.add('d-btn-error');
        else if (button.classList.contains('btn-secondary')) button.classList.add('d-btn-outline');
        else button.classList.add('d-btn-primary');
    });

    const confirmationDialog = document.querySelector('[data-confirmation-dialog]');
    if (confirmationDialog) {
        let pendingConfirmation = null;
        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('[data-confirm]');
            if (!trigger || trigger.dataset.confirmApproved === 'true') return;
            event.preventDefault();
            event.stopImmediatePropagation();
            pendingConfirmation = trigger;
            confirmationDialog.querySelector('[data-confirmation-message]').textContent = trigger.dataset.confirm;
            confirmationDialog.showModal();
        }, true);
        confirmationDialog.addEventListener('close', function () {
            if (confirmationDialog.returnValue === 'confirm' && pendingConfirmation) {
                const trigger = pendingConfirmation;
                pendingConfirmation = null;
                trigger.dataset.confirmApproved = 'true';
                trigger.click();
                delete trigger.dataset.confirmApproved;
            } else {
                pendingConfirmation = null;
            }
        });
    }

    document.querySelectorAll('[data-dismiss-alert]').forEach(function (button) {
        button.addEventListener('click', function () {
            button.closest('[role="alert"], [role="status"]').remove();
        });
    });

    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            const submit = form.querySelector('[type="submit"]');
            if (submit && !submit.disabled) {
                submit.classList.add('is-loading');
                submit.setAttribute('aria-busy', 'true');
                if (!submit.querySelector('.d-loading')) {
                    const spinner = document.createElement('span');
                    spinner.className = 'd-loading d-loading-spinner d-loading-sm';
                    spinner.setAttribute('aria-hidden', 'true');
                    submit.prepend(spinner);
                }
                window.setTimeout(function () { submit.disabled = true; }, 0);
            }
        });
    });

    const scrollButton = document.getElementById('scroll-to-top');
    if (scrollButton) {
        const update = function () {
            scrollButton.classList.toggle('is-visible', window.scrollY > 320);
        };
        window.addEventListener('scroll', update, {passive: true});
        scrollButton.addEventListener('click', function () {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
        update();
    }

    const dashboard = document.querySelector('[data-dashboard-widgets]');
    if (dashboard) {
        let layout = JSON.parse(dashboard.dataset.layout || '{"order":[],"hidden":[]}');
        const picker = document.querySelector('[data-dashboard-picker]');
        const widgets = Array.from(dashboard.querySelectorAll('[data-widget]'));
        const save = function () {
            layout.order = Array.from(dashboard.querySelectorAll('[data-widget]')).map(el => el.dataset.widget);
            layout.hidden = widgets.filter(el => el.hidden).map(el => el.dataset.widget);
            const body = new URLSearchParams(); body.set('layout', JSON.stringify(layout)); body.set(dashboard.dataset.csrfParam, dashboard.dataset.csrfToken);
            fetch(dashboard.dataset.saveUrl, {method: 'POST', headers: {'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8'}, body: body.toString()});
        };
        layout.order.forEach(id => { const el=dashboard.querySelector('[data-widget="'+id+'"]'); if(el) dashboard.appendChild(el); });
        widgets.forEach(el => { el.hidden=(layout.hidden || []).includes(el.dataset.widget); const label=document.createElement('label'); const input=document.createElement('input'); input.type='checkbox'; input.checked=!el.hidden; input.addEventListener('change',()=>{el.hidden=!input.checked; save();}); label.append(input, document.createTextNode(' '+el.dataset.title)); picker.appendChild(label); el.addEventListener('dragstart',()=>el.classList.add('is-dragging')); el.addEventListener('dragend',()=>{el.classList.remove('is-dragging'); save();}); });
        dashboard.addEventListener('dragover', event => { event.preventDefault(); const moving=dashboard.querySelector('.is-dragging'); const target=event.target.closest('[data-widget]'); if(moving && target && moving!==target) dashboard.insertBefore(moving, target); });
        const customize=document.querySelector('[data-dashboard-customize]'); if(customize) customize.addEventListener('click',()=>{picker.hidden=!picker.hidden;});
    }

    document.querySelectorAll('textarea[data-rich-editor]').forEach(function (source) {
        const shell = document.createElement('div'); shell.className = 'rich-editor';
        const toolbar = document.createElement('div'); toolbar.className = 'rich-editor-toolbar'; toolbar.setAttribute('role', 'toolbar');
        const editor = document.createElement('div'); editor.className = 'rich-editor-content'; editor.contentEditable = 'true'; editor.dir = source.closest('[dir]')?.getAttribute('dir') || 'auto'; editor.innerHTML = source.value;
        const commands = [['bold','B'],['italic','I'],['formatBlock','H2'],['insertUnorderedList','•'],['insertOrderedList','1.'],['removeFormat','×']];
        commands.forEach(function (item) { const button=document.createElement('button'); button.type='button'; button.className='rich-editor-button'; button.textContent=item[1]; button.title=item[0]; button.addEventListener('click',function(){ editor.focus(); document.execCommand(item[0], false, item[0]==='formatBlock' ? 'h2' : null); editor.dispatchEvent(new Event('input')); }); toolbar.appendChild(button); });
        const link=document.createElement('button'); link.type='button'; link.className='rich-editor-button'; link.textContent='↗'; link.title='Link'; link.addEventListener('click',function(){ const url=window.prompt('URL'); if(url && /^(https?:\/\/|\/|#|mailto:)/i.test(url)){editor.focus(); document.execCommand('createLink',false,url); editor.dispatchEvent(new Event('input'));} }); toolbar.appendChild(link);
        editor.addEventListener('input', function(){ source.value=editor.innerHTML; source.dispatchEvent(new Event('change',{bubbles:true})); });
        source.classList.add('rich-text-source-enhanced'); source.insertAdjacentElement('beforebegin', shell); shell.append(toolbar, editor);
    });

    const sectionSorter = document.querySelector('[data-home-section-sorter]');
    if (sectionSorter) {
        const state = document.querySelector('[data-home-section-save-state]');
        let moving = null;
        const saveSections = function () {
            const items = Array.from(sectionSorter.querySelectorAll('[data-section-id]')).map(function(row){ return {id:Number(row.dataset.sectionId),enabled:row.querySelector('[data-section-enabled]').checked}; });
            const body=new URLSearchParams(); body.set('items',JSON.stringify(items)); body.set(sectionSorter.dataset.csrfParam,sectionSorter.dataset.csrfToken);
            state.textContent='…';
            fetch(sectionSorter.dataset.saveUrl,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8'},body:body.toString()}).then(function(response){if(!response.ok) throw new Error(); return response.json();}).then(function(){state.textContent='✓';}).catch(function(){state.textContent='!';});
        };
        sectionSorter.querySelectorAll('[data-section-id]').forEach(function(row){ row.addEventListener('dragstart',function(){moving=row; row.classList.add('is-dragging');}); row.addEventListener('dragend',function(){row.classList.remove('is-dragging'); moving=null; saveSections();}); row.querySelector('[data-section-enabled]').addEventListener('change',saveSections); });
        sectionSorter.addEventListener('dragover',function(event){event.preventDefault(); const target=event.target.closest('[data-section-id]'); if(moving && target && target!==moving) sectionSorter.insertBefore(moving,target);});
    }
}());

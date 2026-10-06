import { createDemo, currentMonth, parseMoney, summary, filteredMovements, saveMovement, removeEntity } from './model.js';

let state = createDemo();
let page = 'resumen';
let month = currentMonth();
let filters = { month, type: '', account: '', search: '' };
let editing = null;
let pendingAction = null;
let statusTimer;
let editorOpener;
const selectedMovements = new Set();
let movementSort = { key: 'date', direction: -1 };
const view = document.querySelector('#view');
const editor = document.querySelector('#editor');
const form = document.querySelector('#editor-form');
const confirmation = document.querySelector('#confirmation');
const money = amount => new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(amount / 100);
const escape = value => String(value).replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[character]);
const icon = name => `<sl-icon name="${escape(name)}" aria-hidden="true"></sl-icon>`;
const accountIcon = account => account.type === 'Efectivo' ? 'coin' : account.type === 'Ahorro' ? 'piggy-bank' : 'credit-card-2-back';
const options = (items, selected) => items.map(item => `<option value="${escape(item.id)}" ${item.id === selected ? 'selected' : ''}>${escape(item.name)}</option>`).join('');

function notify(message) {
  clearTimeout(statusTimer);
  document.querySelector('#status').textContent = message;
  statusTimer = setTimeout(() => { document.querySelector('#status').textContent = ''; }, 5000);
}

function movementTable(movements, compact = false) {
  if (!movements.length) return '<p class="empty-state">No hay movimientos en este periodo.</p>';
  const columns = [['date', 'Fecha'], ['category', 'Categoría'], ['note', 'Comentario'], ['account', 'Cuenta'], ['kind', 'Tipo'], ['amount', 'Cantidad']];
  const headers = compact ? '' : `<div class="card-list__headers"><ul class="card-list__labels"><li><label class="card-list__select-all"><input type="checkbox" id="select-all"><span class="sr-only">Seleccionar todos</span></label></li>${columns.map(([key, label]) => `<li><button type="button" class="movement-sort" data-sort="${key}" aria-label="${label}: ordenar ${movementSort.key === key && movementSort.direction === -1 ? 'ascendente' : 'descendente'}">${label}${icon(movementSort.key === key && movementSort.direction === 1 ? 'caret-up-fill' : 'caret-down-fill')}</button></li>`).join('')}</ul></div>`;
  return `${headers}<div class="card-list__lista-movimientos${compact ? ' card-list__lista-movimientos--resumen' : ''}">${compact ? '<div class="card-list__header--resumen"><h2>Últimos movimientos</h2><a href="#movimientos">Ver más movimientos &gt;</a></div>' : ''}${movements.map(movement => {
    const category = state.categories.find(item => item.id === movement.category);
    const account = state.accounts.find(item => item.id === movement.account);
    const date = movement.date.split('-').reverse().join('/');
    const amount = `${movement.type === 'Gasto' ? '−' : '+'} ${money(movement.amount)}`;
    const style = movement.type === 'Gasto' ? 'font-gasto' : 'font-ingreso';
    const opening = compact ? `<button type="button" data-edit="movements"` : '<label';
    return `${opening} class="mov-card mov-card--clickable${compact ? ' mov-card--resumen' : ''}" data-id="${escape(movement.id)}"><span class="sr-only">${compact ? 'Editar' : 'Seleccionar'} </span><div class="mov-card__body"><div class="mov-card__row${compact ? ' mov-card__row--mobile' : ''}">${compact ? '' : `<input type="checkbox" class="select-movimiento mov-card__checkbox" data-id="${escape(movement.id)}">`}<span class="mov-card__date">${date}</span><span class="mov-card__category">${escape(category.name)}</span><span class="mov-card__comment${compact ? '' : ' only-desktop'}">${escape(movement.note || '—')}</span><span class="mov-card__account ${compact ? 'mov-card__account--desktop' : ''}">${icon(accountIcon(account))}${escape(account.name)}</span>${compact ? '' : `<span class="mov-card__type">${escape(movement.kind)}</span>`}<span class="mov-card__amount ${compact ? 'mov-card__amount--desktop' : 'only-desktop'} ${style}">${amount}</span></div><div class="mov-card__row ${compact ? 'mov-card__row--mobile--bottom' : 'only-mobile'}"><span class="${compact ? 'mov-card__account' : 'mov-card__comment'}">${compact ? `${icon(accountIcon(account))}${escape(account.name)}` : escape(movement.note || '—')}</span><span class="mov-card__amount ${style}">${amount}</span></div></div></${compact ? 'button' : 'label'}>`;
  }).join('')}</div>`;
}

function renderSummary() {
  const totals = summary(state, month);
  const spending = Object.entries(totals.spending).sort((first, second) => second[1] - first[1]);
  const top = spending[0];
  view.innerHTML = `<section class="card-list__cuentas-wrapper" aria-label="Saldos por cuenta"><ul class="card-list__cuentas">${state.accounts.map((account, index) => `<li class="small-card__cuentas" id="cuenta-${index + 1}"><a href="#cuentas"><span class="icon-wrapper">${icon(accountIcon(account))}</span><div class="small-card__content">${escape(account.name)}<span class="small-card__saldo">${money(totals.balances[account.id])}</span></div></a></li>`).join('')}</ul></section>
    <section class="dashboard-movimientos__wrapper" aria-label="Resumen financiero">
      <div id="saldo-total" class="small-card small-card--white"><h2 class="small-card__title">Saldo total</h2><div class="small-card__content"><span class="icon-wrapper">${icon('credit-card-2-back')}</span><p class="saldo-total">${money(totals.total)}</p></div></div>
      <div id="balance" class="small-card small-card--white"><h2 class="small-card__title">Balance</h2><div class="small-card__content"><span class="icon-wrapper"><sl-icon src="/assets/img/scale.svg" aria-hidden="true"></sl-icon></span><div class="small-card__balance-section"><div class="small-card__balance-section-info"><p>Ingresos</p><span class="font-ingreso">${money(totals.income)}</span></div><div class="small-card__balance-section-info"><p>Gastos</p><span class="font-gasto">− ${money(totals.expense)}</span></div></div></div></div>
      <div id="gasto-mas-mes" class="small-card small-card--white"><h2 class="small-card__title">Este mes he gastado más en:</h2><div class="small-card__content"><span class="icon-wrapper">${icon('currency-euro')}</span><div><p class="font-ingreso">${top ? escape(state.categories.find(category => category.id === top[0]).name) : 'Ninguna'}</p><p class="font-gasto font-weight-bold">− ${money(top?.[1] ?? 0)}</p></div></div></div>
      <div id="ultimos-movimientos">${movementTable(filteredMovements(state, { month }).slice(0, 10), true)}</div>
      <div id="gastos-por-categoria"><a href="#movimientos"><div class="small-card small-card--white categoria-list"><h2 class="categoria-title">Gastos por categoría</h2>${spending.length ? `<ul>${spending.map(([categoryId, amount]) => `<li class="categoria-item"><span>${escape(state.categories.find(category => category.id === categoryId).name)}</span><span class="categoria-valor">− ${money(amount)}</span></li>`).join('')}</ul>` : '<p class="empty-state">Sin gastos en este periodo.</p>'}</div></a></div>
    </section>`;
}

function renderMovements() {
  filters = { month: filters.month, type: '', account: '', search: '' };
  view.innerHTML = `<div class="movimientos-controls movimientos-controls--design" aria-label="Acciones de movimientos"><div class="movement-actions"><sl-button id="edit-selected" class="btn btn-edit btn-disabled" variant="primary" size="small" pill disabled>Editar</sl-button><sl-button id="delete-selected" class="btn btn-delete btn-disabled" variant="primary" size="small" pill disabled>Eliminar</sl-button></div><label class="movement-month"><span aria-hidden="true">Cambiar mes ${icon('caret-down-fill')}</span><span class="sr-only">Cambiar mes</span><input id="filter-month" type="month" value="${filters.month}" aria-label="Cambiar mes"></label><span id="selection-count" class="sr-only" role="status"></span></div><div id="movement-results"></div>`;
  renderMovementResults();
}

function renderMovementResults() {
  const monthControl = view.querySelector('.movement-month');
  view.querySelector('.movimientos-controls').append(monthControl);
  selectedMovements.clear();
  const movements = filteredMovements(state, filters);
  const sortValue = movement => {
    if (movementSort.key === 'category') return state.categories.find(category => category.id === movement.category).name;
    if (movementSort.key === 'account') return state.accounts.find(account => account.id === movement.account).name;
    if (movementSort.key === 'amount') return movement.type === 'Gasto' ? -movement.amount : movement.amount;
    return movement[movementSort.key];
  };
  movements.sort((first, second) => {
    const firstValue = sortValue(first);
    const secondValue = sortValue(second);
    return movementSort.direction * (typeof firstValue === 'number' ? firstValue - secondValue : firstValue.localeCompare(secondValue, 'es'));
  });
  document.querySelector('#movement-results').innerHTML = `<p class="sr-only">${movements.length} movimientos</p>${movementTable(movements)}`;
  positionMonthControl();
  updateMovementSelection();
}

function positionMonthControl() {
  const monthControl = view.querySelector('.movement-month');
  if (!monthControl) return;
  if (window.matchMedia('(max-width: 768px)').matches) {
    let header = view.querySelector('.card-list__headers');
    if (!header) {
      header = document.createElement('div');
      header.className = 'card-list__headers';
      view.querySelector('#movement-results').prepend(header);
    }
    header.append(monthControl);
  } else {
    view.querySelector('.movimientos-controls').append(monthControl);
  }
}

window.matchMedia('(max-width: 768px)').addEventListener('change', positionMonthControl);

function updateMovementSelection() {
  const checkboxes = [...view.querySelectorAll('.select-movimiento')];
  for (const checkbox of checkboxes) {
    checkbox.checked = selectedMovements.has(checkbox.dataset.id);
    checkbox.closest('.mov-card').classList.toggle('mov-card--selected', checkbox.checked);
  }
  const all = view.querySelector('#select-all');
  if (all) {
    all.checked = checkboxes.length > 0 && selectedMovements.size === checkboxes.length;
    all.indeterminate = selectedMovements.size > 0 && !all.checked;
  }
  for (const [id, disabled] of [['edit-selected', selectedMovements.size !== 1], ['delete-selected', selectedMovements.size === 0]]) {
    const button = view.querySelector(`#${id}`);
    if (!button) continue;
    button.disabled = disabled;
    button.classList.toggle('btn-disabled', disabled);
  }
  view.querySelector('#selection-count').textContent = selectedMovements.size ? `${selectedMovements.size} ${selectedMovements.size === 1 ? 'seleccionado' : 'seleccionados'}` : '';
}

function renderEntities() {
  const isAccount = page === 'cuentas';
  const collection = isAccount ? 'accounts' : 'categories';
  view.innerHTML = `<ul class="entity-list" aria-label="${isAccount ? 'Cuentas' : 'Categorías'}">${state[collection].map(item => `<li>${isAccount ? icon(accountIcon(item)) : ''}<span class="entity-name">${escape(item.name)}</span></li>`).join('')}</ul>${state[collection].length ? '' : '<p class="empty-state">No hay elementos todavía.</p>'}`;
}

function render() {
  const titles = { resumen: 'Resumen', movimientos: 'Movimientos', cuentas: 'Cuentas', categorias: 'Categorías' };
  page = Object.hasOwn(titles, location.hash.slice(1)) ? location.hash.slice(1) : 'resumen';
  document.title = `${titles[page]} | LaButxaka Demo`;
  document.querySelector('#page-title').textContent = titles[page];
  document.querySelectorAll('nav a').forEach(link => {
    link.classList.toggle('sidebar-active', link.hash === `#${page}`);
    if (link.hash === `#${page}`) link.setAttribute('aria-current', 'page');
    else link.removeAttribute('aria-current');
  });
  if (page === 'resumen') renderSummary();
  else if (page === 'movimientos') renderMovements();
  else renderEntities();
}

function typeControl(selected) {
  return `<sl-radio-group class="radio-tabs" label="" name="type" value="${selected}" required><sl-radio-button value="Gasto">Gasto</sl-radio-button><sl-radio-button value="Ingreso">Ingreso</sl-radio-button><sl-radio-button value="Transferencia" disabled>Transferencia</sl-radio-button></sl-radio-group>`;
}

async function openEditor(collection, id) {
  editorOpener = document.activeElement;
  await Promise.all(['sl-dialog', 'sl-input', 'sl-select', 'sl-option', 'sl-radio-group', 'sl-radio', 'sl-radio-button', 'sl-button'].map(name => customElements.whenDefined(name)));
  const item = state[collection].find(item => item.id === id);
  editing = { collection, id: item?.id ?? crypto.randomUUID() };
  const label = collection === 'accounts' ? 'cuenta' : collection === 'categories' ? 'categoría' : 'movimiento';
  editor.label = collection === 'movements' ? `${item ? 'Editar' : 'Nuevo'} registro` : `${item ? 'Editar' : 'Nueva'} ${label}`;
  document.querySelector('#form-error').textContent = '';
  let fields;
  if (collection === 'movements') {
    const type = item?.type ?? 'Gasto';
    const today = new Date();
    const date = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
    fields = `${typeControl(type)}<div class="form__fields">
      <sl-input type="date" label="Fecha" name="date" value="${item?.date ?? date}" required></sl-input>
      <sl-radio-group class="radio__tipo" label="Tipo" name="kind" value="${item?.kind ?? 'Fijo'}" required><sl-radio value="Fijo">Fijo</sl-radio><sl-radio value="Variable">Variable</sl-radio></sl-radio-group>
      <sl-select name="account" placeholder="Elige una cuenta" label="Cuenta" value="${item?.account ?? ''}" required>${state.accounts.map(account => `<sl-option value="${escape(account.id)}">${icon(accountIcon(account))} ${escape(account.name)}</sl-option>`).join('')}</sl-select>
      <sl-select name="category" placeholder="Elige una categoría" label="Categoría" value="${item?.category ?? ''}" required>${state.categories.filter(category => category.type === type).map(category => `<sl-option value="${escape(category.id)}">${escape(category.name)}</sl-option>`).join('')}</sl-select>
      </div><div class="form__fields form__fields--cantidad"><sl-input type="number" name="amount" label="Cantidad" placeholder="Escribe una cantidad" inputmode="decimal" min="0.01" max="9999999.99" step="0.01" value="${item ? (item.amount / 100).toFixed(2) : ''}" required></sl-input><sl-select name="currency" disabled placeholder="EUR" label="Moneda" value="EUR"><sl-option value="EUR">EUR</sl-option></sl-select></div>
      <sl-input type="text" name="note" label="Comentario" maxlength="120" value="${escape(item?.note ?? '')}"></sl-input>`;
  } else if (collection === 'accounts') {
    fields = `<label>Nombre<input name="name" value="${escape(item?.name ?? '')}" maxlength="50" required></label><div class="form-grid"><label>Tipo de cuenta<select name="type">${['Banco', 'Ahorro', 'Efectivo'].map(type => `<option ${item?.type === type ? 'selected' : ''}>${type}</option>`).join('')}</select></label><label>Saldo inicial (EUR)<input name="opening" inputmode="decimal" value="${item ? (item.opening / 100).toFixed(2) : '0.00'}" required maxlength="12"></label></div>`;
  } else {
    const hasMovements = item && state.movements.some(movement => movement.category === item.id);
    fields = `<label>Nombre<input name="name" value="${escape(item?.name ?? '')}" maxlength="50" required></label><label>Tipo<select name="type" ${hasMovements ? 'disabled' : ''}><option ${item?.type !== 'Ingreso' ? 'selected' : ''}>Gasto</option><option ${item?.type === 'Ingreso' ? 'selected' : ''}>Ingreso</option></select></label>`;
  }
  document.querySelector('#form-fields').innerHTML = fields;
  document.querySelector('#delete-edited').hidden = !item || collection !== 'movements';
  await Promise.all([...form.querySelectorAll('sl-input, sl-select, sl-radio-group')].map(control => control.updateComplete));
  editor.show();
}

function askConfirmation(title, message, label, action) {
  pendingAction = action;
  document.querySelector('#confirmation-title').textContent = title;
  document.querySelector('#confirmation-text').textContent = message;
  document.querySelector('#confirm-action').textContent = label;
  confirmation.showModal();
}

form.addEventListener('sl-change', event => {
  if (editing.collection === 'movements' && event.target.name === 'type') {
    const categorySelect = form.querySelector('[name="category"]');
    categorySelect.value = '';
    categorySelect.innerHTML = state.categories.filter(category => category.type === event.target.value).map(category => `<sl-option value="${escape(category.id)}">${escape(category.name)}</sl-option>`).join('');
  }
});

form.addEventListener('submit', event => {
  event.preventDefault();
  try {
    const fields = new FormData(form);
    const { collection, id } = editing;
    if (collection === 'movements') {
      saveMovement(state, { id, type: fields.get('type'), kind: fields.get('kind'), amount: parseMoney(fields.get('amount')), date: fields.get('date'), account: fields.get('account'), category: fields.get('category'), note: fields.get('note').trim() });
    } else {
      const name = fields.get('name').trim();
      if (!name) throw new Error('Introduce un nombre.');
      const index = state[collection].findIndex(item => item.id === id);
      if (state[collection].some(item => item.id !== id && item.name.toLocaleLowerCase('es') === name.toLocaleLowerCase('es'))) throw new Error('Ya existe un elemento con ese nombre.');
      const previous = state[collection][index];
      const item = collection === 'accounts' ? { id, name, type: fields.get('type'), opening: parseMoney(fields.get('opening'), true) } : { id, name, type: fields.get('type') ?? previous.type, icon: previous?.icon ?? 'tag' };
      if (index === -1) state[collection].push(item);
      else state[collection][index] = item;
    }
    editor.hide();
    render();
    notify('Cambios guardados.');
  } catch (error) {
    document.querySelector('#form-error').textContent = error.message;
  }
});

view.addEventListener('click', event => {
  const sort = event.target.closest('[data-sort]');
  if (sort) {
    movementSort = { key: sort.dataset.sort, direction: movementSort.key === sort.dataset.sort ? -movementSort.direction : -1 };
    renderMovementResults();
    view.querySelector(`[data-sort="${movementSort.key}"]`).focus();
  }
  if (event.target.closest('#edit-selected') && selectedMovements.size === 1) {
    openEditor('movements', [...selectedMovements][0]);
  }
  if (event.target.closest('#delete-selected') && selectedMovements.size) {
    const ids = [...selectedMovements];
    askConfirmation(ids.length === 1 ? '¿Eliminar este movimiento?' : `¿Eliminar ${ids.length} movimientos?`, 'Esta acción no se puede deshacer.', 'Eliminar', () => {
      for (const id of ids) removeEntity(state, 'movements', id);
      render();
      notify(ids.length === 1 ? 'Movimiento eliminado.' : `${ids.length} movimientos eliminados.`);
    });
  }
  const create = event.target.closest('[data-create]');
  if (create) openEditor(create.dataset.create);
  const edit = event.target.closest('[data-edit]');
  const remove = event.target.closest('[data-delete]');
  if (edit) openEditor(edit.dataset.edit, edit.dataset.id);
  if (remove) {
    const collection = remove.dataset.delete;
    const id = remove.dataset.id;
    const field = collection === 'accounts' ? 'account' : 'category';
    if (collection !== 'movements' && state.movements.some(movement => movement[field] === id)) {
      notify('No se puede eliminar: tiene movimientos asociados.');
      return;
    }
    askConfirmation('¿Eliminar este elemento?', 'Esta acción no se puede deshacer.', 'Eliminar', () => { removeEntity(state, collection, id); render(); notify('Elemento eliminado.'); });
  }
  if (event.target.closest('#clear-filters')) {
    filters = { month: '', type: '', account: '', search: '' };
    renderMovements();
  }
});

view.addEventListener('input', event => {
  if (event.target.id === 'filter-search') { filters.search = event.target.value; renderMovementResults(); }
});
view.addEventListener('change', event => {
  if (event.target.matches('.select-movimiento')) {
    if (event.target.checked) selectedMovements.add(event.target.dataset.id);
    else selectedMovements.delete(event.target.dataset.id);
    updateMovementSelection();
  }
  if (event.target.id === 'select-all') {
    selectedMovements.clear();
    if (event.target.checked) view.querySelectorAll('.select-movimiento').forEach(checkbox => selectedMovements.add(checkbox.dataset.id));
    updateMovementSelection();
  }
  const keys = { 'filter-month': 'month', 'filter-type': 'type', 'filter-account': 'account' };
  if (keys[event.target.id]) { filters[keys[event.target.id]] = event.target.value; renderMovementResults(); }
});
document.querySelector('#create').addEventListener('click', () => openEditor('movements'));
document.querySelector('#delete-edited').addEventListener('click', async () => {
  const { collection, id } = editing;
  const field = collection === 'accounts' ? 'account' : 'category';
  if (collection !== 'movements' && state.movements.some(movement => movement[field] === id)) {
    document.querySelector('#form-error').textContent = 'No se puede eliminar: tiene movimientos asociados.';
    return;
  }
  await editor.hide();
  askConfirmation('¿Eliminar este elemento?', 'Esta acción no se puede deshacer.', 'Eliminar', () => { removeEntity(state, collection, id); render(); notify('Elemento eliminado.'); });
});
document.querySelectorAll('[data-close]').forEach(button => button.addEventListener('click', () => editor.hide()));
editor.addEventListener('keydown', event => {
  if (event.key !== 'Escape' || !editor.open) return;
  if ([...editor.querySelectorAll('sl-select')].some(select => select.open)) return;
  event.preventDefault();
  event.stopPropagation();
  editor.hide();
}, { capture: true });
editor.addEventListener('sl-after-hide', event => {
  if (event.target !== editor || confirmation.open) return;
  if (editorOpener?.isConnected && editorOpener !== document.body) editorOpener.focus();
  else document.querySelector('#create').focus();
});
confirmation.addEventListener('close', () => document.querySelector('#create').focus());
document.querySelector('#cancel-confirmation').addEventListener('click', () => confirmation.close());
document.querySelector('#confirmation-form').addEventListener('submit', event => {
  event.preventDefault();
  confirmation.close();
  pendingAction?.();
  pendingAction = null;
});
document.querySelectorAll('[data-reset]').forEach(control => control.addEventListener('click', () => {
  document.querySelector('#navigation-drawer').hide();
  askConfirmation('¿Reiniciar la demo?', 'Se descartarán los cambios de esta visita y se recuperarán los datos ficticios iniciales.', 'Reiniciar', () => {
  state = createDemo();
  month = currentMonth();
  filters = { month, type: '', account: '', search: '' };
  render();
  notify('Demo reiniciada.');
  });
}));
document.querySelector('#mobile-navigation').innerHTML = document.querySelector('.layout-sidebar nav').innerHTML;
document.querySelector('#open-navigation').addEventListener('click', () => document.querySelector('#navigation-drawer').show());
document.querySelector('#mobile-navigation').addEventListener('click', event => { if (event.target.closest('a')) document.querySelector('#navigation-drawer').hide(); });
window.addEventListener('hashchange', () => { render(); document.querySelector('#page-title').setAttribute('tabindex', '-1'); document.querySelector('#page-title').focus(); });
render();
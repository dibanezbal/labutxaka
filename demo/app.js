import { createDemo, currentMonth, parseMoney, summary, filteredMovements, saveMovement, removeEntity } from './model.js';

let state = createDemo();
let page = 'resumen';
let month = currentMonth();
let filters = { month, type: '', account: '', search: '' };
let editing = null;
let pendingAction = null;
let statusTimer;
const view = document.querySelector('#view');
const editor = document.querySelector('#editor');
const form = document.querySelector('#editor-form');
const confirmation = document.querySelector('#confirmation');
const money = amount => new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(amount / 100);
const escape = value => String(value).replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[character]);
const icon = name => `<sl-icon name="${escape(name)}" aria-hidden="true"></sl-icon>`;
const accountIcon = account => account.type === 'Efectivo' ? 'coin' : account.type === 'Ahorro' ? 'piggy-bank' : 'credit-card-2-back';
const options = (items, selected) => items.map(item => `<option value="${escape(item.id)}" ${item.id === selected ? 'selected' : ''}>${escape(item.name)}</option>`).join('');
const actions = (collection, item) => `<div class="row-actions"><button class="icon-button" type="button" data-edit="${collection}" data-id="${escape(item.id)}" title="Editar" aria-label="Editar ${escape(item.name ?? item.note)}">${icon('pencil')}</button><button class="icon-button" type="button" data-delete="${collection}" data-id="${escape(item.id)}" title="Eliminar" aria-label="Eliminar ${escape(item.name ?? item.note)}">${icon('trash3')}</button></div>`;

function notify(message) {
  clearTimeout(statusTimer);
  document.querySelector('#status').textContent = message;
  statusTimer = setTimeout(() => { document.querySelector('#status').textContent = ''; }, 5000);
}

function movementTable(movements, compact = false) {
  if (!movements.length) return '<p class="empty-state">No hay movimientos en este periodo.</p>';
  const headers = compact ? '' : '<div class="card-list__headers"><ul class="card-list__labels"><li>Fecha</li><li>Categoría</li><li>Comentario</li><li>Cuenta</li><li>Tipo</li><li>Cantidad</li></ul></div>';
  return `${headers}<div class="card-list__lista-movimientos${compact ? ' card-list__lista-movimientos--resumen' : ''}">${compact ? '<div class="card-list__header--resumen"><h3>Últimos movimientos</h3><a href="#movimientos">Ver más movimientos &gt;</a></div>' : ''}${movements.map(movement => {
    const category = state.categories.find(item => item.id === movement.category);
    const account = state.accounts.find(item => item.id === movement.account);
    const date = movement.date.split('-').reverse().join('/');
    const amount = `${movement.type === 'Gasto' ? '−' : '+'} ${money(movement.amount)}`;
    const style = movement.type === 'Gasto' ? 'font-gasto' : 'font-ingreso';
    return `<button type="button" class="mov-card mov-card--clickable${compact ? ' mov-card--resumen' : ''}" data-edit="movements" data-id="${escape(movement.id)}" aria-label="Editar ${escape(movement.note || category.name)}"><div class="mov-card__body"><div class="mov-card__row${compact ? ' mov-card__row--mobile' : ''}"><span class="mov-card__date">${date}</span><span class="mov-card__category">${escape(category.name)}</span><span class="mov-card__comment${compact ? '' : ' only-desktop'}">${escape(movement.note || '—')}</span><span class="mov-card__account ${compact ? 'mov-card__account--desktop' : ''}">${icon(accountIcon(account))}${escape(account.name)}</span>${compact ? '' : `<span class="mov-card__type">${escape(movement.kind)}</span>`}<span class="mov-card__amount ${compact ? 'mov-card__amount--desktop' : 'only-desktop'} ${style}">${amount}</span></div><div class="mov-card__row ${compact ? 'mov-card__row--mobile--bottom' : 'only-mobile'}"><span class="${compact ? 'mov-card__account' : 'mov-card__comment'}">${compact ? `${icon(accountIcon(account))}${escape(account.name)}` : escape(movement.note || '—')}</span><span class="mov-card__amount ${style}">${amount}</span></div></div></button>`;
  }).join('')}</div>`;
}

function renderSummary() {
  const totals = summary(state, month);
  const spending = Object.entries(totals.spending).sort((first, second) => second[1] - first[1]);
  const top = spending[0];
  view.innerHTML = `<div class="demo-period"><label for="summary-month">Periodo</label><input id="summary-month" type="month" value="${month}" aria-label="Periodo del resumen"></div>
    <section class="card-list__cuentas-wrapper" aria-label="Saldos por cuenta"><ul class="card-list__cuentas">${state.accounts.map((account, index) => `<li class="small-card__cuentas" id="cuenta-${index + 1}"><a href="#cuentas"><span class="icon-wrapper">${icon(accountIcon(account))}</span><div class="small-card__content">${escape(account.name)}<span class="small-card__saldo">${money(totals.balances[account.id])}</span></div></a></li>`).join('')}</ul></section>
    <section class="dashboard-movimientos__wrapper" aria-label="Resumen financiero">
      <div id="saldo-total" class="small-card small-card--white"><h3 class="small-card__title">Saldo total</h3><div class="small-card__content"><span class="icon-wrapper">${icon('credit-card-2-back')}</span><p class="saldo-total">${money(totals.total)}</p></div></div>
      <div id="balance" class="small-card small-card--white"><h3 class="small-card__title">Balance</h3><div class="small-card__content"><span class="icon-wrapper"><sl-icon src="/assets/img/scale.svg" aria-hidden="true"></sl-icon></span><div class="small-card__balance-section"><div class="small-card__balance-section-info"><p>Ingresos</p><span class="font-ingreso">${money(totals.income)}</span></div><div class="small-card__balance-section-info"><p>Gastos</p><span class="font-gasto">− ${money(totals.expense)}</span></div></div></div></div>
      <div id="gasto-mas-mes" class="small-card small-card--white"><h3 class="small-card__title">Este mes he gastado más en:</h3><div class="small-card__content"><span class="icon-wrapper">${icon('currency-euro')}</span><div><p class="font-ingreso">${top ? escape(state.categories.find(category => category.id === top[0]).name) : 'Ninguna'}</p><p class="font-gasto font-weight-bold">− ${money(top?.[1] ?? 0)}</p></div></div></div>
      <div id="ultimos-movimientos">${movementTable(filteredMovements(state, { month }).slice(0, 10), true)}</div>
      <div id="gastos-por-categoria"><a href="#movimientos"><div class="small-card small-card--white categoria-list"><h3 class="categoria-title">Gastos por categoría</h3>${spending.length ? `<ul>${spending.map(([categoryId, amount]) => `<li class="categoria-item"><span>${escape(state.categories.find(category => category.id === categoryId).name)}</span><span class="categoria-valor">− ${money(amount)}</span></li>`).join('')}</ul>` : '<p class="empty-state">Sin gastos en este periodo.</p>'}</div></a></div>
    </section>`;
}

function renderMovements() {
  view.innerHTML = `<div class="filters">
    <label>Buscar<input id="filter-search" type="search" maxlength="120" placeholder="Buscar movimiento" value="${escape(filters.search)}"></label>
    <label>Periodo<input id="filter-month" type="month" value="${filters.month}"></label>
    <label>Tipo<select id="filter-type"><option value="">Todos</option><option ${filters.type === 'Gasto' ? 'selected' : ''}>Gasto</option><option ${filters.type === 'Ingreso' ? 'selected' : ''}>Ingreso</option></select></label>
    <label>Cuenta<select id="filter-account"><option value="">Todas</option>${options(state.accounts, filters.account)}</select></label>
    <button class="quiet-button" type="button" id="clear-filters" title="Limpiar filtros" aria-label="Limpiar filtros">${icon('funnel')}Limpiar</button>
    </div><div id="movement-results"></div>`;
  renderMovementResults();
}

function renderMovementResults() {
  const movements = filteredMovements(state, filters);
  document.querySelector('#movement-results').innerHTML = `<p class="result-count">${movements.length} movimientos</p>${movementTable(movements)}`;
}

function renderEntities() {
  const isAccount = page === 'cuentas';
  const collection = isAccount ? 'accounts' : 'categories';
  view.innerHTML = `<ul class="entity-list" aria-label="${isAccount ? 'Cuentas' : 'Categorías'}">${state[collection].map(item => `<li>${isAccount ? icon(accountIcon(item)) : ''}<span class="entity-name">${escape(item.name)}</span>${actions(collection, item)}</li>`).join('')}</ul>${state[collection].length ? '' : '<p class="empty-state">No hay elementos todavía.</p>'}<div class="entity-actions"><button type="button" class="quiet-button" data-create="${collection}">${icon('plus-lg')}${isAccount ? 'Nueva cuenta' : 'Nueva categoría'}</button></div>`;
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
  document.querySelector('#delete-edited').hidden = !item;
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
  if (event.target.id === 'summary-month') {
    month = event.target.value;
    filters.month = month;
    renderSummary();
  }
  const keys = { 'filter-month': 'month', 'filter-type': 'type', 'filter-account': 'account' };
  if (keys[event.target.id]) { filters[keys[event.target.id]] = event.target.value; renderMovementResults(); }
});
document.querySelector('#create').addEventListener('click', () => openEditor('movements'));
document.querySelector('#delete-edited').addEventListener('click', () => {
  const { collection, id } = editing;
  const field = collection === 'accounts' ? 'account' : 'category';
  if (collection !== 'movements' && state.movements.some(movement => movement[field] === id)) {
    document.querySelector('#form-error').textContent = 'No se puede eliminar: tiene movimientos asociados.';
    return;
  }
  editor.hide();
  askConfirmation('¿Eliminar este elemento?', 'Esta acción no se puede deshacer.', 'Eliminar', () => { removeEntity(state, collection, id); render(); notify('Elemento eliminado.'); });
});
document.querySelectorAll('[data-close]').forEach(button => button.addEventListener('click', () => editor.hide()));
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
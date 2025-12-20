const cardList = document.querySelector('card-list');

const editBtn = document.getElementById('btn-open-edit-form');
const deleteBtn = document.getElementById('btn-open-delete-modal');

const editDialog = document.getElementById('edit-dialog');
const editContent = document.getElementById('edit-dialog-content');

const createDialog = document.getElementById('create-dialog');
const createContent = document.getElementById('create-dialog-content');

const deleteDialog = document.getElementById('delete-dialog');
const deleteContent = document.getElementById('delete-dialog-content');

function toggleBtn(btn, enabled) {
  btn?.classList.toggle('btn-disabled', !enabled);
}

// Estado inicial de los botones editar y eliminar
toggleBtn(editBtn, (cardList?.getSelectedIds()?.length || 0) === 1);
toggleBtn(deleteBtn, (cardList?.getSelectedIds()?.length || 0) >= 1);


// Sincroniza botones con la selección
cardList?.addEventListener('selection-change', (e) => {
  const ids = e.detail.ids || [];
  toggleBtn(editBtn, ids.length === 1);
  toggleBtn(deleteBtn, ids.length >= 1);  

  // Resalta las tarjetas seleccionadas
  cardList.querySelectorAll('.mov-card--selected').forEach(card => card.classList.remove('mov-card--selected'));
  ids.forEach(id => {
    const card = cardList.querySelector(`.mov-card[data-id="${id}"]`);
    if (card) card.classList.add('mov-card--selected');
  });
});

// MODAL: Crear nuevo movimiento
document.getElementById('btn-open-create-form')?.addEventListener('click', async () => {
  const res = await fetch('?c=movimientos&a=create', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
  createContent.innerHTML = await res.text();
  const form = createContent.querySelector('form');
  form?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formDataCreate = new FormData(form);
    const resp = await fetch('?c=movimientos&a=save', { method: 'POST', body: formDataCreate, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    if (!resp.ok) { 
      alert('Error al guardar'); 
      return; 
    }
    createDialog.hide();
    location.href = '?c=movimientos&a=index&ts=' + Date.now();
  }, { once: true });
  createDialog.show();
});
document.getElementById('create-cancel')?.addEventListener('click', () => createDialog.hide());

// MODAL: Editar movimiento (requiere sólo un item seleccionado)
editBtn?.addEventListener('click', async (e) => {
  if (editBtn.classList.contains('btn-disabled')) { e.preventDefault(); return; }
  const ids = cardList?.getSelectedIds() || [];
  if (ids.length !== 1) return;
  const id = ids[0];

  const res = await fetch(`?c=movimientos&a=edit&id=${encodeURIComponent(id)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
  editContent.innerHTML = await res.text();
  const form = editContent.querySelector('form');
  form?.addEventListener('submit', async (ev) => {
    ev.preventDefault();
    const formDataEdit = new FormData(form);
    const resp = await fetch('?c=movimientos&a=update', { method: 'POST', body: formDataEdit, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    if (!resp.ok) { alert('Error al actualizar'); return; }
    editDialog.hide();
    location.href = '?c=movimientos&a=index&ts=' + Date.now();
  }, { once: true });
  editDialog.show();
});
document.getElementById('edit-cancel')?.addEventListener('click', () => editDialog.hide());

// MODAL: Editar movimiento desde el resumen. 
document.querySelectorAll('.mov-card--resumen').forEach(card => {
  card.addEventListener('click', async () => {
    const id = card.dataset.id;
    const res = await fetch(`?c=movimientos&a=edit&id=${encodeURIComponent(id)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    editContent.innerHTML = await res.text();
    const form = editContent.querySelector('form');
    form?.addEventListener('submit', async (ev) => {
      ev.preventDefault();
      const formDataEdit = new FormData(form);
      const resp = await fetch('?c=movimientos&a=update', { method: 'POST', body: formDataEdit, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!resp.ok) { alert('Error al actualizar'); return; }
      editDialog.hide();
      location.href = '?c=movimientos&a=resumen&ts=' + Date.now();
    }, { once: true });
    editDialog.show();
  });
});

// MODAL: Eliminar movimientos (múltiples items seleccionados)
deleteBtn?.addEventListener('click', () => {
  if (deleteBtn.classList.contains('btn-disabled')) return;
  const ids = cardList?.getSelectedIds() || [];
  if (!ids.length) return;

  deleteContent.textContent = `¿Deseas eliminar ${ids.length} registro(s) seleccionado(s)?`;
  deleteDialog.dataset.ids = JSON.stringify(ids);
  deleteDialog.show();
});

// MODAL: Botón Cancelar Eliminar

document.getElementById('delete-cancel')?.addEventListener('click', () => {
  deleteDialog.hide();
  deleteDialog.dataset.ids = '';

  const selectedCheckboxes = document.querySelectorAll('.select-movimiento:checked');
  selectedCheckboxes.forEach(checkbox => { checkbox.checked = false; });
});

// MODAL: Botón Aceptar Eliminar

document.getElementById('delete-accept')?.addEventListener('click', async () => {
  const ids = JSON.parse(deleteDialog.dataset.ids || '[]');
  if (!ids.length) { deleteDialog.hide(); return; }

  // Enviar como FormData: ids[]=
  const formDataDelete = new FormData();
  ids.forEach(id => formDataDelete.append('ids[]', id));

  const resp = await fetch('?c=movimientos&a=delete', {
    method: 'POST',
    body: formDataDelete,
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  });
  const text = await resp.text();
  if (!resp.ok) { console.error('Delete error:', resp.status, text); alert('Error al eliminar'); return; }

  deleteDialog.hide();
  location.href = '?c=movimientos&a=index&ts=' + Date.now();
});

// CHECKBOX: Seleccionar todos los movimientos
const allSelectedIds = [];
const selectAll = document.getElementById('select-all');
selectAll?.addEventListener('change', (e) => {

  e.preventDefault();

  if (!cardList) return;

  allSelectedIds.length = 0;

  const allCheckboxes = document.querySelectorAll('.select-movimiento');
  allCheckboxes.forEach(checkbox => { 
    checkbox.checked = e.target.checked; 
    if (e.target.checked) {
      allSelectedIds.push(checkbox.dataset.id);
    }
  });
  
  cardList?.dispatchEvent(new CustomEvent('selection-change', { detail: { ids: allSelectedIds } }));
});
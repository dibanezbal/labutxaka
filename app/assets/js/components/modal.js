const createDialog = document.getElementById('create-dialog');
const createContent = document.getElementById('create-dialog-content');

const editBtn = document.getElementById('btn-open-edit-form');
const editDialog = document.getElementById('edit-dialog');
const editContent = document.getElementById('edit-dialog-content');

const deleteBtn = document.getElementById('btn-open-delete-modal');
const deleteDialog = document.getElementById('delete-dialog');
const deleteContent = document.getElementById('delete-dialog-content');

function getCheckedItem() {
  const checkedItem = document.querySelector('.select-movimiento:checked');
  return checkedItem?.dataset.id || null;
}

function toggleBtn(btn, enabled) {
  if (!btn) return;
  if (enabled) {
    btn.classList.remove('btn-disabled');
  } else {
    btn.classList.add('btn-disabled');
  }
}

// Estado inicial de los botones
toggleBtn(editBtn, !!getCheckedItem())
toggleBtn(deleteBtn, !!getCheckedItem())

// Listener para caambios de slección

document.querySelectorAll('.select-movimiento').forEach(checkbox => {
  checkbox.addEventListener('change', () => {
    const id = getCheckedItem();
    toggleBtn(editBtn, !!id);
    toggleBtn(deleteBtn, !!id);
  });
});

// Abrir modal para CREATE
document.getElementById('btn-open-create-form')?.addEventListener('click', async () => {
  const res = await fetch('?c=movimientos&a=create', {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  });
  createContent.innerHTML = await res.text();

  const form = createContent.querySelector('form');
  form?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(form);
    const resp = await fetch('?c=movimientos&a=save', {
      method: 'POST',
      body: fd,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    if (!resp.ok) { alert('Error al guardar'); return; }
    createDialog.hide();
    window.location.href = '?c=movimientos&a=index&ts=' + Date.now();
  }, { once: true });

  createDialog.show();
});

document.getElementById('create-cancel')?.addEventListener('click', () => createDialog.hide());

// Abrir EDIT (usa data-id del botón)
document.getElementById('btn-open-edit-form')?.addEventListener('click', async () => {
  // buscar el checkbox marcado dentro del grid
  const checked = document.querySelector('.select-movimiento:checked');
  const id = checked?.dataset.id;

  const url = `?c=movimientos&a=edit&id=${(id)}`;
  const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
  editContent.innerHTML = await res.text();

  const form = editContent.querySelector('form');
  form?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(form);
    const resp = await fetch('?c=movimientos&a=update', {
      method: 'POST',
      body: fd,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    if (!resp.ok) { alert('Error al actualizar'); return; }
    editDialog.hide();
    window.location.href = '?c=movimientos&a=index&ts=' + Date.now();
  });

  editDialog.show();
});

document.getElementById('edit-cancel')?.addEventListener('click', () => editDialog.hide());

// Eliminar (no ejecutar si está deshabilitado)
deleteBtn?.addEventListener('click', async (e) => {
  if (deleteBtn.classList.contains('btn-disabled')) { e.preventDefault(); return; }
  const id = getCheckedItem();
  if (!id) return;

  deleteContent.textContent = `¿Deseas eliminar todos los 
registros seleccionados?`;
  deleteDialog.show();
  
  deleteDialog.dataset.id = id;
});

document.getElementById('delete-cancel')?.addEventListener('click', () => {
  deleteDialog.hide();
  deleteDialog.dataset.id = '';
});

// Aceptar: elimina y recarga
document.getElementById('delete-accept')?.addEventListener('click', async () => {
  const id = deleteDialog.dataset.id;
  if (!id) { deleteDialog.hide(); return; }

  const resp = await fetch(`?c=movimientos&a=delete&id=${encodeURIComponent(id)}`, {
    method: 'GET',
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  });
  if (!resp.ok) { alert('Error al eliminar'); return; }

  // Feedback rápido y cierre
  deleteContent.textContent = 'Eliminado';
  setTimeout(() => {
    deleteDialog.hide();
    // Recargar lista
    window.location.href = '?c=movimientos&a=index&ts=' + Date.now();
  }, 500);
});
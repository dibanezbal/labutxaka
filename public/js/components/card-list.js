class CardList extends HTMLElement {
  selectedId = null;

  connectedCallback() {
    const movimientos = JSON.parse(this.getAttribute("movimientos") || "[]");
    const cuentas     = JSON.parse(this.getAttribute("cuentas") || "{}");
    const categorias  = JSON.parse(this.getAttribute("categorias") || "{}");

    this.classList.add('movs-grid');
    this.innerHTML = '';

    movimientos.forEach(m => {
      const formatDate = m.fecha_registro ? new Date(m.fecha_registro).toLocaleDateString() : '';
      const card = document.createElement('button');
      card.className = 'card-basic';
      card.innerHTML = `
          <input type="checkbox" class="select-movimiento" id="select-mov-${m.id}" data-id="${m.id}">
          <span class="mov-card__fecha">${formatDate ?? ''}</span>
          ${m.comentario ? `<div class="mov-card__row"><span>${m.comentario}</span></div>` : ''}
          <span class="badge">${m.tipo_movimiento ?? ''}</span>
          <span class="badge badge--muted">${m.tipo_registro ?? ''}</span>
          <div class="mov-card__row"><span>${cuentas[m.cuenta_id] ?? '—'}</span></div>
          <div class="mov-card__row"><span>${categorias[m.categoria_id] ?? '—'}</span></div>
          <div class="mov-card__row"><span>${m.cantidad ?? ''}</span></div>
      `;
      this.appendChild(card);
    });

    // Delegación: asegura solo un checkbox marcado a la vez y guarda selectedId
    this.addEventListener('change', (e) => {
      const cb = e.target.closest('.select-movimiento');
      if (!cb) return;

      this.selectedId = cb.checked ? cb.dataset.id : null;
      // Opcional: dispara evento para que otras partes lo consuman
      this.dispatchEvent(new CustomEvent('selection-change', { detail: { id: this.selectedId } }));
    });

    // Acciones
    this.addEventListener("click", async (e) => {
      const delBtn  = e.target.closest(".delete-btn");
      if (delBtn) {
        const id = delBtn.dataset.id;
        if (confirm("¿Seguro que quieres eliminar este movimiento?")){
          await fetch(`?c=movimientos&a=delete&id=${id}`);
          window.location.reload();
        }
      }
    });
  }

  getSelectedId() {
    return this.selectedId;
  }
}

customElements.define("card-list", CardList);
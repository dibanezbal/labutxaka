class CardList extends HTMLElement {
  async connectedCallback() {
    const url = this.dataset.url;
    if (url) {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      this.innerHTML = await res.text();
    }
    this.addEventListener('change', (e) => {
      const cb = e.target.closest('.select-movimiento');
      if (!cb) return;
      const ids = Array.from(this.querySelectorAll('.select-movimiento:checked')).map(checked => checked.dataset.id);
      this.dispatchEvent(new CustomEvent('selection-change', { detail: { ids } }));
    });
  }
  getSelectedIds() {
    return Array.from(this.querySelectorAll('.select-movimiento:checked')).map(checked => checked.dataset.id);
  }
}

customElements.define('card-list', CardList);


class CardList extends HTMLElement {
  static get observedAttributes() {
    return ['data-url'];
  }

  async load() {
    const url = this.getAttribute('data-url');
    if (!url) return;

    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    this.innerHTML = await res.text();

    // Al recargar, vacía selección
    this.dispatchEvent(new CustomEvent('selection-change', { detail: { ids: [] } }));
  }

  async connectedCallback() {
    await this.load();

    this.addEventListener('click', (e) => {

      const checkbox = e.target.closest('.select-movimiento');
      const fullCard = e.target.closest('.mov-card--clickable');
      if (fullCard && !checkbox) {
        const id = fullCard.dataset.id;
        const checkbox = fullCard.querySelector(`.select-movimiento[data-id="${id}"]`);
        if (checkbox) {
          checkbox.checked = !checkbox.checked;
        }
      }

      const ids = Array.from(this.querySelectorAll('.select-movimiento:checked')).map(checked => checked.dataset.id);
      this.dispatchEvent(new CustomEvent('selection-change', { detail: { ids } }));
    });
  }

  async attributeChangedCallback(name, oldValue, newValue) {
    if (name === 'data-url' && oldValue !== newValue && this.isConnected) {
      await this.load();
    }
  }
  getSelectedIds() {
    return Array.from(this.querySelectorAll('.select-movimiento:checked')).map(checked => checked.dataset.id);
  }
}

customElements.define('card-list', CardList);
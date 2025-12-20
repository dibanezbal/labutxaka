class CardList extends HTMLElement {
  async connectedCallback() {
    const url = this.dataset.url;
    if (url) {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      this.innerHTML = await res.text();
    }
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
  getSelectedIds() {
    return Array.from(this.querySelectorAll('.select-movimiento:checked')).map(checked => checked.dataset.id);
  }
}

customElements.define('card-list', CardList);
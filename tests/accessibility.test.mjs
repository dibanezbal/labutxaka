import test from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';

const read = filename => readFileSync(new URL(`../${filename}`, import.meta.url), 'utf8');

test('CSP permite los iconos internos de Shoelace sin ampliar script-src', () => {
  const configuration = read('netlify.toml');
  assert.match(configuration, /connect-src 'self' data: https:\/\/cdn.jsdelivr.net;/);
  assert.match(configuration, /script-src 'self' https:\/\/cdn.jsdelivr.net;/);
});

test('botón de usuario con texto accesible y filas sin etiquetas que oculten contenido', () => {
  assert.match(read('demo/index.html'), /<span class="sr-only">Usuario demo<\/span>/);
  const application = read('demo/app.js');
  assert.doesNotMatch(application, /data-edit="movements"[^>]*aria-label/);
  assert.doesNotMatch(application, /<h3/);
});

test('el campo mes limita su ancho al espacio disponible', () => {
  assert.match(read('demo/fidelity.css'), /input\[type="month"\][^{]*\{[^}]*min-width: 0;[^}]*max-width: 100%/);
});

test('selección mantiene estados marcado, parcial y alto contraste con el rojo original', () => {
  const styles = read('demo/fidelity.css');
  assert.match(styles, /--color-red: #E94220/);
  assert.match(styles, /\.select-movimiento:checked::after/);
  assert.match(styles, /#select-all:indeterminate::after/);
  assert.match(styles, /@media \(forced-colors: active\)/);
});

test('texto blanco del menú solo en reposo, sin sobreescribir hover ni foco', () => {
  assert.match(read('demo/fidelity.css'), /\.sidebar-link:not\(\.sidebar-active\):not\(:hover\):not\(:focus-visible\) \{ color: #fff; \}/);
});

test('el diseño no añade creación de cuentas o categorías ni selector de periodo en resumen', () => {
  const application = read('demo/app.js');
  assert.doesNotMatch(application, /summary-month|class="entity-actions"|data-create="\$\{collection\}"/);
  assert.match(application, /id="filter-month"/);
});
import test from 'node:test';
import assert from 'node:assert/strict';
import { createDemo, parseMoney, summary, filteredMovements, saveMovement, removeEntity } from '../demo/model.js';

test('datos inventados y balances calculados en céntimos', () => {
  const state = createDemo(new Date(2026, 9, 6));
  const result = summary(state, '2026-10');
  assert.equal(result.income, 210000);
  assert.equal(result.expense, 75125);
  assert.equal(result.total, 509775);
  assert.equal(result.spending.c3, 65000);
  assert.equal(filteredMovements(state, { month: '2026-10' }).length, 6);
  assert.equal(filteredMovements(state, { search: 'cine', type: 'Gasto' }).length, 1);
});

test('importes locales exactos y rechazo de importes inválidos', () => {
  assert.equal(parseMoney('42,50'), 4250);
  assert.equal(parseMoney('0.01'), 1);
  assert.equal(parseMoney('-10', true), -1000);
  for (const value of ['0', '-1', '1.234', 'Infinity', '1e2', '10000000']) assert.throws(() => parseMoney(value));
});

test('crear, editar y eliminar actualiza los totales sin duplicar', () => {
  const state = createDemo(new Date(2026, 9, 6));
  const movement = { id: 'new', type: 'Gasto', kind: 'Variable', amount: 1010, date: '2026-10-06', account: 'a1', category: 'c2', note: 'Prueba' };
  const total = summary(state, '2026-10').total;
  saveMovement(state, movement);
  assert.equal(summary(state, '2026-10').total, total - 1010);
  saveMovement(state, { ...movement, amount: 2020 });
  assert.equal(state.movements.length, 9);
  assert.equal(summary(state, '2026-10').total, total - 2020);
  removeEntity(state, 'movements', 'new');
  assert.equal(summary(state, '2026-10').total, total);
  assert.throws(() => removeEntity(state, 'accounts', 'a1'));
  assert.throws(() => removeEntity(state, 'categories', 'c2'));
});

test('rechaza referencias, fechas y tipos no válidos', () => {
  const state = createDemo();
  const movement = { ...state.movements[0] };
  for (const change of [{ category: 'c2' }, { account: 'unknown' }, { date: '2026-02-30' }, { type: 'Transferencia' }, { amount: -1 }, { note: 'x'.repeat(121) }]) {
    assert.throws(() => saveMovement(state, { ...movement, ...change }));
  }
});

test('reinicio devuelve una copia independiente y cambia de año correctamente', () => {
  const state = createDemo(new Date(2027, 0, 2));
  state.accounts[0].name = 'Modificada';
  assert.equal(createDemo().accounts[0].name, 'Cuenta corriente');
  assert.equal(state.movements[6].date, '2026-12-24');
});
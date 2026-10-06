const monthOf = date => `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
export const currentMonth = () => monthOf(new Date());

export function createDemo(date = new Date()) {
  const month = monthOf(date);
  const previous = monthOf(new Date(date.getFullYear(), date.getMonth() - 1, 1));
  return {
    accounts: [
      { id: 'a1', name: 'Cuenta corriente', type: 'Banco', opening: 125000 },
      { id: 'a2', name: 'Ahorro', type: 'Ahorro', opening: 240000 },
      { id: 'a3', name: 'Efectivo', type: 'Efectivo', opening: 9000 }
    ],
    categories: [
      { id: 'c1', name: 'Nómina', type: 'Ingreso', icon: 'briefcase' },
      { id: 'c2', name: 'Alimentación', type: 'Gasto', icon: 'basket' },
      { id: 'c3', name: 'Vivienda', type: 'Gasto', icon: 'house' },
      { id: 'c4', name: 'Transporte', type: 'Gasto', icon: 'bus-front' },
      { id: 'c5', name: 'Ocio', type: 'Gasto', icon: 'ticket-perforated' },
      { id: 'c6', name: 'Salud', type: 'Gasto', icon: 'heart-pulse' },
      { id: 'c7', name: 'Otros ingresos', type: 'Ingreso', icon: 'cash-coin' },
      { id: 'c8', name: 'Suministros', type: 'Gasto', icon: 'lightbulb' },
      { id: 'c9', name: 'Suscripciones', type: 'Gasto', icon: 'collection-play' },
      { id: 'c10', name: 'Restaurantes', type: 'Gasto', icon: 'cup-hot' },
      { id: 'c11', name: 'Ropa y accesorios', type: 'Gasto', icon: 'bag' },
      { id: 'c12', name: 'Viajes', type: 'Gasto', icon: 'airplane' },
      { id: 'c13', name: 'Formación', type: 'Gasto', icon: 'book' },
      { id: 'c14', name: 'Deporte', type: 'Gasto', icon: 'bicycle' },
      { id: 'c15', name: 'Tecnología', type: 'Gasto', icon: 'laptop' },
      { id: 'c16', name: 'Hogar', type: 'Gasto', icon: 'lamp' },
      { id: 'c17', name: 'Regalos', type: 'Gasto', icon: 'gift' },
      { id: 'c18', name: 'Seguros', type: 'Gasto', icon: 'shield-check' },
      { id: 'c19', name: 'Intereses', type: 'Ingreso', icon: 'graph-up-arrow' },
      { id: 'c20', name: 'Ventas de segunda mano', type: 'Ingreso', icon: 'shop' }
    ],
    movements: [
      { id: 'm1', type: 'Ingreso', kind: 'Fijo', amount: 210000, date: `${month}-01`, account: 'a1', category: 'c1', note: 'Nómina de ejemplo' },
      { id: 'm2', type: 'Gasto', kind: 'Fijo', amount: 65000, date: `${month}-02`, account: 'a1', category: 'c3', note: 'Alquiler mensual' },
      { id: 'm3', type: 'Gasto', kind: 'Variable', amount: 4250, date: `${month}-03`, account: 'a1', category: 'c2', note: 'Compra semanal' },
      { id: 'm4', type: 'Gasto', kind: 'Fijo', amount: 2800, date: `${month}-04`, account: 'a1', category: 'c4', note: 'Abono de transporte' },
      { id: 'm5', type: 'Gasto', kind: 'Variable', amount: 1200, date: `${month}-05`, account: 'a3', category: 'c5', note: 'Entrada de cine' },
      { id: 'm6', type: 'Gasto', kind: 'Variable', amount: 1875, date: `${month}-06`, account: 'a1', category: 'c6', note: 'Farmacia' },
      { id: 'm7', type: 'Ingreso', kind: 'Variable', amount: 4500, date: `${previous}-24`, account: 'a3', category: 'c7', note: 'Venta de un libro' },
      { id: 'm8', type: 'Gasto', kind: 'Variable', amount: 3600, date: `${previous}-26`, account: 'a1', category: 'c5', note: 'Cena de ejemplo' }
    ]
  };
}

export function parseMoney(value, allowNegative = false) {
  const text = String(value).trim().replace(',', '.');
  if (!/^-?\d+(?:\.\d{1,2})?$/.test(text)) throw new Error('Introduce un importe con un máximo de dos decimales.');
  const amount = Math.round(Number(text) * 100);
  if (!Number.isSafeInteger(amount) || Math.abs(amount) > 999999999 || (!allowNegative && amount <= 0)) {
    throw new Error('Introduce un importe válido mayor que cero.');
  }
  return amount;
}

export function summary(state, month) {
  const balances = Object.fromEntries(state.accounts.map(account => [account.id, account.opening]));
  const spending = {};
  let income = 0;
  let expense = 0;
  for (const movement of state.movements) {
    const sign = movement.type === 'Ingreso' ? 1 : -1;
    balances[movement.account] += sign * movement.amount;
    if (!movement.date.startsWith(month)) continue;
    if (sign === 1) income += movement.amount;
    else {
      expense += movement.amount;
      spending[movement.category] = (spending[movement.category] ?? 0) + movement.amount;
    }
  }
  return { balances, total: Object.values(balances).reduce((total, amount) => total + amount, 0), income, expense, spending };
}

export function filteredMovements(state, filters = {}) {
  const search = (filters.search ?? '').toLocaleLowerCase('es');
  return state.movements.filter(movement => {
    const category = state.categories.find(category => category.id === movement.category);
    const account = state.accounts.find(account => account.id === movement.account);
    return (!filters.month || movement.date.startsWith(filters.month)) &&
      (!filters.type || movement.type === filters.type) &&
      (!filters.account || movement.account === filters.account) &&
      `${movement.note} ${category.name} ${account.name}`.toLocaleLowerCase('es').includes(search);
  }).sort((first, second) => second.date.localeCompare(first.date) || second.id.localeCompare(first.id));
}

export function saveMovement(state, movement) {
  if (!['Ingreso', 'Gasto'].includes(movement.type) || !['Fijo', 'Variable'].includes(movement.kind)) throw new Error('Selecciona un tipo válido.');
  if (!Number.isSafeInteger(movement.amount) || movement.amount <= 0 || movement.amount > 999999999) throw new Error('El importe no es válido.');
  if (!/^\d{4}-\d{2}-\d{2}$/.test(movement.date) || !Number.isFinite(Date.parse(`${movement.date}T12:00:00Z`)) || new Date(`${movement.date}T12:00:00Z`).toISOString().slice(0, 10) !== movement.date) throw new Error('La fecha no es válida.');
  if (!state.accounts.some(account => account.id === movement.account)) throw new Error('Selecciona una cuenta.');
  if (!state.categories.some(category => category.id === movement.category && category.type === movement.type)) throw new Error('Selecciona una categoría del mismo tipo.');
  if (typeof movement.note !== 'string' || movement.note.length > 120) throw new Error('El comentario no puede superar 120 caracteres.');
  const index = state.movements.findIndex(item => item.id === movement.id);
  if (index === -1) state.movements.push({ ...movement });
  else state.movements[index] = { ...movement };
}

export function removeEntity(state, collection, id) {
  if (!['accounts', 'categories', 'movements'].includes(collection)) throw new Error('Elemento no válido.');
  const field = collection === 'accounts' ? 'account' : 'category';
  if (collection !== 'movements' && state.movements.some(movement => movement[field] === id)) throw new Error('Este elemento tiene movimientos asociados.');
  state[collection] = state[collection].filter(item => item.id !== id);
}
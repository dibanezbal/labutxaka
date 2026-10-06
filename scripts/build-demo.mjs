import { cp, mkdir, readFile, readdir, rm } from 'node:fs/promises';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const root = fileURLToPath(new URL('../', import.meta.url));
const output = path.join(root, 'dist');
await rm(output, { recursive: true, force: true });
await mkdir(path.join(output, 'assets/img'), { recursive: true });
for (const name of ['index.html', 'fidelity.css', 'app.js', 'model.js']) {
  await cp(path.join(root, 'demo', name), path.join(output, name));
}
await cp(path.join(root, 'app/assets/css'), path.join(output, 'assets/css'), { recursive: true });
for (const name of ['favicon.ico', 'logo_amarillo_vertical.svg', 'logo_amarillo_horizontal.svg', 'scale.svg', 'perfil.svg']) {
  await cp(path.join(root, 'app/assets/img', name), path.join(output, 'assets/img', name));
}

async function audit(directory) {
  for (const entry of await readdir(directory, { withFileTypes: true })) {
    const location = path.join(directory, entry.name);
    if (entry.isDirectory()) await audit(location);
    else {
      if (!/\.(html|css|js|svg|ico)$/.test(entry.name)) throw new Error(`Archivo no permitido: ${entry.name}`);
      if (!entry.name.endsWith('.ico')) {
        const content = await readFile(location, 'utf8');
        if (/<\?php|DB_PASS|MYSQL_PASSWORD|INSERT INTO|-----BEGIN .*PRIVATE KEY-----/.test(content)) {
          throw new Error(`Contenido privado o de backend detectado: ${path.relative(output, location)}`);
        }
      }
    }
  }
}
await audit(output);
console.log('Demo compilada en dist; solo archivos estáticos de la lista permitida.');
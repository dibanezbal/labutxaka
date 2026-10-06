import http from 'node:http';
import { readFile } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const root = path.resolve(fileURLToPath(new URL('../dist/', import.meta.url)));
const types = { '.html': 'text/html; charset=utf-8', '.css': 'text/css', '.js': 'text/javascript', '.svg': 'image/svg+xml', '.ico': 'image/x-icon' };
const server = http.createServer(async (request, response) => {
  try {
    const pathname = decodeURIComponent(new URL(request.url, 'http://localhost').pathname);
    const filename = path.resolve(root, `.${pathname === '/' ? '/index.html' : pathname}`);
    if (!filename.startsWith(`${root}${path.sep}`) || !types[path.extname(filename)]) {
      response.writeHead(404).end('Not found');
      return;
    }
    response.writeHead(200, { 'Content-Type': types[path.extname(filename)], 'X-Content-Type-Options': 'nosniff' });
    response.end(await readFile(filename));
  } catch {
    if (!response.headersSent) response.writeHead(404);
    response.end('Not found');
  }
});
server.listen(Number(process.env.PORT || 4173), '127.0.0.1', () => console.log(`Demo: http://127.0.0.1:${server.address().port}`));
server.on('error', error => { console.error(error.message); process.exitCode = 1; });
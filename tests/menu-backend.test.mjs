// Testes de API - Componente Menu (backend) - Código 42
// Rotas cobertas: GET /categoriasPai, GET /subcategoria/{id}, GET /categorias, GET /produtos?categorias=
//
// Requisitos: Node 18+ (fetch e node:test nativos, sem instalar nada).
// Uso:        node --test menu-backend.test.mjs
// Outra URL:  BASE_URL=http://localhost:8080 node --test menu-backend.test.mjs
//
// Pré-condição: docker compose up -d --build (backend em :8080, com backend/.env preenchido)

import { test, describe, before } from 'node:test';
import assert from 'node:assert/strict';

const BASE = (process.env.BASE_URL || 'node -v').replace(/\/$/, '');
const ORIGIN_FRONT = 'http://localhost:8081';
const TEMPO_MAX_MS = 2000;

async function req(path, opts = {}) {
  const t0 = performance.now();
  const res = await fetch(BASE + path, opts);
  const ms = performance.now() - t0;
  const texto = await res.text();
  let json = null;
  try { json = JSON.parse(texto); } catch { /* corpo não-JSON */ }
  return { res, status: res.status, texto, json, ms };
}

let pais = [];

before(async () => {
  const r = await req('/categoriasPai');
  if (r.status === 200 && Array.isArray(r.json)) pais = r.json;
});

describe('CT01-CT06 | GET /categoriasPai (menu principal)', () => {
  test('CT01 retorna 200 e Content-Type JSON', async () => {
    const r = await req('/categoriasPai');
    assert.equal(r.status, 200);
    assert.match(r.res.headers.get('content-type') || '', /application\/json/);
  });

  test('CT02 retorna uma lista não vazia', async () => {
    assert.ok(Array.isArray(pais), 'corpo deve ser array');
    assert.ok(pais.length > 0, 'deve haver ao menos 1 categoria pai');
  });

  test('CT03 cada item tem id_categoria numérico e nome texto não vazio', () => {
    for (const c of pais) {
      assert.ok(Number(c.id_categoria) > 0, `id_categoria inválido: ${JSON.stringify(c)}`);
      assert.equal(typeof c.nome, 'string');
      assert.ok(c.nome.trim().length > 0, `nome vazio: ${JSON.stringify(c)}`);
    }
  });

  test('CT04 todos os itens são categorias pai (id_categoria_pai = null)', () => {
    for (const c of pais) assert.equal(c.id_categoria_pai, null, `não é pai: ${JSON.stringify(c)}`);
  });

  test('CT05 sem IDs duplicados', () => {
    const ids = pais.map(c => c.id_categoria);
    assert.equal(new Set(ids).size, ids.length);
  });

  test('CT06 ordem estável entre duas chamadas (menu não pode "pular")', async () => {
    const a = await req('/categoriasPai');
    const b = await req('/categoriasPai');
    assert.deepEqual(a.json.map(c => c.id_categoria), b.json.map(c => c.id_categoria));
  });
});

describe('CT07-CT13 | GET /subcategoria/{id} (submenu)', () => {
  test('CT07 para cada categoria pai: 200, lista, e todos os filhos apontam para o pai', async () => {
    assert.ok(pais.length > 0, 'sem categorias pai para testar');
    for (const pai of pais) {
      const r = await req(`/subcategoria/${pai.id_categoria}`);
      assert.equal(r.status, 200, `pai ${pai.id_categoria}`);
      assert.ok(Array.isArray(r.json));
      for (const f of r.json) {
        assert.equal(Number(f.id_categoria_pai), Number(pai.id_categoria), `filho fora do pai: ${JSON.stringify(f)}`);
        assert.ok(f.nome && f.nome.trim().length > 0);
      }
    }
  });

  test('CT08 ID inexistente (999999) retorna 200 com lista vazia', async () => {
    const r = await req('/subcategoria/999999');
    assert.equal(r.status, 200);
    assert.deepEqual(r.json, []);
  });

  test('CT09 ID 0 retorna lista vazia (sem erro 500)', async () => {
    const r = await req('/subcategoria/0');
    assert.notEqual(r.status, 500);
    assert.deepEqual(r.json, []);
  });

  test('CT10 ID não numérico (abc) retorna 404 "Rota não encontrada"', async () => {
    const r = await req('/subcategoria/abc');
    assert.equal(r.status, 404);
    assert.ok(r.json && r.json.erro);
  });

  test('CT11 ID negativo (-1) retorna 404', async () => {
    const r = await req('/subcategoria/-1');
    assert.equal(r.status, 404);
  });

  test('CT12 tentativa de SQL injection no ID não vaza dados nem gera 500', async () => {
    for (const payload of ["1%20OR%201=1", "1'%20OR%20'1'='1", "1;DROP%20TABLE%20categoria", "1%20UNION%20SELECT%201,2,3"]) {
      const r = await req(`/subcategoria/${payload}`);
      assert.equal(r.status, 404, `payload ${payload} -> ${r.status}`);
      assert.ok(!/SQLSTATE|mysql|PDOException/i.test(r.texto), 'resposta expõe detalhe de banco');
    }
  });

  test('CT13 ID gigante (overflow) não gera 500', async () => {
    const r = await req('/subcategoria/99999999999999999999999');
    assert.notEqual(r.status, 500);
  });
});

describe('CT14-CT16 | Consistência entre rotas', () => {
  test('CT14 /categoriasPai bate com os pais de /categorias', async () => {
    const todas = await req('/categorias');
    assert.equal(todas.status, 200);
    const idsPai = todas.json.filter(c => c.id_categoria_pai === null).map(c => c.id_categoria).sort((a, b) => a - b);
    const idsMenu = pais.map(c => Number(c.id_categoria)).sort((a, b) => a - b);
    assert.deepEqual(idsMenu, idsPai);
  });

  test('CT15 subcategorias de /subcategoria/{id} batem com os filhos de /categorias', async () => {
    const todas = (await req('/categorias')).json;
    for (const pai of pais) {
      const esperados = todas.filter(c => c.id_categoria_pai === Number(pai.id_categoria)).map(c => c.id_categoria).sort((a, b) => a - b);
      const obtidos = (await req(`/subcategoria/${pai.id_categoria}`)).json.map(c => Number(c.id_categoria)).sort((a, b) => a - b);
      assert.deepEqual(obtidos, esperados, `pai ${pai.id_categoria}`);
    }
  });

  test('CT16 nomes das categorias pai estão em português legível (sem lixo de encoding)', () => {
    for (const c of pais) assert.ok(!/Ã.|�/.test(c.nome), `encoding suspeito: ${c.nome}`);
  });
});

describe('CT17-CT20 | Filtro de produtos pelo menu: GET /produtos?categorias=', () => {
  test('CT17 filtro por categoria pai válida retorna 200 e lista', async () => {
    assert.ok(pais.length > 0);
    const r = await req(`/produtos?categorias=${pais[0].id_categoria}`);
    assert.equal(r.status, 200);
    assert.ok(Array.isArray(r.json));
  });

  test('CT18 categoria inexistente retorna 200 e lista vazia', async () => {
    const r = await req('/produtos?categorias=999999');
    assert.equal(r.status, 200);
    assert.deepEqual(r.json, []);
  });

  test('CT19 várias categorias separadas por vírgula (ex.: 1,2) retorna 200', async () => {
    const r = await req('/produtos?categorias=1,2');
    assert.equal(r.status, 200);
    assert.ok(Array.isArray(r.json));
  });

  test('CT20 valor inválido (abc) NÃO deve retornar 500', async () => {
    // Observação: o código converte com intval -> 0 -> filtro ignorado (devolve todos os produtos).
    // Aqui só garantimos que não quebra. Registrar no relatório se o comportamento não for o desejado.
    const r = await req('/produtos?categorias=abc');
    assert.notEqual(r.status, 500);
  });
});

describe('CT21-CT25 | Protocolo, CORS e desempenho', () => {
  test('CT21 preflight OPTIONS retorna 204', async () => {
    const r = await req('/categoriasPai', { method: 'OPTIONS', headers: { Origin: ORIGIN_FRONT, 'Access-Control-Request-Method': 'GET' } });
    assert.equal(r.status, 204);
  });

  test('CT22 CORS libera a origem do frontend (localhost:8081)', async () => {
    const r = await req('/categoriasPai', { headers: { Origin: ORIGIN_FRONT } });
    assert.equal(r.res.headers.get('access-control-allow-origin'), ORIGIN_FRONT);
  });

  test('CT23 método não permitido (POST /categoriasPai) não retorna 200/500', async () => {
    const r = await req('/categoriasPai', { method: 'POST' });
    assert.ok([404, 405].includes(r.status), `status ${r.status}`);
  });

  test('CT24 DELETE /subcategoria/1 não é aceito', async () => {
    const r = await req('/subcategoria/1', { method: 'DELETE' });
    assert.ok([404, 405].includes(r.status), `status ${r.status}`);
  });

  test(`CT25 respostas do menu em até ${TEMPO_MAX_MS} ms`, async () => {
    for (const p of ['/categoriasPai', '/categorias']) {
      const r = await req(p);
      assert.ok(r.ms < TEMPO_MAX_MS, `${p} levou ${r.ms.toFixed(0)} ms`);
    }
  });
});

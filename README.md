# Agendamento

Aplicação Laravel para gerenciar eventos e agendamentos, com suporte a autenticação e interface moderna via Livewire/Vite.

## Requisitos

- PHP 8.2+
- Composer 2.2+
- Node.js 18+
- NPM 9+
- Banco de dados compatível com Laravel (SQLite/ MySQL/ PostgreSQL)

## Stack

- Framework: `Laravel 12`
- Frontend: `Vite` + `Tailwind CSS` + `Alpine.js`
- Backend: `Livewire` (Laravel)
- Testes: `PHPUnit`

## Estrutura do projeto

- `app/` — código da aplicação (Models, Controllers, Livewire, etc.)
- `routes/` — rotas web e API
- `resources/` — views, estilos e assets
- `public/` — pasta pública (assets compilados, `.well-known`, upload estático)
- `database/` — migrations, seeders e factories
- `tests/` — testes automatizados
- `storage/` — arquivos temporários, logs e caches

## Primeiros passos

1. Copie o ambiente:

```bash
cp .env.example .env
```

2. Instale dependências PHP:

```bash
composer install
```

3. Instale dependências JS:

```bash
npm install
```

4. Gere a chave da aplicação:

```bash
php artisan key:generate
```

5. Rode migrations (ajuste `DB_*` em `.env` antes se necessário):

```bash
php artisan migrate
```

6. Compile assets:

```bash
npm run build
```

7. Inicie o servidor de desenvolvimento:

```bash
php artisan serve
npm run dev
```

## Script de setup e comandos úteis

- Setup completo:

```bash
composer setup
```

- Ambiente de desenvolvimento (php, fila, logs e Vite em paralelo):

```bash
composer dev
```

- Rodar testes:

```bash
composer test
```

## Banco de dados

Edite `DB_*` no `.env` conforme seu ambiente. O projeto está preparado para SQLite no estado atual e pode ser adaptado para MySQL/PostgreSQL com alteração de driver e credenciais.

## Boas práticas

- Nunca commite arquivos `.env`.
- Evite versionar `vendor/`, `node_modules/` e arquivos de build em `public/build`.
- Utilize as migrations para controle de schema.
- Prefira criar testes para fluxos críticos de agendamento.

## Licença

MIT

# Pendencias - Sistema de Tickets

> Avaliacao completa em 02/03/2026 | Laravel 12 + PHP 8.2 + Tailwind 3 + Vite 4

---

## 1. BUGS CRITICOS — CORRIGIDOS

| # | Arquivo | Problema | Status |
|---|---------|----------|--------|
| 1 | `Attachment.php` | `ticket()` usava `ticket_id` inexistente — agora navega via `ticketMessage->ticket` | CORRIGIDO |
| 2 | `AttachmentController.php` | Usava `$user->client_id` inexistente — agora verifica via `clientContacts` | CORRIGIDO |
| 3 | `TicketMessage.php` | `ip_address` e `user_agent` faltavam em `$fillable` | CORRIGIDO |
| 4 | `UserPasswordController.php` | `metadata` passado ao `AuditLog` sem coluna — dados movidos para `new_values` | CORRIGIDO |
| 5 | `StoreClientRequest.php` / `UpdateClientRequest.php` | Regex incompativel com `prepareForValidation` — regex agora valida 14 digitos | CORRIGIDO |
| 6 | `SanitizeInputMiddleware.php` | Transformacoes se sobrescreviam — agora usa variavel progressiva | CORRIGIDO |
| 7 | `RouteServiceProvider.php` | Referenciava `routes/api.php` inexistente — callback removido | CORRIGIDO |

---

## 2. INCONSISTENCIA DE VERSOES — CORRIGIDO

Todos os 6 locais unificados para **v1.6.1**:

| Local | Antes | Agora |
|-------|-------|-------|
| `config/app.php` | 1.4.9 | 1.6.1 |
| `config/system.php` | 1.0.0 (fallback) | 1.6.1 |
| `composer.json` | v1.3 | v1.6.1 |
| `package.json` | 1.2.0 | 1.6.1 |
| `.env` | 1.0.0 | 1.6.1 |
| `.env.example` | 1.2.0 | 1.6.1 |

---

## 3. MIGRATIONS DUPLICADAS

- `add_is_active_to_users_table` (2x: `221410` e `221417`)
- `update_tickets_hash_field` (2x: `000001` e `151518`)
- `remove_hash_from_tickets_table` (2x: `125409` e `125426`)

**Acao:** Consolidar e remover duplicatas. Risco de erro ao rodar `migrate:fresh`.

---

## 4. ARQUITETURA LARAVEL 12 — CORRIGIDO

| Acao | Status |
|------|--------|
| `Kernel.php` deletado — obsoleto no Laravel 12 | CORRIGIDO |
| `RouteServiceProvider.php` deletado — codigo morto, nao registrado | CORRIGIDO |
| `bootstrap/app.php` atualizado — `SanitizeInputMiddleware` e `CaptureAuditInfo` no grupo `web`, aliases `role`, `audit`, `rate.limit` registrados | CORRIGIDO |
| `LoginController` e `RegisterController` — `RouteServiceProvider::HOME` substituido por `'/dashboard'` | CORRIGIDO |

---

## 5. DEPENDENCIAS — CORRIGIDO

Removidos (nao utilizados no projeto):
- `bootstrap` 5.3, `@popperjs/core` 2.11, `sass` 1.56

Atualizados em `package.json`:

| Pacote | Antes | Agora |
|--------|-------|-------|
| `vite` | ^4.0.0 | ^6.2.0 |
| `laravel-vite-plugin` | ^0.7.2 | ^1.2.0 |
| `axios` | ^1.1.2 | ^1.7.9 |

Mantidos (ja na ultima minor):
- `tailwindcss` ^3.4.17, `postcss` ^8.5.6, `autoprefixer` ^10.4.21, `chart.js` ^4.5.0

**Nota:** Tailwind v4 requer migracao do CSS (`@tailwind` → `@import`, `tailwind.config.js` → `@theme`). Planejar separadamente.

**Pendente:** Rodar `npm install` em producao para aplicar.

---

## 6. SEGURANCA — CORRIGIDO

| # | Problema | Status |
|---|----------|--------|
| 1 | `.env.example` continha senha real (`AMESMASENHA2022*`) — substituida por placeholder | CORRIGIDO |
| 2 | `LoginController` — 6x `\Log::info()` de debug removidos (incluindo log de credenciais em JSON) | CORRIGIDO |
| 3 | `Client\UserController` — bloco de debug com `\Log::info()` removido | CORRIGIDO |
| 4 | `SanitizeInputMiddleware` e `RateLimitMiddleware` — registrados em `bootstrap/app.php` (item #4) | CORRIGIDO |

---

## 7. REFATORACAO NECESSARIA — CORRIGIDO

| # | Item | Status |
|---|------|--------|
| 1 | Rota de teste `/test-contacts` removida de `web.php` | CORRIGIDO |
| 2 | Redirects legados `clients.contacts.edit`, `settings.index`, `settings.system` removidos | CORRIGIDO |
| 3 | Permissoes extraidas para `authorizeClientAccess()` em `Client\UserController` (de 5x repetido para 1 metodo) | CORRIGIDO |
| 4 | `clients.contacts.store/update/delete` — usados pelo modal AJAX em `clients/show.blade.php`, mantidos | NOTA |
| 5 | `ProfileController::updatePreferences()` — agora persiste via session (migration pendente para colunas permanentes) | CORRIGIDO |
| 6 | `middleware(['auth'])` redundante removido dos grupos de tickets e users | CORRIGIDO |

---

## 8. CONFIGURACAO — CORRIGIDO

| Item | Antes | Agora |
|------|-------|-------|
| `timezone` | UTC | America/Sao_Paulo |
| `locale` | en | pt_BR |
| `fallback_locale` | en | pt_BR |
| `faker_locale` | en_US | pt_BR |

---

## 9. TESTES

Nenhum teste unitario ou de feature encontrado em `tests/`. Cobertura: **0%**.

---

## PRIORIDADE DE EXECUCAO

1. **Urgente:** Bugs criticos (#1-7) — sistema com falhas ativas
2. **Alta:** Seguranca (#6) — credenciais expostas e middleware desativado
3. **Media:** Arquitetura Laravel 12 (#4) + Migrations (#3)
4. **Baixa:** Dependencias (#5) + Configuracao (#8) + Refatoracao (#7)
5. **Planejado:** Testes (#9)

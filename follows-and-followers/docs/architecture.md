# Architecture & DDD Learnings

## 1. Overview

This document captures all architectural decisions, DDD principles applied, and patterns adopted in this project. It covers the Log module and Payment module refactoring process.

---

## 2. Log Module

### Problem

The original `LogService` used a **static method**:

```php
LogService::write('action', $userId, ...);
```

This violated Dependency Injection principles, made the code untestable, and hid dependencies.

### Fix

Replaced with a proper Use Case injected via constructor:

```php
public function __construct(
    private CreateLogUseCase $writeLog,
) {}

// Usage
$this->writeLog->execute(new CreateLogDTO(...));
```

### Data Stream

```
HTTP Request
    → Controller
        → UseCase (injected with CreateLogUseCase)
            → CreateLogUseCase::execute(CreateLogDTO)
                → LogRepository::create()
                    → EloquentLogModel (persistence)
```

### Structure

```
app/Modules/Log/
    Application/
        DTO/
            CreateLogDTO.php        ← input DTO (from Use Case layer)
        UseCases/
            CreateLogUseCase.php    ← execute(CreateLogDTO): void
    Domain/
        Repo/
            LogRepository.php       ← interface
    Infra/
        Persistence/
            EloquentLogModel.php    ← implements LogRepository
```

---

## 3. Domain-Driven Design (DDD) — Layer Rules

### Dependency Direction (CRITICAL)

```
Domain ← Application ← Infrastructure ← Presentation
```

- **Domain** knows nothing about any other layer.
- **Application** depends on Domain (uses interfaces/entities/VOs).
- **Infrastructure** implements Domain interfaces (Repositories, Gateways).
- **Presentation** calls Application Use Cases.

**The most common violation**: putting Application DTOs into the Domain layer.

---

## 4. Gateway Pattern

### Problem

`PaymentGateway` was temporarily placed at `Application/Ports/AbstractPaymentGateway`. This broke DDD because:

- The Domain layer depended on the Application layer to know what `PaymentGateway` is.
- It forced Domain entities/factories to import Application-level abstractions.

### Fix

Moved `PaymentGateway` **back to `Domain/Gateway/PaymentGateway.php`**.

```php
// Domain/Gateway/PaymentGateway.php
interface PaymentGateway
{
    public function createPixPayment(PixPaymentRequest $request): object;
    public function fetchPayment(int $paymentId): object;
}
```

The **concrete implementation** (`MercadoPagoGateway`) lives in `Infra/Gateways/`.

### Rule

> Domain **defines** contracts (interfaces). Infrastructure **fulfills** them.

```
Domain/Gateway/PaymentGateway.php          ← interface (DOMAIN)
Infra/Gateways/MercadoPagoGateway.php      ← implementation (INFRA)
```

---

## 5. Value Objects vs DTOs

### Problem

The original `PaymentGateway::createPixPayment()` accepted `CreatePixPaymentDTO` — an **Application DTO** — as a parameter. This violated DDD because the Domain depended on the Application layer.

### Fix: `PixPaymentRequest` Value Object

Created `Domain/ValueObject/PixPaymentRequest.php`:

```php
final class PixPaymentRequest
{
    public function __construct(
        public readonly int $userId,
        public readonly string $email,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $docType,
        public readonly string $docNumber,
        public readonly float $amount,
    ) {}
}
```

The **Application Use Case** maps the HTTP DTO to the Domain VO:

```php
// CreatePaymentUseCase.php
$pixRequest = new PixPaymentRequest(
    userId: $dto->userId,
    email: $dto->email,
    firstName: $dto->firstName,
    lastName: $dto->lastName,
    docType: $dto->docType,
    docNumber: $dto->docNumber,
    amount: $dto->amount,
);

$response = $this->paymentGateway->createPixPayment($pixRequest);
```

### Benefits

| Aspect | DTO Leak (bad) | PixPaymentRequest VO (good) |
|---|---|---|
| Domain isolation | ❌ Domain imports App DTO | ✅ Domain is self-contained |
| Reusability | ❌ Tied to HTTP layer | ✅ Any caller can use Domain |
| Testability | ❌ Must mock App classes | ✅ Pure Domain objects in tests |
| DDD compliance | ❌ Wrong dependency direction | ✅ Follows domain-first principle |

---

## 6. Domain Events Pattern

### How It Works

1. **Entity** fires events into an internal list (does NOT dispatch them directly).
2. **Use Case** collects events via `pullDomainEvents()` and dispatches them.
3. **EventDispatcher** resolves and calls the appropriate **Listeners**.
4. **Listeners** handle side effects (logging, notifications, etc.).

### SaleEntity Methods

```php
// SaleEntity.php
public function markAsCreated(string $mpPaymentId, string $status, ?string $ipAddress): void
{
    $this->mpPaymentId = $mpPaymentId;
    $this->status = $status;
    $this->domainEvents[] = new SaleCreatedEvent($this, $ipAddress);
}

public function approve(string $mpPaymentId, ?string $ipAddress): void
{
    $this->mpPaymentId = $mpPaymentId;
    $this->status = 'approved';
    $this->domainEvents[] = new SaleApprovedEvent($this, $ipAddress);
}

public function fail(string $mpPaymentId, ?string $ipAddress): void
{
    $this->mpPaymentId = $mpPaymentId;
    $this->status = 'failed';
    $this->domainEvents[] = new SaleFailedEvent($this, $ipAddress);
}

public function failtoUpdate(string $mpPaymentId, string $status, ?string $ipAddress): void
{
    $this->domainEvents[] = new SaleUpdateFailEvent($this, $ipAddress);
}

/** @return array<object> */
public function pullDomainEvents(): array
{
    $events = $this->domainEvents;
    $this->domainEvents = [];
    return $events;
}
```

### Domain Events

```
Domain/Events/
    SaleCreatedEvent.php        ← fired when sale is first created
    SaleApprovedEvent.php       ← fired when payment is approved
    SaleFailedEvent.php         ← fired when payment fails/is cancelled/rejected
    SaleUpdateFailEvent.php     ← fired when webhook arrives but sale not found
```

### Use Case Dispatching

```php
// After persisting the entity:
foreach ($sale->pullDomainEvents() as $event) {
    $this->eventDispatcher->dispatch($event);
}
```

### Listener Invocation

Listeners are registered in the `ServiceProvider`. When `eventDispatcher->dispatch($event)` is called, Laravel resolves and invokes `handle()` on the matching listener:

```php
// LogSaleCreated.php
public function handle(SaleCreatedEvent $event): void
{
    $this->writeLog->execute(new CreateLogDTO(
        action: 'sale.created',
        userId: $event->sale->userId,
        entityType: 'sale',
        entityId: $event->sale->id,
        ipAddress: $event->ipAddress,
    ));
}
```

---

## 7. Factory Pattern

### Rule

Factories belong in the **Domain** layer. They create complex domain objects from primitives — they must NOT accept Application DTOs.

```php
// Domain/Factory/SaleFactory.php
public function createPendingFromPix(
    int $userId,
    float $amount,
    string $mpPaymentId,
    ?array $mpPaymentData,
): SaleEntity {
    return new SaleEntity(
        id: null,
        userId: $userId,
        amount: $amount,
        status: 'pending',
        mpPaymentId: $mpPaymentId,
        mpPaymentData: $mpPaymentData,
    );
}
```

---

## 8. Repository Pattern

### Rule

- **Interface** → `Domain/Repo/SaleRepository.php` (Domain defines the contract)
- **Implementation** → `Infra/Persistence/EloquentSaleRepository.php` (Infra fulfills it)
- **Binding** → `PaymentServiceProvider` maps interface → implementation

```php
// Domain/Repo/SaleRepository.php
interface SaleRepository
{
    public function create(SaleEntity $sale): SaleEntity;
    public function update(SaleEntity $sale): void;
    public function findByPaymentId(string $paymentId): ?SaleEntity;
}
```

---

## 9. Event-Driven Logging

### Why Logging via Listeners Is Better

**Inline logging (tightly coupled):**
```php
// UseCase directly creates log — knows too much
$this->writeLog->execute(new CreateLogDTO(action: 'sale.created', ...));
$gateway->createPixPayment(...);
```

**Event-driven logging (decoupled):**
```php
// UseCase only knows about business logic
$sale->markAsCreated($mpPaymentId, $status, $ipAddress);

// Listener handles logging as a side effect
// LogSaleCreated::handle(SaleCreatedEvent $event)
```

### Benefits

| Aspect | Inline Logging | Event-Driven Logging |
|---|---|---|
| UseCase responsibility | ❌ Mixed concerns | ✅ Single responsibility |
| Adding new side effects | ❌ Edit UseCase | ✅ Add a new Listener |
| Testability | ❌ Must assert log calls | ✅ Test events independently |
| Extensibility | ❌ Open/closed violation | ✅ Open for extension |

---

## 10. Full Data Flows

### CreatePayment Flow

```
POST /payments/pix
    → PaymentController::store(PaymentRequest $request)
        → CreatePixPaymentDTO::fromRequest($request)
        → CreatePaymentUseCase::execute(CreatePixPaymentDTO $dto)
            → new PixPaymentRequest(...) [maps DTO → Domain VO]
            → PaymentGateway::createPixPayment(PixPaymentRequest)
                → MercadoPagoGateway (Infra)
                    → MercadoPago SDK
                → returns raw object
            → CreatePaymentMercadoPagoResponseDTO::fromMercadoPago($response)
            → SaleFactory::createPendingFromPix(userId, amount, mpPaymentId, data)
                → new SaleEntity (status: pending)
            → SaleRepository::create(SaleEntity)
                → EloquentSaleRepository persists
            → SaleEntity::markAsCreated(mpPaymentId, status, ipAddress)
                → queues SaleCreatedEvent internally
            → pullDomainEvents() → dispatch(SaleCreatedEvent)
                → LogSaleCreated::handle(SaleCreatedEvent)
                    → CreateLogUseCase::execute(CreateLogDTO)
```

### HandleWebhook Flow

```
POST /webhooks/mercadopago
    → WebhookController::handle(Request $request)
        → MercadoPagoSignatureValidator::validate($request)
        → HandleWebhookUseCase::execute(type, paymentId, ipAddress)
            → if type != 'payment': return
            → SaleRepository::findByPaymentId($paymentId)
                → if null:
                    → dispatch(WebhookSaleNotFoundEvent)
                        → LogWebhookSaleNotFound::handle(...)
                            → CreateLogUseCase (log the anomaly)
                    → return
            → PaymentGateway::fetchPayment(int $paymentId)
                → MercadoPagoGateway → SDK → returns raw object
            → GetPaymentMercadoPagoResponseDTO::fromMercadoPago($response)
            → match($status):
                'approved'          → $sale->approve(mpPaymentId, ipAddress)
                                        → queues SaleApprovedEvent
                'cancelled'|'rejected' → $sale->fail(mpPaymentId, ipAddress)
                                        → queues SaleFailedEvent
                default             → no state change
            → SaleRepository::update($sale)
            → pullDomainEvents() → dispatch each event
                → LogSaleApproved::handle(SaleApprovedEvent) [or LogSaleFailed]
                    → CreateLogUseCase::execute(CreateLogDTO)
```

---

## 11. Module File Structure

```
app/Modules/Payment/
    Domain/
        Entity/
            SaleEntity.php
        Events/
            SaleCreatedEvent.php
            SaleApprovedEvent.php
            SaleFailedEvent.php
            SaleUpdateFailEvent.php
        Factory/
            SaleFactory.php
        Gateway/
            PaymentGateway.php              ← interface
        Repo/
            SaleRepository.php              ← interface
        ValueObject/
            PixPaymentRequest.php
    Application/
        DTOs/
            CreatePixPaymentDTO.php         ← HTTP input DTO
            CreatePaymentMercadoPagoResponseDTO.php
            Response/
                GetPaymentMercadoPagoResponseDTO.php
        Events/
            WebhookSaleNotFoundEvent.php
        Listeners/
            LogSaleCreated.php
            LogWebhookSaleNotFound.php
        UseCases/
            CreatePaymentUseCase.php
            HandleWebhookUseCase.php
    Infra/
        Gateways/
            MercadoPagoGateway.php          ← implements PaymentGateway
        Persistence/
            EloquentSaleRepository.php      ← implements SaleRepository
        Validators/
            MercadoPagoSignatureValidator.php
    Presentation/
        Controllers/
            PaymentController.php
        Requests/
            PaymentRequest.php
    Provider/
        PaymentServiceProvider.php

app/Modules/Log/
    Application/
        DTO/
            CreateLogDTO.php
        UseCases/
            CreateLogUseCase.php
    Domain/
        Repo/
            LogRepository.php               ← interface
    Infra/
        Persistence/
            EloquentLogModel.php            ← implements LogRepository

app/Shared/
    Application/
        Events/
            EventDispatcherInterface.php    ← shared interface
```

---

## 12. Key DDD Rules — Quick Reference

| Rule | Good | Bad |
|---|---|---|
| Gateway interface location | `Domain/Gateway/` | `Application/Ports/` |
| Method parameter type | Domain VO (`PixPaymentRequest`) | Application DTO (`CreatePixPaymentDTO`) |
| Logging side effects | Domain Events + Listeners | Direct `CreateLogUseCase::execute()` in UseCase |
| Factory input | Primitive types | Application DTOs |
| Repository binding | ServiceProvider (Infra → Domain interface) | Direct Eloquent in UseCase |
| Static services | ❌ Never | N/A |
| Domain depending on Application | ❌ Never | N/A |

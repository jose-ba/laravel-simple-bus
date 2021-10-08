Implementación muy sencilla de un bus de eventos usando simple-bus/message-bus
en Laravel.

#Uso

## Solicitar la instancia del bus

Si se usa inyección de dependencias, pedir una instancia de `Buses\Domain\EventBus`
en el constructor.

```php
public function __construct(\Buses\Domain\EventBus $eventBus) {
    $this->eventBus = $eventBus;
}
```

## Registrar un listener

El bus está registrado en el contenedor de servicios como singleton para poder
registrar listeners a lo largo de la aplicación antes de lanzar un evento.

Para registrar un listener, hay que hacer la siguiente llamada:

```php
$this->eventBus->addSubscriber(
    Evento::class,
    [new ClaseDelHandler(), 'método a llamar'] // O un callable cualquiera
);
```

Es decir, se pasa como primer parámetro el nombre completo de la clase que
representa el evento, y como segundo parámetro un `callable` al que se pasará
el evento.

## Lanzar un evento

Para lanzar un evento, simplemente hay que pasar una instancia del mismo al bus:

```php
$this->eventBus->dispatch(new Evento());
```

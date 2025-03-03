1. Для посева рандомных товаров в Kafka:

```sh
symfony console app:seeding:products --count=1000
```

--count=1000 - это заданное количество товаров

2. Через supervisor происходит постоянное наполнение БД товарами из kafka. Для этого используется команда:

```sh
symfony console app:consume:products
```

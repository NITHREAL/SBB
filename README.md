Documentation
-------------

## Makefile
Установить `make` если не установлен в системе
```
cp docker/Makefile .
```
```bash
make first_install # Первоначальная установка проекта.
make build # Сборка образа.
make up # Запуск контейнеров и миграция базы данных.
make down # Остановка контейнеров.
make clear # Очистка кеша и настроек laravel.
make test_run # Запуск тестов.
make cs # Проверка кода (codesniffer).
make cs-fix # Автоматическое исправление кода (codesniffer).
```
Override-файл для docker-compose можно взять тут:
```
$ cp docker/docker-compose.override.yml .
```

Пользователь для админки:
````
Логин: admin@admin.com
Пароль: admin
````

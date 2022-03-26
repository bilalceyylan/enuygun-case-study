# Enuygun Case Study

Enuygun Case Study

#Install

```
composer install
```

#Create Database and Table

```
php bin/console doctrine:database:create
```

```
php bin/console make:migration
```

```
php bin/console doctrine:migrations:migrate
```

#Load Data

```
php bin/console doctrine:fixtures:load
```

#Run Command

```
php bin/console task:create
```

#Run Project

```
symfony server:start -d
```

#Let's go http://127.0.0.1:8000/task

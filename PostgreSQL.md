# PostgreSQL Cheat Sheet

## Install

### Update and install, then switch to user _postgres_

```
$ sudo apt update
$ sudo apt install postgresql postgresql-contrib
$ sudo -u postgres psql
```

### Creating a new role

```
postgres@server:-$ createuser --interactive
```

or without switching from your normal account :

```
$ sudo -u postgres createuser --interactive
```

### Install pgAdmin4 on Ubuntu

```
$ sudo apt-get install pgadmin4 pgadmin4-apache2
```

# MySQL Binlog Analyzer

This application show usage from binlog extract files.

This use regular expressions to show the usage.

(c) This service is created using Symfony Command

## How to get binlog events do analyze

In your MySQL database run this sql:
```
SHOW BINLOG EVENTS; # GET ALL EVENTS
```

To see a specify binlog:
```
SHOW BINARY LOGS; # GET ALL BINLOGS FILES
SHOW BINLOG EVENTS IN 'mysql-bin-changelog.NUMBER'; # optional
```

Save all data in a csv file in this folder to run command.

## Requeriments

PHP 7+

```
composer install
```

## How to run

Using command line:

```
php index.php analyzer
```

Optional set filename on call command:

```
php index.php analyzer file.csv
```

## Preview

![Alt text](preview.png "Preview")


## Contribute

Contributions are always welcome!

## License

[![CC0](https://licensebuttons.net/p/zero/1.0/88x31.png)](https://creativecommons.org/publicdomain/zero/1.0/)

To the extent possible under law, [VitorGGA](https://vitorgga.com) has waived all copyright and related or neighboring rights to this work.
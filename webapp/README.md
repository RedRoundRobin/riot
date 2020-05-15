![RedRounRobinLogo](https://i.imgur.com/3Dcv4vs.png)

# Web application - ThiReMa

:fire: Versione componente: `v1.0.0-stable` 

:pushpin: Main repo: [swe-thirema](https://github.com/RedRoundRobin/swe-thirema)

---

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=RedRoundRobin_swe-webapp&metric=alert_status)](https://sonarcloud.io/dashboard?id=RedRoundRobin_swe-webapp)

![SWE Web App CI](https://github.com/RedRoundRobin/swe-webapp/workflows/SWE%20Web%20App%20CI/badge.svg)

![Repository Checker](https://github.com/RedRoundRobin/swe-webapp/workflows/Repository%20Checker/badge.svg)

[Coverage Status](https://coveralls.io/github/RedRoundRobin/swe-webapp?branch=develop)


### Installazione e primo avvio

1. composer update
2. npm install
3. npm run dev
4. php artisan serve


### In caso di corruzione JS :fire:

`npm install --package-lock`

### Comandi per il code style JS :fire:

- Check:

`npm run prettier-eslint-check`
`npm run prettier-eslint-test-check`

- Autofix:

`npm run prettier-eslint`
`npm run prettier-eslint-test`


### Comandi per i test JS

`npm test`


### Comandi per il code style PHP 

- Check:

`vendor/bin/phpcs -s`
`vendor/bin/php-cs-fixer fix --config=.php_cs.php --dry-run -vvv`

- Autofix: 

`vendor/bin/phpcbf -n`
`vendor/bin/php-cs-fixer fix --config=.php_cs.php --diff -vvv`


### Comandi per i test PHP

`vendor/bin/phpunit -c phpunit.xml`


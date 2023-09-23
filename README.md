# freelance-rss-parser

Extract information about new projects from source sites.  Add filters for find right job and send notification about new jobs.

Add [notifier tg channel](https://symfony.com/doc/6.1/notifier.html#chat-channel) for send notification about new jobs as ENV variable TELEGRAM_BOT_NOTIFIER.

## Tests

```shell
bin/console doctrine:database:drop --if-exists --force
bin/console doctrine:database:create --if-not-exists
bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
```
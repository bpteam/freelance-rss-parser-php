ARG APP_VERSION
FROM freelance-rss-parser-php-worker:$APP_VERSION

CMD ['php', 'bin/console', 'app:notifier_telegram', '--start=now', '--end=2999-01-01', '--repeat=PT1M']
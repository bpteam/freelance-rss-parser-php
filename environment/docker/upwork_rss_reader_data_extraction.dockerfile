ARG APP_VERSION
FROM freelance-rss-parser-php-worker:$APP_VERSION

CMD ['php', 'bin/console', 'app:upwork_rss_reader_data_extraction']
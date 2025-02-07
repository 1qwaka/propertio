FROM php:8.2-cli

ENV PHP_METRICS_VERSION=2.8.1

RUN apt update && apt install -y \
    libyaml-dev

RUN pecl install yaml \
	&& docker-php-ext-enable yaml

RUN curl -L https://github.com/phpmetrics/PhpMetrics/releases/download/v$PHP_METRICS_VERSION/phpmetrics.phar > /usr/local/bin/phpmetrics \
    && chmod +x /usr/local/bin/phpmetrics \
    && rm -rf /var/cache/apk/* /var/tmp/* /tmp/*

VOLUME ["/project"]
WORKDIR /project

ENTRYPOINT ["phpmetrics"]
#CMD ["--version"]
#CMD ["--report-html=/project/linter-report",  "/project/app"]
CMD ["--config=/project/linter-config.yaml"]


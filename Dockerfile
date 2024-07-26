FROM ubuntu:22.04

LABEL maintainer="Taylor Otwell"

ARG WWWGROUP

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone


RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 \
    && mkdir -p ~/.gnupg \
    && chmod 600 ~/.gnupg \
    && echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf \
    && apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys E5267A6C \
    && apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys C300EE8C \
    && echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu focal main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y php82-cli php82-dev \
       php82-pgsql php82-sqlite3 php82-gd \
       php82-curl php82-memcached \
       php82-imap php82-mysql php82-mbstring \
       php82-xml php82-zip php82-bcmath php82-soap \
       php82-intl php82-readline \
       php82-msgpack php82-igbinary php82-ldap \
       php82-redis php82-mbstring \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && apt-get update \
    && apt-get install -y yarn \
    && apt-get install -y mysql-client \
    && apt-get install -y postgresql-client \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*



# RUN pecl channel-update https://pecl.php.net/channel.xml \
#     && pecl install swoole \
#     && pecl clear-cache \
#     && rm -rf /tmp/* /var/tmp/*




RUN setcap "cap_net_bind_service=+ep" /usr/bin/php82

RUN useradd -m sail
RUN usermod -aG sudo sail

# RUN groupadd --force -g $WWWGROUP sail
# RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 sail

RUN curl  -fsSL https://deb.nodesource.com/setup_12.x |  bash -
RUN apt-get install -y nodejs

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


COPY . /var/www/html/
COPY resources/docker/app/start-container /usr/local/bin/start-container
COPY resources/docker/app/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY resources/docker/app/php.ini /etc/php/82/cli/conf.d/99-sail.ini
RUN chmod +x /usr/local/bin/start-container

EXPOSE 80

ENTRYPOINT ["start-container"]

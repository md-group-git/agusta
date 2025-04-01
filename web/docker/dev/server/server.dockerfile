FROM alpine:3.12

RUN apk add --update nginx nano mc
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

COPY nginx.conf /etc/nginx/
COPY project.conf /etc/nginx/conf.d/
COPY upstream.conf /etc/nginx/conf.d/

RUN adduser -D -g '' -G www-data www-data

CMD ["nginx"]

WORKDIR /var/www/project

EXPOSE 80
EXPOSE 443

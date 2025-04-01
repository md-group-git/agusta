FROM alpine:3.12

RUN apk add --update nginx nano mc
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*
RUN rm /etc/nginx/conf.d/default.conf

COPY nginx.conf /etc/nginx/
COPY project.conf /etc/nginx/conf.d/
COPY upstream.conf /etc/nginx/conf.d/

RUN adduser -D -g '' -G www-data www-data

EXPOSE 80/tcp 443/tcp

CMD ["nginx"]

STOPSIGNAL SIGTERM

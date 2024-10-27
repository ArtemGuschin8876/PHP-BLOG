FROM nginx:alpine
WORKDIR /var/www/app
COPY ./.docker/nginx/nginx.conf /etc/nginx/nginx.conf
RUN rm /etc/nginx/conf.d/*.conf
COPY ./.docker/nginx/conf.d /etc/nginx/conf.d
CMD ["nginx"]
FROM nginx:1.14-alpine
ADD /docker/config/nginx.conf /etc/nginx/conf.d/default.conf
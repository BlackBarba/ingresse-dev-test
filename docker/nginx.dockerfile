FROM nginx:alpine
ADD /docker/config/nginx.conf /etc/nginx/conf.d/default.conf
FROM nginx:1.27-alpine

# Удаляем дефолтный конфиг и кладём свой
RUN rm /etc/nginx/conf.d/default.conf
COPY nginx.conf /etc/nginx/conf.d/laravel.conf

# Рабочий каталог приложения (для статических ассетов)
WORKDIR /var/www/html

# Etapa 1: construir. Node compila el proyecto y deja HTML, CSS y JS en dist/
FROM node:20-alpine AS construir
WORKDIR /app
COPY frontend/package.json frontend/package-lock.json ./
RUN npm ci
COPY frontend/ ./
RUN npm run build -- --configuration production

# Etapa 2: servir. Solo viaja dist/. Ni Node ni node_modules llegan aqui.
FROM nginx:1.27-alpine
COPY --from=construir /app/dist/avisos/ /usr/share/nginx/html/
COPY docker/angular.nginx.conf /etc/nginx/conf.d/default.conf
EXPOSE 80

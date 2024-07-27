FROM node:lts-alpine

RUN apk --no-cache  add  supervisor
RUN yarn global add @vue/cli -g

WORKDIR /app

COPY frontend/package*.json ./
RUN npm install


#COPY docker/npm/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8080
CMD ["npm", "run","dev"]
#CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
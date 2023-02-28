FROM node:lts-alpine as builder

WORKDIR /app

COPY index.js package.json /app/
COPY views /app/views
RUN npm install

EXPOSE 8080

CMD [ "npm", "start" ]

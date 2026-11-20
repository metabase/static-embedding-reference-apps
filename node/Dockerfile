FROM node:lts-alpine

WORKDIR /app

ENV NODE_ENV=production
ENV PORT=8080

# Install the dependencies first, so Docker reuses this layer until they change.
COPY package.json package-lock.json /app/
RUN npm ci --omit=dev

COPY index.js /app/
COPY views /app/views

EXPOSE 8080

CMD [ "node", "index.js" ]

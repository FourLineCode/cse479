FROM node:18-alpine

WORKDIR /app

RUN npm install -g pnpm

COPY package.json ./package.json
COPY pnpm-lock.yaml ./pnpm-lock.yaml
RUN pnpm install

COPY . .
RUN pnpm build

EXPOSE 3000
CMD ["pnpm", "start"]

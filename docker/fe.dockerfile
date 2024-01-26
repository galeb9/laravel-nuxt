# base image
FROM node:16-alpine AS builder

# setup bash
RUN apk add --no-cache bash \
	git

# set working directory
WORKDIR /app

# install and cache app dependencies
COPY src/frontend/package.json /app/package.json
COPY src/frontend/yarn.lock /app/yarn.lock

RUN yarn install

ENV HOST 0.0.0.0

CMD ["yarn", "dev"]
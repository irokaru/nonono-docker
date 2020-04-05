FROM nginx:1.17-alpine

ARG TZ
RUN echo "${TZ}" > /etc/timezone \
   && dpkg-reconfigure -f noninteractive tzdata
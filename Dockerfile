FROM node:9

ENV consumer_key consumer_secret access_token access_token_secret mysql_host mysql_db mysql_user mysql_password NODE_ENV=debug

RUN npm install --save twitt

ADD post-tw.js /usr/src/app/post-tw.js

USER node

CMD ["node","post-tw.js"]

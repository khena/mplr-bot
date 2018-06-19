var Twit = require('twit')
 
var T = new Twit({
  consumer_key:         '...',
  consumer_secret:      '...',
  access_token:         '...',
  access_token_secret:  '...',
  timeout_ms:           60*1000,  // optional HTTP request timeout to apply to all requests.
  strictSSL:            true,     // optional - requires SSL certificates to be valid.
})

// open and get next mysql message

// random

// retrieve

// if check ok (240 car) >  post
 
T.post('statuses/update', { status: 'hello world!' }, function(err, data, response) {

  console.log(data)
})

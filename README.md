# Symfony Rest Bundle

## Introduction
Basic foundation for Rest Api with Symfony Rest Api Bundle

## Client Creation
In order to create client from command line just execute below code from the program root directory

```
php app/console Rest:Oauth:CreateClient --redirect-uri="CLIENT_HOST" --grant-type="authorization_code" --grant-type="password" --grant-type="refresh_token" --grant-type="token" --grant-type="client_credentials"
```
Here, CLIENT_HOST refers to the to the url of the client (where to redirect after succesfull authentication).

The command will register an OAuth client on the platform side, and will Output something like this (of course, you will see slightly different output)

```
Added a new client with public id 8_3ntffdg3idogok84s4cskc08swgoo48wcgc848k8sw8s004os4, secret egi10aw40fsw8kg0g8coo0skso40g8o8oo0so8kg80kokg4w4
```

### Check if it Works
Execute the following request in your browser with the client id and secret you have
```
http://localhost/testserver/web/app_dev.php/oauth/v2/token?client_id=8_3ntffdg3idogok84s4cskc08swgoo48wcgc848k8sw8s004os4&client_secret=egi10aw40fsw8kg0g8coo0skso40g8o8oo0so8kg80kokg4w4&grant_type=client_credentials

```

If response is like below client is created and token are also created
```
{"access_token":"MjMzNjVkMjA4MTM4NjM2ZTA3YzhmNWFkODI5ZWNlM2M1MGUzN2M5Y2E0NmZmZWQ3YmY1NzNhM2ExMmM0MzBjOA","expires_in":3600,"token_type":"bearer","scope":null}
```

## Test API
Now lets try to access our api,
```
http://localhost/testserver/web/app_dev.php/api/post/all
```
we will get the error, 
```
{"error":"access_denied","error_description":"OAuth2 authentication required"}
```
that's what we were after. It means that our API is protected. Now try with the access_token we have just received.
```
PROVIDER_HOST/api/post/all?access_token=MjMzNjVkMjA4MTM4NjM2ZTA3YzhmNWFkODI5ZWNlM2M1MGUzN2M5Y2E0NmZmZWQ3YmY1NzNhM2ExMmM0MzBjOA
```
For eg: On my localhost it looks like
```
http://localhost/testserver/web/app_dev.php/api/post/all?access_token=ZjNmNmZmNjBmNjIzNDdlZDM2MjQwYjNlMzYyMzMzYmNlMzY3MmJkMjY2ODVhMTA5ZjY4YTE1YWU1MzIxZWU3MA&expires_in=3600&token_type=bearer
```
If the response is valid json file or success then we are done!

## Get Authorization Code
Request url on the browser
```
PROVIDER_HOST/oauth/v2/auth?client_id=CLIENT_ID&response_type=code&redirect_uri=CLIENT_HOST
```
For eg: On my localhost it looks like
```
http://localhost/testserver/web/app_dev.php/oauth/v2/auth?client_id=8_3ntffdg3idogok84s4cskc08swgoo48wcgc848k8sw8s004os4&response_type=code&redirect_uri=CLIENT_HOST
```
here, the PROVIDER_HOST refers to the url of the service provider and 
CLIENT_ID refers to the public id received while client was created and
CLIENT_HOST refers to the redirecting url after authorization completes, it must be same as that at the time of client creation.

The page we are requesting will offer a login, then authorization of the client permissions, once confirming everything it will redirect us back to the url provided in redirect_url. In our case, redirect will look like
```
http://CLIENT_HOST?code=ZWI5NTJlYzdhNGJhNDE5YjYxODIxOTIxNjhjNmVjNWI3MzRkZWI5YTY2NDkzYTYwMzJmNTg1NTEyOGIxMzQwOQ
```
We will receive a code. This code is stored on the Provider side, and once we request for the token, it can uniquely identify the client which made request and the user.

Now,
## Requesting Token
Now, we can request token by 
```
PROVIDER_HOST/oauth/v2/token?client_id=CLIENT_ID&client_secret=CLIENT_SECRET&grant_type=authorization_code&redirect_uri=CLIENT_HOST&code=CODE
```
For eg: On my localhost it looks like
```
http://localhost/testserver/web/app_dev.php/oauth/v2/token?client_id=8_3ntffdg3idogok84s4cskc08swgoo48wcgc848k8sw8s004os4&client_secret=egi10aw40fsw8kg0g8coo0skso40g8o8oo0so8kg80kokg4w4&grant_type=authorization_code&redirect_uri=CLIENT_HOST&code=ZWI5NTJlYzdhNGJhNDE5YjYxODIxOTIxNjhjNmVjNWI3MzRkZWI5YTY2NDkzYTYwMzJmNTg1NTEyOGIxMzQwOQ
```

Then, we get Json response as 
```
{"access_token":"NzhlNzQ3OWI3YzY4NWQxYTg1NzJmNGU2MjlmOTQ0NTAwOGJkNmVmZTNkYjc3MjMxZjU3ZTAxMWE3OTE0YWVlOA","expires_in":3600,"token_type":"bearer","scope":null,"refresh_token":"ZDAwMmFjOGM5NjM2ZTZiNzcxMTQwYzBhN2Q1ZDAxMjVlZmJiMDY2NzNlNWZiOWQ0ZjYyYTg4Yjg5MzcxOGJjOA"}
```
which contains access_token and as code expires soon we might not get the response if it is requested slow. So, copy the uri and paste the code and reload.

## Implicit Grant
It’s similar to Authorization Code grant, it’s just a bit simpler. You just need to make only one request, and you will get the access_token as a part of redirect URL, there’s no need for second response. That’s for the situations where you trust the user and the client, but you still want the user to identify himself in the browser.
```
PROVIDER_HOST/oauth/v2/auth?client_id=8_3ntffdg3idogok84s4cskc08swgoo48wcgc848k8sw8s004os4&redirect_uri=CLIENT_HOST&response_type=token
```
then you will get redirected to
```
CLIENT_HOST#access_token=OGVmYzRhMjkyOTg3MTg4NTVkMzE4ZDI5MTdiYWEyN2M2YWM1MjQ0MjcxMTc0Yjc4MmMzNzc0NjVlYzcyYmNhOA&expires_in=3600&token_type=bearer
```

## Password flow
Let’s say you have no luxury of redirecting user to some website, then handle redirect call, all you have is just an application which is able to send HTTP requests. And you still want to somehow authenticate user on the server side, and all you have is username and password.

#### Request:
```
PROVIDER_HOST/oauth/v2/token?client_id=8_3ntffdg3idogok84s4cskc08swgoo48wcgc848k8sw8s004os4&client_secret=egi10aw40fsw8kg0g8coo0skso40g8o8oo0so8kg80kokg4w4&grant_type=password&username=USER_USERNAME&password=USER_PASSWORD
```
#### Response:
```
{"access_token":"MTVhM2JmZWIzNTNhNzY3N2YwNWFlZDcwYzQ2ODk5ODNkOWEwNTljYTRkNjQwNTBlZWEwYzc5MjMzOTVhNjVlZQ","expires_in":3600,"token_type":"bearer","scope":null,"refresh_token":"ZDQxM2Q1YjU2OTNlZjVjOTU0YTk0M2U2NmRkY2Y0NzlkMjQ3MDY3Y2Q2YmM0ODE2MTA0MzhiY2YyYjYzNjU3Mw"}
```

## Client Credentials
This one is the most simplistic flow of them all. You just need to provide CLIENT_ID and CLIENT_SECRET.
```
PROVIDER_HOST/oauth/v2/token?client_id=8_3ntffdg3idogok84s4cskc08swgoo48wcgc848k8sw8s004os4&client_secret=egi10aw40fsw8kg0g8coo0skso40g8o8oo0so8kg80kokg4w4&grant_type=client_credentials
```
Response will be
```
{"access_token":"Yzg4NmFiZTVjNDc5ZGQ5MDBhODBhOTRjY2ZkNjllNDRjOWRiY2Q4ZjJiNDVjMTY5ZGExNTdkNDRhNjIxMTdlYw","expires_in":3600,"token_type":"bearer","scope":null}
```

## Refresh flow
Before we mentioned that access_tokens have a lifetime of one hour, after which they will expire. With every access_token you were provided a refresh_token. You can exchange refresh token and get a new pair of access_token and refresh_token
```
PROVIDER_HOST/oauth/v2/token?client_id=8_3ntffdg3idogok84s4cskc08swgoo48wcgc848k8sw8s004os4&client_secret=egi10aw40fsw8kg0g8coo0skso40g8o8oo0so8kg80kokg4w4&grant_type=refresh_token&refresh_token=ZDQxM2Q1YjU2OTNlZjVjOTU0YTk0M2U2NmRkY2Y0NzlkMjQ3MDY3Y2Q2YmM0ODE2MTA0MzhiY2YyYjYzNjU3Mw
```
Response will be
```
{"access_token":NEW_ACCESS_TOKEN,"expires_in":3600,"token_type":"bearer","scope":"user","refresh_token":"NEW_REFRESH_TOKEN"}

///Example:
{"access_token":"Mzc3YzczMDY5NmI2YTUzOGY5YjUwNzcwOTI1OTgyODI2ZTNlODE0NWM4OTM1NDdkNjgxMmYwNWQ2MGMyNGViNg","expires_in":3600,"token_type":"bearer","scope":null,"refresh_token":"NjIxOWMzY2FmNWRhYmM0ZGVhZjVkNThjNGQ1MTM3NjQzNzc5MjYxY2JjZWI1ZjMyYTdmMDBiMDFjMjQ1NDQ3OQ"}
```

## Requesting API
Then, API can be requested with access token as
```
PROVIDER_HOST/api/post/all?access_token=ZjNmNmZmNjBmNjIzNDdlZDM2MjQwYjNlMzYyMzMzYmNlMzY3MmJkMjY2ODVhMTA5ZjY4YTE1YWU1MzIxZWU3MA&expires_in=3600&token_type=bearer
```
For eg: On my localhost it looks like
```
http://localhost/testserver/web/app_dev.php/api/post/all?access_token=ZjNmNmZmNjBmNjIzNDdlZDM2MjQwYjNlMzYyMzMzYmNlMzY3MmJkMjY2ODVhMTA5ZjY4YTE1YWU1MzIxZWU3MA&expires_in=3600&token_type=bearer
```
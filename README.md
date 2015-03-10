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
that's what we were after. It means that our API is protected and we will again try this once we have access_token.

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

## Requesting API
Then, API can be requested with access token as
```
PROVIDER_HOST/api/post/all?access_token=ZjNmNmZmNjBmNjIzNDdlZDM2MjQwYjNlMzYyMzMzYmNlMzY3MmJkMjY2ODVhMTA5ZjY4YTE1YWU1MzIxZWU3MA&expires_in=3600&token_type=bearer
```
For eg: On my localhost it looks like
```
http://localhost/testserver/web/app_dev.php/api/post/all?access_token=ZjNmNmZmNjBmNjIzNDdlZDM2MjQwYjNlMzYyMzMzYmNlMzY3MmJkMjY2ODVhMTA5ZjY4YTE1YWU1MzIxZWU3MA&expires_in=3600&token_type=bearer
```
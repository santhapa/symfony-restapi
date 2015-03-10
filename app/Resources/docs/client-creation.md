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
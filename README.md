#Symfony Rest Bundle

##Introduction
Basic foundation for Rest Api with Symfony Rest Api Bundle

##Client Creation
In order to create client from command line just execute below code from the program root directory

```
php app/console Rest:Oauth:CreateClient --redirect-uri="CLIENT_HOST" --grant-type="authorization_code" --grant-type="password" --grant-type="refresh_token" --grant-typ e="token" --grant-type="client_credentials"
```
Here, CLIENT_HOST refers to the to the url of the client (where to redirect after succesfull authentication).

The command will register an OAuth client on the platform side, and will Output something like this (of course, you will see slightly different output)

```
Added a new client with public id 7_9ymz4jb9rrsc48o4w8gg44gcsk4kwckg4og04k4okk8ogkw08, secret 2upzm384cxq848gos8k8sw8wwk4848g80ko8ww8sc8804gkcco
```

####Check if it Works
Execute the following request in your browser with the client id and secret you have
```
http://localhost/test/web/app_dev.php/oauth/v2/token?client_id=7_9ymz4jb9rrsc48o4w8gg44gcsk4kwckg4og04k4okk8ogkw08&client_secret=2upzm384cxq848gos8k8sw8wwk4848g80ko8ww8sc8804gkcco&grant_type=client_credentials

```

If respose is like below client is created and token are also created
```
{"access_token":"OGFmYzg0MWJlNmUxYzNkYjJkNDU2MmQ4MDU5OTliZjExMmVhNWU5MzUxZjhhNjUyNDBmYmFhNjU3ZWJiYmVjZg","expires_in":3600,"token_type":"bearer","scope":null}
```
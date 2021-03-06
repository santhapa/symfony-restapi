# OAuth

## Introduction
OAuth is an open standard for authorization. OAuth provides client applications a 'secure delegated access' to server resources on behalf of a resource owner. It specifies a process for resource owners to authorize third-party access to their server resources without sharing their credentials.

The docs are created for self understanding with a reference from [OAuth2 Explained Series](http://blog.tankist.de/blog/2013/07/16/oauth2-explained-part-1-principles-and-terminology/).

## Provider
So, now we have a functional platform, gathered some data and functionality and now it’s time when we need to provide this data and functionality over an API to our users, mobile devices and other platforms. We will be an OAuth Provider, rather soon.

## Access Token
Let’s say you have an API to protect. You need to provide some functionality over it, including passing back and forth sensible data.

Let’s assume, a user is able to log in using mobile application and request his current balance on his account. Simplest thing comes to your mind:
```
/user/15/balance
```
where 15 is user’s id? Bad idea. Everyone who is able to use any kind of development console for the browser can change user id and get access to a sensible data. How can you solve this?

Right. The same way PHP Sessions work for example. You generate a token, something similar to PHPSESSID, you create it at the moment when user logs in, pass this token back and forth with each request, and that’s how the server knows that this is the user associated with current session. There is no way one can manipulate session token in order to get data for specific user. And then when the user logs out you remove the token, or it just gets expired after awhile.

Congratulations, you just invented the main entity of OAuth – Access Token.

In OAuth world obtaining Access Token means getting access to the application. Once you get it, you attach the access token to each of your requests, and Provider can uniquely identify who you are and what actions are you allowed to perform. Obtained Access Token? Game over, You Won!

## Clients and Scopes
What if, we need a special kind of service which is able to set user balance, and that’s for each and one of them. One solution (and your operations team will really like it) you just give it another endpoint, and this endpoint is open only for white-listed IPs. If you googled for this article, most probably you already know at least 5 reasons why this solution stinks.

A better solution would be: You are able, somehow, on the backend side, distinguish between different type of consumers for your applications. Some are more privileged, some are less. Some are allowed to request only their own balance, some clients are allowed to manipulate this balances for all possible users. So we need a mechanism to specify different Clients and give them different privileges or in OAuth terminology – Scopes. You most probably don’t need a Client for each User, you are fine with one Client for all Users, one Client for a MobileApp, one Client for an accounting application. Each client gets a pair of credentials. Client ID and Client Secret. You need those in order to identify your client.

## Grant Types

If you ever used Facebook Login for websites, you know how the most popular Grant Type works. You get redirected to Provider’s website, if you are not yet logged in, the Provider offers you a login, then it asks for certain permissions, what can the Client do on your behalf, and then redirects you back. That’s the Authoritation_Code grant.

But then we have our accounting application. There’s no login associated with it, no web interface, it’s just a dumb-as-hell cronjob which just wants to trigger some HTTP requests to update user balances on your side. Then we use Client_Credentials grant type. It means in order to obtain access to the api, a client should only present his Client Id and Client Password.

Or an intermediate grant type – password. You provide a client id, client secret, username, password, and the Provider issues an access token, which is connected to this user, so when you get a request, you can identify which user made it.

So the Grant Type represents the flow needed for the Client to obtain Access Token.

## Advanced Topics
[Creating New Client](client-creation.md)

[Grant Types](default-grant-types.md)
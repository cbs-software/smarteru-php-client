# CBS\SmarterU\DataTypes\ExternalAuthorization  

The ExternalAuthorization class represents the information returned by the
SmarterU API when a user logs in through a third-party interface using the
RequestExternalAuthorization method.





## Methods

| Name | Description |
|------|-------------|
|[getAuthKey](#externalauthorizationgetauthkey)|Get the one-time authorization key.|
|[getRedirectPath](#externalauthorizationgetredirectpath)|Get the full path to redirect the user to SmarterU.|
|[getRequestKey](#externalauthorizationgetrequestkey)|Get the unique identifier of the authorization request.|
|[setAuthKey](#externalauthorizationsetauthkey)|Set the one-time authorization key.|
|[setRedirectPath](#externalauthorizationsetredirectpath)|Set the full path to redirect the user to SmarterU.|
|[setRequestKey](#externalauthorizationsetrequestkey)|Set the unique identifier of the authorization request.|




### ExternalAuthorization::getAuthKey  

**Description**

```php
public getAuthKey (void)
```

Get the one-time authorization key.

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> The authorization key


<hr />


### ExternalAuthorization::getRedirectPath  

**Description**

```php
public getRedirectPath (void)
```

Get the full path to redirect the user to SmarterU.

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> The full path to redirect the user to SmarterU


<hr />


### ExternalAuthorization::getRequestKey

**Description**

```php
public getRequestKey (void)
```

Get the unique identifier of the authorization request.

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> The unique identifier of the authorization request


<hr />


### ExternalAuthorization::setAuthKey  

**Description**

```php
public setAuthKey (string $authKey)
```

Set the one-time authorization key.

 

**Parameters**

* `(string) $authKey`
: The authorization key

**Return Values**

`self`




<hr />


### ExternalAuthorization::setRedirectPath  

**Description**

```php
public setRedirectPath (string $redirectPath)
```

Set the full path to redirect the user to SmarterU.

 

**Parameters**

* `(string) $redirectPath`
: The full path to redirect the user to SmarterU 

**Return Values**

`void`


<hr />


### ExternalAuthorization::setRequestKey  

**Description**

```php
public setRequestKey (string $requestKey)
```

Set the unique identifier of the authorization request.

 

**Parameters**

* `(string) $requestKey`
: The unique identifier of the authorization

**Return Values**

`self`




<hr />


# CBS\SmarterU\Client  

The Client class makes API calls and translates the response to the
appropriate object.





## Methods

| Name | Description |
|------|-------------|
|[__construct](#client__construct)|Initialize a client for making queries to the SmarterU API. By default a GuzzleHttp\Client will be automatically created as needed to communicate with the SmarterU API.|
|[createGroup](#clientcreategroup)|Make a CreateGroup query to the SmarterU API.|
|[createUser](#clientcreateuser)|Make a CreateUser query to the SmarterU API.|
|[getAccountApi](#clientgetaccountapi)|Get the account API key.|
|[getGroup](#clientgetgroup)|Make a GetGroup query to the SmarterU API.|
|[getHttpClient](#clientgethttpclient)|Get the HTTP Client. If the client has not already been provided, a new default HttpClient instance will be created and returned.|
|[getUser](#clientgetuser)|Make a GetUser query to the SmarterU API.|
|[getUserApi](#clientgetuserapi)|Get the user API key.|
|[getUserGroups](#clientgetusergroups)|Make a GetUserGroups query to the SmarterU API.|
|[listGroups](#clientlistgroups)|Make a ListGroups query to the SmarterU API.|
|[listUsers](#clientlistusers)|Make a ListUsers query to the SmarterU API.|
|[setAccountApi](#clientsetaccountapi)|Set the account API key.|
|[setHttpClient](#clientsethttpclient)|Set the HTTP Client.|
|[setUserApi](#clientsetuserapi)|Set the user API key.|
|[updateGroup](#clientupdategroup)|Make an UpdateGroup query to the SmarterU API.|
|[updateUser](#clientupdateuser)|Make an UpdateUser query to the SmarterU API.|




### Client::__construct  

**Description**

```php
public __construct (string|null $apiKey, string|null $apiUserKey)
```

Initialize a client for making queries to the SmarterU API. By default a GuzzleHttp\Client will be automatically created as needed to communicate with the SmarterU API. 

 

**Parameters**

* `(string|null) $apiKey`
: the account API key, used for authentication  
purposes when making requests to the SmarterU API. Defaults to null.  
You must set the account API key via the constructor or  
`setAccountApi` before invoking methods which interact with the  
SmarterU API  
* `(string|null) $apiUserKey`
: the user API key, used for authentication  
purposes when making requests to the SmarterU API. Defaults to null.  
You must set the user API key via the constructor or  
`setUserApi` before invoking methods which interact with the  
SmarterU API  

**Return Values**

`void`


<hr />


### Client::createGroup  

**Description**

```php
public createGroup (\Group $group)
```

Make a CreateGroup query to the SmarterU API. 

 

**Parameters**

* `(\Group) $group`
: The Group to create  

**Return Values**

`array`

> An array of [$result, $errors] where $result is an array  
of any information returned by the SmarterU API and $errors is an  
array of any error messages returned by the SmarterU API.


**Throws Exceptions**


`\MissingValueException`
> If the Account API Key and/or the User  
API Key are unset.

`\ClientException`
> If the HTTP response includes a status code  
indicating that an HTTP error has prevented the request from  
being made.

`\SmarterUException`
> If the response from the SmarterU API  
reports a fatal error that prevents the request from executing.

<hr />


### Client::createUser  

**Description**

```php
public createUser (\User $user)
```

Make a CreateUser query to the SmarterU API. 

 

**Parameters**

* `(\User) $user`
: the user to create  

**Return Values**

`array`

> An array of [$result, $errors] where $result is an array  
of any information returned by the SmarterU API and $errors is an  
array of any error messages returned by the SmarterU API.


**Throws Exceptions**


`\MissingValueException`
> If the Account API Key and/or the User  
API Key are unset.

`\ClientException`
> If the HTTP response includes a status code  
indicating that an HTTP error has prevented the request from  
being made.

`\SmarterUException`
> If the response from the SmarterU API  
reports a fatal error that prevents the request from executing.

<hr />


### Client::getAccountApi  

**Description**

```php
public getAccountApi (void)
```

Get the account API key. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the account API key


<hr />


### Client::getGroup  

**Description**

```php
public getGroup (\GetGroupQuery $query)
```

Make a GetGroup query to the SmarterU API. 

 

**Parameters**

* `(\GetGroupQuery) $query`
: The query representing the Group to return  

**Return Values**

`array`

> An array of [$result, $errors] where $result is an array  
of any information returned by the SmarterU API and $errors is an  
array of any error messages returned by the SmarterU API.


**Throws Exceptions**


`\MissingValueException`
> If the Account API Key and/or the User  
API Key are unset in both this instance of the Client and in the  
query passed in as a parameter.

`\ClientException`
> If the HTTP response includes a status code  
indicating that an HTTP error has prevented the request from  
being made.

`\SmarterUException`
> If the response from the SmarterU API  
reports a fatal error that prevents the request from executing.

<hr />


### Client::getHttpClient  

**Description**

```php
public getHttpClient (void)
```

Get the HTTP Client. If the client has not already been provided, a new default HttpClient instance will be created and returned. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\HttpClient`

> the HTTP Client


<hr />


### Client::getUser  

**Description**

```php
public getUser (\GetUserQuery $query)
```

Make a GetUser query to the SmarterU API. 

 

**Parameters**

* `(\GetUserQuery) $query`
: The query representing the User to return  

**Return Values**

`array`

> An array of [$result, $errors] where $result is an array  
of any information returned by the SmarterU API and $errors is an  
array of any error messages returned by the SmarterU API.


**Throws Exceptions**


`\MissingValueException`
> If the Account API Key and/or the User  
API Key are unset in both this instance of the Client and in the  
query passed in as a parameter.

`\ClientException`
> If the HTTP response includes a status code  
indicating that an HTTP error has prevented the request from  
being made.

`\SmarterUException`
> If the response from the SmarterU API  
reports a fatal error that prevents the request from executing.

<hr />


### Client::getUserApi  

**Description**

```php
public getUserApi (void)
```

Get the user API key. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the user API key


<hr />


### Client::getUserGroups  

**Description**

```php
public getUserGroups (\GetUserQuery $query)
```

Make a GetUserGroups query to the SmarterU API. 

 

**Parameters**

* `(\GetUserQuery) $query`
: The query representing the User whose Groups  
are to be read.  

**Return Values**

`array`

> An array of [$result, $errors] where $result is an array  
of any information returned by the SmarterU API and $errors is an  
array of any error messages returned by the SmarterU API.


**Throws Exceptions**


`\MissingValueException`
> If the Account API Key and/or the User  
API Key are unset in both this instance of the Client and in the  
query passed in as a parameter.

`\ClientException`
> If the HTTP response includes a status code  
indicating that an HTTP error has prevented the request from  
being made.

`\SmarterUException`
> If the response from the SmarterU API  
reports a fatal error that prevents the request from executing.

<hr />


### Client::listGroups  

**Description**

```php
public listGroups (\ListGroupsQuery $query)
```

Make a ListGroups query to the SmarterU API. 

 

**Parameters**

* `(\ListGroupsQuery) $query`
: The query representing the Groups to return  

**Return Values**

`array`

> An array of [$result, $errors] where $result is an array  
of any information returned by the SmarterU API and $errors is an  
array of any error messages returned by the SmarterU API.


**Throws Exceptions**


`\MissingValueException`
> If the Account API Key and/or the User  
API Key are unset in both this instance of the Client and in the  
query passed in as a parameter.

`\ClientException`
> If the HTTP response includes a status code  
indicating that an HTTP error has prevented the request from  
being made.

`\SmarterUException`
> If the response from the SmarterU API  
reports a fatal error that prevents the request from executing.

<hr />


### Client::listUsers  

**Description**

```php
public listUsers (\ListUsersQuery $query)
```

Make a ListUsers query to the SmarterU API. 

 

**Parameters**

* `(\ListUsersQuery) $query`
: The query representing the Users to return  

**Return Values**

`array`

> An array of [$result, $errors] where $result is an array  
of any information returned by the SmarterU API and $errors is an  
array of any error messages returned by the SmarterU API.


**Throws Exceptions**


`\MissingValueException`
> If the Account API Key and/or the User  
API Key are unset in both this instance of the Client and in the  
query passed in as a parameter.

`\ClientException`
> If the HTTP response includes a status code  
indicating that an HTTP error has prevented the request from  
being made.

`\SmarterUException`
> If the response from the SmarterU API  
reports a fatal error that prevents the request from executing.

<hr />


### Client::setAccountApi  

**Description**

```php
public setAccountApi (string $accountApi)
```

Set the account API key. 

 

**Parameters**

* `(string) $accountApi`
: the account API key  

**Return Values**

`self`




<hr />


### Client::setHttpClient  

**Description**

```php
public setHttpClient (\HttpClient $httpClient)
```

Set the HTTP Client. 

 

**Parameters**

* `(\HttpClient) $httpClient`
: The HTTP Client  

**Return Values**

`self`




<hr />


### Client::setUserApi  

**Description**

```php
public setUserApi (string $userApi)
```

Set the user API key. 

 

**Parameters**

* `(string) $userApi`
: the user API key  

**Return Values**

`self`




<hr />


### Client::updateGroup  

**Description**

```php
public updateGroup (\Group $group)
```

Make an UpdateGroup query to the SmarterU API. 

 

**Parameters**

* `(\Group) $group`
: The Group to update  

**Return Values**

`array`

> An array of [$result, $errors] where $result is an array  
of any information returned by the SmarterU API and $errors is an  
array of any error messages returned by the SmarterU API.


**Throws Exceptions**


`\MissingValueException`
> If the Account API Key and/or the User  
API Key are unset.

`\ClientException`
> If the HTTP response includes a status code  
indicating that an HTTP error has prevented the request from  
being made.

`\SmarterUException`
> If the response from the SmarterU API  
reports a fatal error that prevents the request from executing.

<hr />


### Client::updateUser  

**Description**

```php
public updateUser (\User $user)
```

Make an UpdateUser query to the SmarterU API. 

 

**Parameters**

* `(\User) $user`
: The User to update  

**Return Values**

`array`

> An array of [$result, $errors] where $result is an array  
of any information returned by the SmarterU API and $errors is an  
array of any error messages returned by the SmarterU API.


**Throws Exceptions**


`\MissingValueException`
> If the Account API Key and/or the User  
API Key are unset.

`\ClientException`
> If the HTTP response includes a status code  
indicating that an HTTP error has prevented the request from  
being made.

`\SmarterUException`
> If the response from the SmarterU API  
reports a fatal error that prevents the request from executing.

<hr />


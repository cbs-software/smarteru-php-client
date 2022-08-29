# CBS\SmarterU\Queries\BaseQuery  

Creates an XML representation of the elements that are universal to every
SmarterU API query.





## Methods

| Name | Description |
|------|-------------|
|[createBaseXml](#basequerycreatebasexml)|Create the base XML element containing the features that are common to all queries.|
|[getAccountApi](#basequerygetaccountapi)|Return the account API key.|
|[getUserApi](#basequerygetuserapi)|Return the user API key.|
|[setAccountApi](#basequerysetaccountapi)|Set the account API key.|
|[setUserApi](#basequerysetuserapi)|Set the user API key.|




### BaseQuery::createBaseXml  

**Description**

```php
public createBaseXml (void)
```

Create the base XML element containing the features that are common to all queries. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\SimpleXMLElement`

> The base XML element from which to build the query


**Throws Exceptions**


`\MissingValueException`
> if the Account API Key and/or the User API  
Key are not set.

<hr />


### BaseQuery::getAccountApi  

**Description**

```php
public getAccountApi (void)
```

Return the account API key. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the account API key


<hr />


### BaseQuery::getUserApi  

**Description**

```php
public getUserApi (void)
```

Return the user API key. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the user API key


<hr />


### BaseQuery::setAccountApi  

**Description**

```php
public setAccountApi (?string $accountApi)
```

Set the account API key. 

 

**Parameters**

* `(?string) $accountApi`
: the account API key  

**Return Values**

`self`




<hr />


### BaseQuery::setUserApi  

**Description**

```php
public setUserApi (?string $userApi)
```

Set the user API key. 

 

**Parameters**

* `(?string) $userApi`
: the user API key  

**Return Values**

`self`




<hr />


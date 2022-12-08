# CBS\SmarterU\Queries\GetUserQuery  

Represents a getUser query or a getUserGroups query made to the SmarterU API.



## Extend:

CBS\SmarterU\Queries\BaseQuery

## Methods

| Name | Description |
|------|-------------|
|[getEmail](#getuserquerygetemail)|Return the email address of the user.|
|[getEmployeeId](#getuserquerygetemployeeid)|Return the user's employee ID.|
|[getId](#getuserquerygetid)|Return the system-generated identifier for the user.|
|[getMethod](#getuserquerygetmethod)|Get the SmarterU API method the query is to be used for.|
|[setEmail](#getuserquerysetemail)|Set the email address for the user.|
|[setEmployeeId](#getuserquerysetemployeeid)|Set the employee ID for the user.|
|[setId](#getuserquerysetid)|Set the system-generated identifier for the user.|
|[setMethod](#getuserquerysetmethod)|Set the SmarterU API method the query is to be used for.|

## Inherited methods

| Name | Description |
|------|-------------|
|createBaseXml|Create the base XML element containing the features that are common to
all queries.|
|getAccountApi|Return the account API key.|
|getUserApi|Return the user API key.|
|setAccountApi|Set the account API key.|
|setUserApi|Set the user API key.|



### GetUserQuery::getEmail  

**Description**

```php
public getEmail (void)
```

Return the email address of the user. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> $email The user's email address


<hr />


### GetUserQuery::getEmployeeId  

**Description**

```php
public getEmployeeId (void)
```

Return the user's employee ID. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The user's employee ID


<hr />


### GetUserQuery::getId  

**Description**

```php
public getId (void)
```

Return the system-generated identifier for the user. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The system-generated identifier for the user if it exists


<hr />


### GetUserQuery::getMethod  

**Description**

```php
public getMethod (void)
```

Get the SmarterU API method the query is to be used for. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> the name of the API method


<hr />


### GetUserQuery::setEmail  

**Description**

```php
public setEmail (string $email)
```

Set the email address for the user. 

 

**Parameters**

* `(string) $email`
: The user's email address  

**Return Values**

`self`




<hr />


### GetUserQuery::setEmployeeId  

**Description**

```php
public setEmployeeId (string $employeeId)
```

Set the employee ID for the user. 

 

**Parameters**

* `(string) $employeeId`
: The user's employee ID  

**Return Values**

`self`




<hr />


### GetUserQuery::setId  

**Description**

```php
public setId (string $id)
```

Set the system-generated identifier for the user. 

 

**Parameters**

* `(string) $id`
: The system-generated identifier for the user  

**Return Values**

`self`




<hr />


### GetUserQuery::setMethod  

**Description**

```php
public setMethod (string $method)
```

Set the SmarterU API method the query is to be used for. 

 

**Parameters**

* `(string) $method`
: the name of the method  

**Return Values**

`self`




<hr />


# CBS\SmarterU\DataTypes\ErrorCode  

ErrorCode encapsulates an error as reported by the SmarterU API.

When reporting an error, the SmarterU API returns a list of errors; each
consisting of a code and a message.  





## Methods

| Name | Description |
|------|-------------|
|[__construct](#errorcode__construct)|Instantiate an ErrorCode|
|[getErrorCode](#errorcodegeterrorcode)|Get the error code|
|[getErrorMessage](#errorcodegeterrormessage)|Get the error message|




### ErrorCode::__construct  

**Description**

```php
public __construct (string $code, string $message)
```

Instantiate an ErrorCode 

 

**Parameters**

* `(string) $code`
: the error code.  
* `(string) $message`
: the error message.  

**Return Values**

`void`


<hr />


### ErrorCode::getErrorCode  

**Description**

```php
public getErrorCode (void)
```

Get the error code 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> the error code.


<hr />


### ErrorCode::getErrorMessage  

**Description**

```php
public getErrorMessage (void)
```

Get the error message 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> the error message.


<hr />


# CBS\SmarterU\Exceptions\SmarterUException  

An exception type to use when the SmarterU API returns a failure message
due to some kind of problem with the data provided.

## Implements:
Stringable, Throwable

## Extend:

Exception

## Methods

| Name | Description |
|------|-------------|
|[getErrorCodes](#smarteruexceptiongeterrorcodes)|Get the list of Error codes detected when the exception was thrown|

## Inherited methods

| Name | Description |
|------|-------------|
| [__construct](https://secure.php.net/manual/en/exception.__construct.php) | Construct the exception |
| [__toString](https://secure.php.net/manual/en/exception.__tostring.php) | String representation of the exception |
| [__wakeup](https://secure.php.net/manual/en/exception.__wakeup.php) | - |
| [getCode](https://secure.php.net/manual/en/exception.getcode.php) | Gets the Exception code |
| [getFile](https://secure.php.net/manual/en/exception.getfile.php) | Gets the file in which the exception was created |
| [getLine](https://secure.php.net/manual/en/exception.getline.php) | Gets the line in which the exception was created |
| [getMessage](https://secure.php.net/manual/en/exception.getmessage.php) | Gets the Exception message |
| [getPrevious](https://secure.php.net/manual/en/exception.getprevious.php) | Returns previous Exception |
| [getTrace](https://secure.php.net/manual/en/exception.gettrace.php) | Gets the stack trace |
| [getTraceAsString](https://secure.php.net/manual/en/exception.gettraceasstring.php) | Gets the stack trace as a string |



### SmarterUException::getErrorCodes  

**Description**

```php
public getErrorCodes (void)
```

Get the list of Error codes detected when the exception was thrown 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\ErrorCode[]`

> the list of error codes


<hr />


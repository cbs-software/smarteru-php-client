# CBS\SmarterU\Client  

The Client class makes API calls and translates the response to the
appropriate object.





## Methods

| Name | Description |
|------|-------------|
|[__construct](#client__construct)|Initialize a client for making queries to the SmarterU API. By default a GuzzleHttp\Client will be automatically created as needed to communicate with the SmarterU API.|
|[addUsersToGroup](#clientadduserstogroup)|Make an addUsersToGroup query to the SmarterU API. This query will add any specified User(s) to the Group, but will not grant them any permissions within the Group. If you would like the Users to have any additional permissions, you must call Client::grantPermissions() after assigning the User to the Group.|
|[createGroup](#clientcreategroup)|Make a CreateGroup query to the SmarterU API.|
|[createUser](#clientcreateuser)|Make a CreateUser query to the SmarterU API.|
|[getAccountApi](#clientgetaccountapi)|Get the account API key.|
|[getHttpClient](#clientgethttpclient)|Get the HTTP Client. If the client has not already been provided, a new default HttpClient instance will be created and returned.|
|[getLearnerReport](#clientgetlearnerreport)| Make a GetLearnerReport query to the SmarterU API.|
|[getXMLGenerator](#clientgetxmlgenerator)|Get the XML Generator. If one has not already been provided, a new default XMLGenerator instance will be created and returned.|
|[getUserApi](#clientgetuserapi)|Get the user API key.|
|[grantPermissions](#clientgrantpermissions)|Make a GrantPermissions request to the SmarterU API.|
|[listGroups](#clientlistgroups)|Make a ListGroups query to the SmarterU API.|
|[listUsers](#clientlistusers)|Make a ListUsers query to the SmarterU API.|
|[readGroupById](#clientreadgroupbyid)|Read the Group whose ID matches the one provided.|
|[readGroupByName](#clientreadgroupbyname)|Read the Group whose name matches the one provided.|
|[readGroupsForUserByEmail](#clientreadgroupsforuserbyEmail)|Read the Groups that have the specified User as a member. The User will be identified by their email address.|
|[readGroupsForUserByEmployeeId](#clientreadgroupsforuserbyEmployeeId)|Read the Groups that have the specified User as a member. The User will be identified by their employee ID.|
|[readGroupsForUserById](#clientreadgroupsforuserbyid)|Read the Groups that have the specified User as a member. The User will be identified by their ID.|
|[readUserByEmail](#clientreaduserbyEmail)|Read the User whose email address matches the one provided.|
|[readUserByEmployeeId](#clientreaduserbyEmployeeId)|Read the User whose employee ID matches the one provided.|
|[readUserById](#clientreaduserbyid)|Read the User whose ID matches the one provided.|
|[removeUsersFromGroup](#clientremoveusersfromgroup)|Make a removeUsersFromGroup query to the SmarterU API.|
|[revokePermissions](#clientrevokepermissions)|Make a RevokePermissions request to the SmarterU API.|
|[requestExternalAuthorizationByEmail](#clientrequestexternalauthorizationbyemail)|Make a RequestExternalAuthorization query to the SmarterU API using the email address to identify the user requesting authorization.|
|[requestExternalAuthorizationByEmployeeId](#clientrequestexternalauthorizationbyemployeeid)|Make a RequestExternalAuthorization query to the SmarterU API using the employee ID to identify the user requesting authorization.|
|[setAccountApi](#clientsetaccountapi)|Set the account API key.|
|[setHttpClient](#clientsethttpclient)|Set the HTTP Client.|
|[setUserApi](#clientsetuserapi)|Set the user API key.|
|[setXMLGenerator](#clientsetxmlgenerator)|Set the XML Generator.|
|[updateGroup](#clientupdategroup)|Make an UpdateGroup query to the SmarterU API. In the event that the Group's name and/or ID are being updated, the fields used to keep track of the old values in the Group object will be erased while making the request. This prevents outdated information from mistakenly being passed into the SmarterU API when making an additional updateGroup query after updating a Group's name and/or ID.|
|[updateUser](#clientupdateuser)|Make an UpdateUser query to the SmarterU API.|




### Client::__construct  

**Description**

```php
public __construct (string $apiKey, string $apiUserKey)
```

Initialize a client for making queries to the SmarterU API. By default a
GuzzleHttp\Client will be automatically created as needed to communicate
with the SmarterU API. 

 

**Parameters**

* `(string) $apiKey`
: the account API key, used for authentication  
purposes when making requests to the SmarterU API.
* `(string) $apiUserKey`
: the user API key, used for authentication  
purposes when making requests to the SmarterU API. 

**Return Values**

`void`


<hr />

### Client::addUsersToGroup

**Description**

```php
public addUsersToGroup(array $users, \Group $group)
```

Make an addUsersToGroup query to the SmarterU API. This query will add any
specified User(s) to the Group, but will not grant them any permissions within
the Group. If you would like the Users to have any additional permissions, you
must call Client::grantPermissions() after assigning the User to the Group.

**Parameters** 

* `(array) $users`
: An array containing one or more Users to add to the Group.

* `(\Group) $group`
: The Group to which the User(s) will be added.

**Return Values**

`Group`

> The Group as updated by the SmarterU API.

**Exceptions**

`\InvalidArgumentException`
> If the "$users" array contains a value that is not a User.

`\MissingValueException` 
> If the "$users" array contains a User that does not have an email address or
an employee ID, or if the Group does not have a name or an ID.

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error
has prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents
the request from executing.


<hr />

### Client::createGroup 

**Description**

```php
public createGroup (\Group $group)
```

Make a CreateGroup query to the SmarterU API. 

 

**Parameters**

* `(\Group) $group`
: the group to create  

**Return Values**

`\Group`

> The group as created by the API.


**Throws Exceptions**


`\MissingValueException`
> If one of the Tags is missing both its name and its ID.

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

`\User`

> The user as created by the API.


**Throws Exceptions**


`\MissingValueException`
> If the User to be created does not have an email address or an employee ID,
or does not have a home Group.

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

`string`

> the account API key


<hr />


### Client::getHttpClient  

**Description**

```php
public getHttpClient (void)
```

Get the HTTP Client. If the client has not already been provided, a
new default HttpClient instance will be created and returned. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\HttpClient`

> the HTTP Client


<hr />

### Client::getLearnerReport

**Description**

```php
public getLearnerReport (\GetLearnerReportQuery $query)
```

Make a GetLearnerReport query to the SmarterU API.

**Parameters**

* `(\GetLearnerReportQuery) $query`
: The query by which to filter the results of the Learner Report.

**Return Values**

`array`

> A list of all LearnerReports matching the query.

**Throws Exceptions**

`\MissingValueException`
> If the Group(s) or User(s) to include in the report are not identified, or if
Groups are filtered by tags that are not identified.

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error has
prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents the
request from executing.


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

`string`

> the user API key


<hr />

### Client::getXMLGenerator

**Description**

```php
public getXMLGenerator (void)
```

Get the XML Generator. If one has not already been provided, a new default
XMLGenerator instance will be created and returned.

**Parameters**

`This function has no parameters.`

**Return Values**

`\XMLGenerator`

> The XML Generator.


<hr />

### Client::grantPermissions

**Description**

```php
public grantPermissions (\User $user, \Group $group, array $permissions)
```

Make a GrantPermissions request to the SmarterU API.

**Parameters**

* `(\User) $user`
: The User to grant permissions to.

* `(\Group) $group`
: The Group in which the User will be granted the specified permissions.

* `(array) $permissions`
: The permissions to be granted to the specified User within the specified Group.

**Return Values**

`\User`

> The User as updated by the SmarterU API.

**Throws Exceptions**

`\InvalidArgumentException`
> If any value in the "$permissions" array is not a string or is not one of the
permissions accepted by the SmarterU API.

`\MissingValueException`
> If the User whose permissions are being modified doesn't have an email
address or an employee ID, or if the Group in which the permissions are being
modified does not have a name or an ID.

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error
has prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents
the request from executing.


<hr />

### Client::listGroups

**Description**

```php
public listGroups (\ListGroupsQuery $query)
```

Make a ListGroups query to the SmarterU API.

**Parameters**

* `(\ListGroupsQuery) $query`
: The query representing the Groups to return.

**Return Values**

`array`

> An array of any Groups returned by the SmarterU API.

**Throws Exceptions**

`\MissingValueException`
> If one of the Tags by which to filter the Groups is missing both its name
and its ID.

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error has
prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents the
request from executing.


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

> An array of all Users matching the parameters specified by the query passed
into the SmarterU API.


**Throws Exceptions**

`\ClientException`
> If the HTTP response includes a status code  
indicating that an HTTP error has prevented the request from  
being made.

`\SmarterUException`
> If the response from the SmarterU API  
reports a fatal error that prevents the request from executing.

<hr />


### Client::readGroupById

**Description**

```php
public readGroupById (string $id)
```

Read the Group whose ID matches the one provided.

**Parameters**

* `(string) $id`
: The ID of the Group to return.

**Return Values**

`(\Group)`

> The Group as read by the API

`(null)`

> Null if no Group matching the provided ID exists within your SmarterU account.

**Throws Exceptions**

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error has
prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents the
request from executing.


<hr />

### Client::readGroupByName

**Description**

```php
public readGroupByName (string $name)
```

Read the Group whose name matches the one provided.

**Parameters**

* `(string) $name`
: The name of the Group to return.

**Return Values**

`(\Group)`

> The Group as read by the API

`(null)`

> Null if no Group matching the provided name exists within your SmarterU account.

**Throws Exceptions**

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error has
prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents the
request from executing.


<hr />

### Client::readGroupsForUserByEmail

**Description**

```php
public readGroupsForUserByEmail (string $email)
```

Read the Groups that have the specified User as a member. The user will be
identified by their email address.

**Parameters**

* `(string) $email`
: The email address of the User to search for.

**Return Values**

`(array)`

> All Groups that User is a member of.

**Throws Exceptions**

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error has
prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents the
request from executing.


<hr />


### Client::readGroupsForUserByEmployeeId

**Description**

```php
public readGroupsForUserByEmployeeId (string $employeeId)
```

Read the Groups that have the specified User as a member. The user will be
identified by their employee ID.

**Parameters**

* `(string) $employeeId`
: The employee ID of the User to search for.

**Return Values**

`(array)`

> All Groups that User is a member of.

**Throws Exceptions**

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error has
prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents the
request from executing.


<hr />

### Client::readGroupsForUserById

**Description**

```php
public readGroupsForUserById (string $id)
```

Read the Groups that have the specified User as a member. The user will be
identified by their ID.

**Parameters**

* `(string) $id`
: The ID of the User to search for.

**Return Values**

`(array)`

> All Groups that User is a member of.

**Throws Exceptions**

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error has
prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents the
request from executing.


<hr />

### Client::readUserByEmail

/**
     * Read the User whose ID matches the one provided.
     *
     * @param string $employeeId The ID of the User to return.
     * @return ?User The User whose ID matches the provided ID,
     *      or null if no such User exists within your SmarterU Account.
     * @throws ClientException If the HTTP response includes a status code
     *      indicating that an HTTP error has prevented the request from
     *      being made.
     * @throws SmarterUException If the response from the SmarterU API
     *      reports a fatal error that prevents the request from executing.
     */

**Description**

```php
public readUserByEmail(string $email)
```

Read the User whose email address matches the one provided.

**Parameters**

* `(string) $email`
: The email address of the User to return.

**Return Values**

`(\User)`

> The User whose email address matches the provided email.

`(null)`

> Null if no such User exists within your SmarterU account.

**Throws Exceptions**

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error has
prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents the
request from executing.


<hr />

### Client::readUserByEmployeeId

**Description**

```php
public readUserByEmployeeId(string $employeeId)
```

Read the User whose employee ID matches the one provided.

**Parameters**

* `(string) $employeeId`
: The employee ID of the User to return.

**Return Values**

`(\User)`

> The User whose employee ID matches the provided employee ID.

`(null)`

> Null if no such User exists within your SmarterU account.

**Throws Exceptions**

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error has
prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents the
request from executing.


<hr />

### Client::readUserById

**Description**

```php
public readUserById(string $id)
```

Read the User whose ID matches the one provided.

**Parameters**

* `(string) $id`
: The ID of the User to return.

**Return Values**

`(\User)`

> The User whose ID matches the provided ID.

`(null)`

> Null if no such User exists within your SmarterU account.

**Throws Exceptions**

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error has
prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents the
request from executing.


<hr />

### Client::removeUsersFromGroup

**Description**

```php
public removeUsersFromGroup(array $users, \Group $group)
```

Make an removeUsersFromGroup query to the SmarterU API.

**Parameters** 

* `(array) $users`
: An array containing one or more Users to remove from the Group.

* `(\Group) $group`
: The Group from which the User(s) will be removed.

**Return Values**

`Group`

> The Group as updated by the SmarterU API.

**Exceptions**

`\InvalidArgumentException`
> If the "$users" array contains a value that is not a User.

`\MissingValueException` 
> If the "$users" array contains a User that does not have an email address or
an employee ID, or if the Group does not have a name or an ID.

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error
has prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents
the request from executing.


<hr />

### Client::revokePermissions

**Description**

```php
public revokePermissions (\User $user, \Group $group, array $permissions)
```

Make a RevokePermissions request to the SmarterU API.

**Parameters**

* `(\User) $user`
: The User to revoke permissions from.

* `(\Group) $group`
: The Group in which the User will have the specified permissions revoked.

* `(array) $permissions`
: The permissions to be revoked from the specified User within the specified Group.

**Return Values**

`\User`

> The User as updated by the SmarterU API.

**Throws Exceptions**

`\InvalidArgumentException`
> If any value in the "$permissions" array is not a string or is not one of the
permissions accepted by the SmarterU API.

`\MissingValueException`
> If the User whose permissions are being modified doesn't have an email
address or an employee ID, or if the Group in which the permissions are being
modified does not have a name or an ID.

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error
has prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents
the request from executing.


<hr />

### Client::requestExternalAuthorizationByEmail

**Description**

```php
public requestExternalAuthorizationByEmail (string $email)
```

Make a RequestExternalAuthorization query to the SmarterU API using the email
address to identify the user requesting authorization.

**Parameters**

* `(string) $email`
: The email address of the user requesting external authorization.

**Return Values**

`\ExternalAuthorization`

> A container for the authorization keys and URL returned by the SmarterU API.

**Throws Exceptions**

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error has
prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents the
request from executing.

<hr />

### Client::requestExternalAuthorizationByEmployeeId

**Description**

```php
public requestExternalAuthorizationByEmployeeId (string $employeeId)
```

Make a RequestExternalAuthorization query to the SmarterU API using the employee
ID to identify the user requesting authorization.

**Parameters**

* `(string) $employeeId`
: The employee ID of the user requesting external authorization.

**Return Values**

`\ExternalAuthorization`

> A container for the authorization keys and URL returned by the SmarterU API.

**Throws Exceptions**

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error has
prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents the
request from executing.

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


### Client::setXMLGenerator

Set the XML Generator.
     *
     * @param XMLGenerator $xmlGenerator The XML generator.
     * @return self

**Description**

```php
public setXMLGenerator (XMLGenerator $xmlGenerator)
```

Set the XML Generator.

**Parameters**

* `(\XMLGenerator) $xmlGenerator`
: The XML generator.

**Return Values**

`self`




<hr />

### Client::updateGroup

**Description**

```php
public updateGroup (\Group $group)
```

Make an UpdateGroup query to the SmarterU API. In the event that the Group's
name and/or ID are being updated, the fields used to keep track of the old
values in the Group object will be erased while making the request. This
prevents outdated information from mistakenly being passed into the SmarterU
API when making an additional updateGroup query after updating a Group's name
and/or ID.

**Parameters**

* `(\Group) $group`
: The Group to update

**Return Values**

`\Group`

> The Group as updated by the SmarterU API.

**Throws Exceptions**

`\MissingValueException`
> If one of the Tags is missing both its name and its ID.

`\ClientException`
> If the HTTP response includes a status code indicating that an HTTP error has
prevented the request from being made.

`\SmarterUException`
> If the response from the SmarterU API reports a fatal error that prevents the
request from executing.


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

`\User`

> The User as updated by the SmarterU API.


**Throws Exceptions**


`\MissingValueException`
> If the User being updated does not have an email address or an employee ID.

`\ClientException`
> If the HTTP response includes a status code  
indicating that an HTTP error has prevented the request from  
being made.

`\SmarterUException`
> If the response from the SmarterU API  
reports a fatal error that prevents the request from executing.

<hr />


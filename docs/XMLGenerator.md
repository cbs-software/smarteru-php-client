# CBS\SmarterU\XMLGenerator  

This class contains helper methods for Client that generate the XML body of
the request to be made to the SmarterU API.





## Methods

| Name | Description |
|------|-------------|
|[changeGroupMembers](#xmlgeneratorchangegroupmembers)|Generate the XML body for an addUsersToGroup or removeUsersFromGroup query. Functionally, this is just an updateGroup query with most values hardcoded to be left blank.|
|[changePermissions](#xmlgeneratorchangepermissions)|Generate the XML body for a grantPermission or revokePermission query.|
|[createGroup](#xmlgeneratorcreategroup)|Generate the XML body for a createGroup query.|
|[createUser](#xmlgeneratorcreateuser)|Generate the XML body for a createUser query.|
|[getGroup](#xmlgeneratorgetgroup)|Generate the XML body for a getGroup query.|
|[getLearnerReport](#xmlgeneratorgetlearnerreport)|Generate the XML body for a getLearnerReport query.|
|[getUser](#xmlgeneratorgetuser)|Generate the XML body for a GetUser query.|
|[listGroups](#xmlgeneratorlistgroups)|Generate the XML body for a listGroups query.|
|[listUsers](#xmlgeneratorlistusers)|Generate the XML body for a ListUsers query.|
|[updateGroup](#xmlgeneratorupdategroup)|Generate the XML body for an updateGroup query.|
|[updateUser](#xmlgeneratorupdateuser)|Generate the XML body for an updateUser query.|




### XMLGenerator::changeGroupMembers  

**Description**

```php
public changeGroupMembers (string $accountApi, string $userApi, array $users, \Group $group, string $action)
```

Generate the XML body for an addUsersToGroup or removeUsersFromGroup query. Functionally, this is just an updateGroup query with most values hardcoded to be left blank. 

 

**Parameters**

* `(string) $accountApi`
: The SmarterU API key identifying the account  
making the request.  
* `(string) $userApi`
: The SmarterU API key identifying the user within  
that account who is making the request.  
* `(array) $users`
: The Users who are being added to or removed from the  
Group.  
* `(\Group) $group`
: The Group to which the Users are being added or  
removed.  
* `(string) $action`
: Whether the Users are being added to or removed  
from the Group.  

**Return Values**

`string`

> an XML representation of the query.


**Throws Exceptions**


`\InvalidArgumentException`
> If the "$users" array contains a value  
that is not a User.

`\MissingValueException`
> If the "$users" array contains a User that  
does not have an email address or an employee ID, or if the Group  
does not have a name or an ID.

<hr />


### XMLGenerator::changePermissions  

**Description**

```php
public changePermissions (string $accountApi, string $userApi, \User $user, \Group $group, array $permissions, string $action)
```

Generate the XML body for a grantPermission or revokePermission query. 

Functionally, this is just a specific type of updateGroup query. 

**Parameters**

* `(string) $accountApi`
: The SmarterU API key identifying the account  
making the request.  
* `(string) $userApi`
: The SmarterU API key identifying the user within  
that account who is making the request.  
* `(\User) $user`
: The User whose permissions within the Group are being  
updated.  
* `(\Group) $group`
: The Group in which the User's permissions are being  
updated.  
* `(array) $permissions`
: The permissions to be granted or revoked.  
* `(string) $action`
: Whether the permissions are being granted or revoked.  

**Return Values**

`string`

> an XML representation of the query.


**Throws Exceptions**


`\MissingValueException`
> If the user whose permissions are being  
modified doesn't have an email address or an employee ID, or if the  
Group in which the permissions are being modified does not have a  
name or an ID.

<hr />


### XMLGenerator::createGroup  

**Description**

```php
public createGroup (string $accountApi, string $userApi, \Group $group)
```

Generate the XML body for a createGroup query. 

 

**Parameters**

* `(string) $accountApi`
: The SmarterU API key identifying the account  
making the request.  
* `(string) $userApi`
: The SmarterU API key identifying the user within  
that account who is making the request.  
* `(\Group) $group`
: The Group to translate to XML  

**Return Values**

`string`

> an XML representation of the Group


**Throws Exceptions**


`\MissingValueException`
> If one of the Tags is missing both its  
name and its ID.

<hr />


### XMLGenerator::createUser  

**Description**

```php
public createUser (string $accountApi, string $userApi, \User $user)
```

Generate the XML body for a createUser query. 

 

**Parameters**

* `(string) $accountApi`
: The SmarterU API key identifying the account  
making the request.  
* `(string) $userApi`
: The SmarterU API key identifying the user within  
that account who is making the request.  
* `(\User) $user`
: The User to translate to XML  

**Return Values**

`string`

> an XML representation of the User


**Throws Exceptions**


`\MissingValueException`
> If the User to be created does not have  
an email address or an employee ID, or does not have a home Group.

<hr />


### XMLGenerator::getGroup  

**Description**

```php
public getGroup (string $accountApi, string $userApi, \GetGroupQuery $query)
```

Generate the XML body for a getGroup query. 

 

**Parameters**

* `(string) $accountApi`
: The SmarterU API key identifying the account  
making the request.  
* `(string) $userApi`
: The SmarterU API key identifying the user within  
that account who is making the request.  
* `(\GetGroupQuery) $query`
: The query to translate to XML  

**Return Values**

`string`

> an XML representation of the query


**Throws Exceptions**


`\MissingValueException`
> if the group identifier is not set.

<hr />


### XMLGenerator::getLearnerReport  

**Description**

```php
public getLearnerReport (string $accountApi, string $userApi, \GetLearnerReportQuery $query)
```

Generate the XML body for a getLearnerReport query. 

 

**Parameters**

* `(string) $accountApi`
: The SmarterU API key identifying the account  
making the request.  
* `(string) $userApi`
: The SmarterU API key identifying the user within  
that account who is making the request.  
* `(\GetLearnerReportQuery) $query`
: The query containing the parameters  
by which to filter the results.  

**Return Values**

`string`

> an XML representation of the query.


**Throws Exceptions**


`\MissingValueException`
> If the Group(s) or User(s) to include in  
the report are not identified, or if Groups are filtered by tags  
that are not identified.

<hr />


### XMLGenerator::getUser  

**Description**

```php
public getUser (string $accountApi, string $userApi, \GetUserQuery $query)
```

Generate the XML body for a GetUser query. 

 

**Parameters**

* `(string) $accountApi`
: The SmarterU API key identifying the account  
making the request.  
* `(string) $userApi`
: The SmarterU API key identifying the user within  
that account who is making the request.  
* `(\GetUserQuery) $query`
: The query to translate to XML  

**Return Values**

`string`

> an XML representation of the query


<hr />


### XMLGenerator::listGroups  

**Description**

```php
public listGroups (string $accountApi, string $userApi, \ListGroupsQuery $query)
```

Generate the XML body for a listGroups query. 

 

**Parameters**

* `(string) $accountApi`
: The SmarterU API key identifying the account  
making the request.  
* `(string) $userApi`
: The SmarterU API key identifying the user within  
that account who is making the request.  
* `(\ListGroupsQuery) $query`
: The query to translate to XML  

**Return Values**

`string`

> an XML representation of the query


**Throws Exceptions**


`\MissingValueException`
> If one of the Tags by which to filter the  
Groups is missing both its name and its ID.

<hr />


### XMLGenerator::listUsers  

**Description**

```php
public listUsers (string $accountApi, string $userApi, \ListUsersQuery $query)
```

Generate the XML body for a ListUsers query. 

 

**Parameters**

* `(string) $accountApi`
: The SmarterU API key identifying the account  
making the request.  
* `(string) $userApi`
: The SmarterU API key identifying the user within  
that account who is making the request.  
* `(\ListUsersQuery) $query`
: The query to translate to XML  

**Return Values**

`string`

> an XML representation of the query


<hr />


### XMLGenerator::updateGroup  

**Description**

```php
public updateGroup (string $accountApi, string $userApi, \Group $group)
```

Generate the XML body for an updateGroup query. 

 

**Parameters**

* `(string) $accountApi`
: The SmarterU API key identifying the account  
making the request.  
* `(string) $userApi`
: The SmarterU API key identifying the user within  
that account who is making the request.  
* `(\Group) $group`
: The Group to translate to XML  

**Return Values**

`string`

> an XML representation of the User


**Throws Exceptions**


`\MissingValueException`
> If one of the Tags is missing both its  
name and its ID.

<hr />


### XMLGenerator::updateUser  

**Description**

```php
public updateUser (string $accountApi, string $userApi, \User $user)
```

Generate the XML body for an updateUser query. 

 

**Parameters**

* `(string) $accountApi`
: The SmarterU API key identifying the account  
making the request.  
* `(string) $userApi`
: The SmarterU API key identifying the user within  
that account who is making the request.  
* `(\User) $user`
: The User to translate to XML  

**Return Values**

`string`

> an XML representation of the User


**Throws Exceptions**


`\MissingValueException`
> If the User being updated does not have an  
email address or an employee ID.

<hr />


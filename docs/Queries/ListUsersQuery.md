# CBS\SmarterU\Queries\ListUsersQuery  

Represents a listUsers query made to the SmarterU API.



## Extend:

CBS\SmarterU\Queries\BaseQuery

## Methods

| Name | Description |
|------|-------------|
|[getCreatedDate](#listusersquerygetcreateddate)|Return the date range when the user's account was created.|
|[getEmail](#listusersquerygetemail)|Return the tag representing the email to query for.|
|[getEmployeeId](#listusersquerygetemployeeid)|Return the tag representing the employee ID to query for.|
|[getGroupName](#listusersquerygetgroupname)|Return the name of the group containing the users to query for.|
|[getHomeGroup](#listusersquerygethomegroup)|Get the name of the home group of users to query for.|
|[getModifiedDate](#listusersquerygetmodifieddate)|Return the date range when the user's account was last updated.|
|[getName](#listusersquerygetname)|Return the tag representing the name to query for.|
|[getPage](#listusersquerygetpage)|Return the page to get.|
|[getPageSize](#listusersquerygetpagesize)|Return the maximum number of users to return.|
|[getSortField](#listusersquerygetsortfield)|Return the field used to sort results.|
|[getSortOrder](#listusersquerygetsortorder)|Return the direction the results are sorted in.|
|[getTeams](#listusersquerygetteams)|Return the container for the teams the user is assigned to.|
|[getUserStatus](#listusersquerygetuserstatus)|Return the status of the users to query for.|
|[setCreatedDate](#listusersquerysetcreateddate)|Set the date range when the user's account was created.|
|[setEmail](#listusersquerysetemail)|Set the tag representing the email to query for.|
|[setEmployeeId](#listusersquerysetemployeeid)|Set the tag representing the employee ID to query for.|
|[setGroupName](#listusersquerysetgroupname)|Set the name of the group containing the users to query for.|
|[setHomeGroup](#listusersquerysethomegroup)|Set the name of the home group of users to query for.|
|[setModifiedDate](#listusersquerysetmodifieddate)|Set the date range when the user's account was last updated.|
|[setName](#listusersquerysetname)|Set the tag representing the name to query for.|
|[setPage](#listusersquerysetpage)|Set the page to get.|
|[setPageSize](#listusersquerysetpagesize)|Set the maximum number of users to return. Cannot be greater than 1000.|
|[setSortField](#listusersquerysetsortfield)|Set the field used to sort results.|
|[setSortOrder](#listusersquerysetsortorder)|Set the direction the results are sorted in.|
|[setTeams](#listusersquerysetteams)|Set the container for the teams the user is assigned to.|
|[setUserStatus](#listusersquerysetuserstatus)|Set the status of the users to query for.|

## Inherited methods

| Name | Description |
|------|-------------|
|createBaseXml|Create the base XML element containing the features that are common to
all queries.|
|getAccountApi|Return the account API key.|
|getUserApi|Return the user API key.|
|setAccountApi|Set the account API key.|
|setUserApi|Set the user API key.|



### ListUsersQuery::getCreatedDate  

**Description**

```php
public getCreatedDate (void)
```

Return the date range when the user's account was created. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\DateRangeTag`

> the date range when the user's account was created


<hr />


### ListUsersQuery::getEmail  

**Description**

```php
public getEmail (void)
```

Return the tag representing the email to query for. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\MatchTag`

> the tag representing the email to query for


<hr />


### ListUsersQuery::getEmployeeId  

**Description**

```php
public getEmployeeId (void)
```

Return the tag representing the employee ID to query for. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\MatchTag`

> the tag representing the employee ID to query for


<hr />


### ListUsersQuery::getGroupName  

**Description**

```php
public getGroupName (void)
```

Return the name of the group containing the users to query for. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the name of the group containing the users to query for


<hr />


### ListUsersQuery::getHomeGroup  

**Description**

```php
public getHomeGroup (void)
```

Get the name of the home group of users to query for. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the name of the home group


<hr />


### ListUsersQuery::getModifiedDate  

**Description**

```php
public getModifiedDate (void)
```

Return the date range when the user's account was last updated. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\DateRangeTag`

> the date range when the user's account was last updated


<hr />


### ListUsersQuery::getName  

**Description**

```php
public getName (void)
```

Return the tag representing the name to query for. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\MatchTag`

> the tag representing the name to query for


<hr />


### ListUsersQuery::getPage  

**Description**

```php
public getPage (void)
```

Return the page to get. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?int`

> the page to get


<hr />


### ListUsersQuery::getPageSize  

**Description**

```php
public getPageSize (void)
```

Return the maximum number of users to return. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?int`

> The maximum number of users to return.


<hr />


### ListUsersQuery::getSortField  

**Description**

```php
public getSortField (void)
```

Return the field used to sort results. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the field used to sort results


<hr />


### ListUsersQuery::getSortOrder  

**Description**

```php
public getSortOrder (void)
```

Return the direction the results are sorted in. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the field used to sort results


<hr />


### ListUsersQuery::getTeams  

**Description**

```php
public getTeams (void)
```

Return the container for the teams the user is assigned to. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?array`

> the container for the teams the user is assigned to


<hr />


### ListUsersQuery::getUserStatus  

**Description**

```php
public getUserStatus (void)
```

Return the status of the users to query for. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> the status of the users to query for


<hr />


### ListUsersQuery::setCreatedDate  

**Description**

```php
public setCreatedDate (?\DateRangeTag $createdDate)
```

Set the date range when the user's account was created. 

 

**Parameters**

* `(?\DateRangeTag) $createdDate`
: the date range when the user's account  
was created  

**Return Values**

`self`




<hr />


### ListUsersQuery::setEmail  

**Description**

```php
public setEmail (?\MatchTag $email)
```

Set the tag representing the email to query for. 

 

**Parameters**

* `(?\MatchTag) $email`
: the email to query for  

**Return Values**

`self`




<hr />


### ListUsersQuery::setEmployeeId  

**Description**

```php
public setEmployeeId (?\MatchTag $employeeId)
```

Set the tag representing the employee ID to query for. 

 

**Parameters**

* `(?\MatchTag) $employeeId`
: the employee ID to query for  

**Return Values**

`self`




<hr />


### ListUsersQuery::setGroupName  

**Description**

```php
public setGroupName (?string $groupName)
```

Set the name of the group containing the users to query for. 

 

**Parameters**

* `(?string) $groupName`
: the name of the group containing the users to query for  

**Return Values**

`self`




<hr />


### ListUsersQuery::setHomeGroup  

**Description**

```php
public setHomeGroup (string $homeGroup)
```

Set the name of the home group of users to query for. 

 

**Parameters**

* `(string) $homeGroup`
: the name of the home group  

**Return Values**

`self`




<hr />


### ListUsersQuery::setModifiedDate  

**Description**

```php
public setModifiedDate (?\DateRangeTag $modifiedDate)
```

Set the date range when the user's account was last updated. 

 

**Parameters**

* `(?\DateRangeTag) $modifiedDate`
: the date range when the user's account  
was last modified  

**Return Values**

`self`




<hr />


### ListUsersQuery::setName  

**Description**

```php
public setName (?\MatchTag $name)
```

Set the tag representing the name to query for. 

 

**Parameters**

* `(?\MatchTag) $name`
: the name to query for  

**Return Values**

`self`




<hr />


### ListUsersQuery::setPage  

**Description**

```php
public setPage (?int $page)
```

Set the page to get. 

 

**Parameters**

* `(?int) $page`
: the page to get  

**Return Values**

`self`




<hr />


### ListUsersQuery::setPageSize  

**Description**

```php
public setPageSize (?int $pageSize)
```

Set the maximum number of users to return. Cannot be greater than 1000. 

 

**Parameters**

* `(?int) $pageSize`
: the maximum number of users to return  

**Return Values**

`self`




<hr />


### ListUsersQuery::setSortField  

**Description**

```php
public setSortField (?string $sortField)
```

Set the field used to sort results. 

 

**Parameters**

* `(?string) $sortField`
: the field used to sort results  

**Return Values**

`self`




<hr />


### ListUsersQuery::setSortOrder  

**Description**

```php
public setSortOrder (?string $sortOrder)
```

Set the direction the results are sorted in. 

 

**Parameters**

* `(?string) $sortOrder`
: the direction the results are sorted in  

**Return Values**

`self`




<hr />


### ListUsersQuery::setTeams  

**Description**

```php
public setTeams (?array $)
```

Set the container for the teams the user is assigned to. 

 

**Parameters**

* `(?array) $`
: the teams the user is assigned to  

**Return Values**

`self`




<hr />


### ListUsersQuery::setUserStatus  

**Description**

```php
public setUserStatus (string $userStatus)
```

Set the status of the users to query for. 

 

**Parameters**

* `(string) $userStatus`
: the status of the users to query for  

**Return Values**

`self`




<hr />


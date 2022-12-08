# CBS\SmarterU\Queries\GetGroupQuery  

Represents a getGroup query or a getUserGroups query made to the SmarterU API.



## Extend:

CBS\SmarterU\Queries\BaseQuery

## Methods

| Name | Description |
|------|-------------|
|[getGroupId](#getgroupquerygetgroupid)|Get the user-specified identifier assigned to the group.|
|[getName](#getgroupquerygetname)|Return the unique name of the group to get.|
|[setGroupId](#getgroupquerysetgroupid)|Set the user-specified identifier assigned to the group|
|[setName](#getgroupquerysetname)|Set the unique name of the group to get.|

## Inherited methods

| Name | Description |
|------|-------------|
|createBaseXml|Create the base XML element containing the features that are common to
all queries.|
|getAccountApi|Return the account API key.|
|getUserApi|Return the user API key.|
|setAccountApi|Set the account API key.|
|setUserApi|Set the user API key.|



### GetGroupQuery::getGroupId  

**Description**

```php
public getGroupId (void)
```

Get the user-specified identifier assigned to the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The The user-specified identifier assigned to the group


<hr />


### GetGroupQuery::getName  

**Description**

```php
public getName (void)
```

Return the unique name of the group to get. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The unique name of the group to get if it exists


<hr />


### GetGroupQuery::setGroupId  

**Description**

```php
public setGroupId (string $groupId)
```

Set the user-specified identifier assigned to the group 

 

**Parameters**

* `(string) $groupId`
: The user-specified identifier assigned to the group  

**Return Values**

`self`




<hr />


### GetGroupQuery::setName  

**Description**

```php
public setName (string $name)
```

Set the unique name of the group to get. 

 

**Parameters**

* `(string) $name`
: The unique name of the group to get  

**Return Values**

`self`




<hr />


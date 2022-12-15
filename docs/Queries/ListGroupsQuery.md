# CBS\SmarterU\Queries\ListGroupsQuery  

Represents a listGroups query made to the SmarterU API.



## Extend:

CBS\SmarterU\Queries\BaseQuery

## Methods

| Name | Description |
|------|-------------|
|[getGroupName](#listgroupsquerygetgroupname)|Get the container for group name filters.|
|[getGroupStatus](#listgroupsquerygetgroupstatus)|Get the group status.|
|[getTags](#listgroupsquerygettags)|Get the container for group tag filters.|
|[setGroupName](#listgroupsquerysetgroupname)|Set the container for group name filters.|
|[setGroupStatus](#listgroupsquerysetgroupstatus)|Set the group status.|
|[setTags](#listgroupsquerysettags)|Set the container for group tag filters.|

## Inherited methods

| Name | Description |
|------|-------------|
|createBaseXml|Create the base XML element containing the features that are common to
all queries.|
|getAccountApi|Return the account API key.|
|getUserApi|Return the user API key.|
|setAccountApi|Set the account API key.|
|setUserApi|Set the user API key.|



### ListGroupsQuery::getGroupName  

**Description**

```php
public getGroupName (void)
```

Get the container for group name filters. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\MatchTag`

> The group name filters.


<hr />


### ListGroupsQuery::getGroupStatus  

**Description**

```php
public getGroupStatus (void)
```

Get the group status. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The group's status


<hr />


### ListGroupsQuery::getTags  

**Description**

```php
public getTags (void)
```

Get the container for group tag filters. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?array`

> the group tag filters


<hr />


### ListGroupsQuery::setGroupName  

**Description**

```php
public setGroupName (\MatchTag $groupName)
```

Set the container for group name filters. 

 

**Parameters**

* `(\MatchTag) $groupName`
: The group name filters  

**Return Values**

`self`




<hr />


### ListGroupsQuery::setGroupStatus  

**Description**

```php
public setGroupStatus (string $groupStatus)
```

Set the group status. 

 

**Parameters**

* `(string) $groupStatus`
: The group's status  

**Return Values**

`self`




<hr />


### ListGroupsQuery::setTags  

**Description**

```php
public setTags (array $tags)
```

Set the container for group tag filters. 

 

**Parameters**

* `(array) $tags`
: the group tag filters  

**Return Values**

`self`




<hr />


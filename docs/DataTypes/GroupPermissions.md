# CBS\SmarterU\DataTypes\GroupPermissions  

The GroupPermissions class represents a User's affiliation with a Group
and the permissions that User has within the Group.

Note: All GroupPermissions instances must contain the "permissions" array.
The GroupPermisisons instance used in User::$groups must contain either the
group name or group ID. The GroupPermissions instance used in Group::$users
must contain the home group, and either the email or employee ID.
Unnecessary attributes will be ignored.  





## Methods

| Name | Description |
|------|-------------|
|[getEmail](#grouppermissionsgetemail)|Get the email address of the user you want to assign to the group.|
|[getEmployeeId](#grouppermissionsgetemployeeid)|Get the employee ID of the user you want to assign to the group.|
|[getGroupId](#grouppermissionsgetgroupid)|Get the user-specified ID of the group the user is a member of.|
|[getGroupName](#grouppermissionsgetgroupname)|Get the name of the group the user is a member of.|
|[getHomeGroup](#grouppermissionsgethomegroup)|Get whether or not this group is the user's home group.|
|[getPermissions](#grouppermissionsgetpermissions)|Get the container for the permissions to be granted or denied to the
user within the group.|
|[setEmail](#grouppermissionssetemail)|Set the email address of the user you want to assign to the group.|
|[setEmployeeId](#grouppermissionssetemployeeid)|Set the employee ID of the user you want to assign to the group.|
|[setGroupId](#grouppermissionssetgroupid)|Set the user-specified ID of the group the user is a member of.|
|[setGroupName](#grouppermissionssetgroupname)|Set the name of the group the user is a member of.|
|[setHomeGroup](#grouppermissionssethomegroup)|Set whether or not this group is the user's home group.|
|[setPermissions](#grouppermissionssetpermissions)|Set the container for the permissions to be granted or denied to the
user within the group.|




### GroupPermissions::getEmail  

**Description**

```php
public getEmail (void)
```

Get the email address of the user you want to assign to the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the email address of the user assigned to the group


<hr />


### GroupPermissions::getEmployeeId  

**Description**

```php
public getEmployeeId (void)
```

Get the employee ID of the user you want to assign to the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the employee ID of the user assigned to the group


<hr />


### GroupPermissions::getGroupId  

**Description**

```php
public getGroupId (void)
```

Get the user-specified ID of the group the user is a member of. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?int`

> the ID of the group the user is a member of.


<hr />


### GroupPermissions::getGroupName  

**Description**

```php
public getGroupName (void)
```

Get the name of the group the user is a member of. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the name of the group the user is a member of


<hr />


### GroupPermissions::getHomeGroup  

**Description**

```php
public getHomeGroup (void)
```

Get whether or not this group is the user's home group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`bool`

> true if and only if this group is the user's home group


<hr />


### GroupPermissions::getPermissions  

**Description**

```php
public getPermissions (void)
```

Get the container for the permissions to be granted or denied to the
user within the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`array`

> the container for the permissions


<hr />


### GroupPermissions::setEmail  

**Description**

```php
public setEmail (string $email)
```

Set the email address of the user you want to assign to the group. 

 

**Parameters**

* `(string) $email`
: the email address of the user assigned to the group  

**Return Values**

`void`


<hr />


### GroupPermissions::setEmployeeId  

**Description**

```php
public setEmployeeId (string $employeeId)
```

Set the employee ID of the user you want to assign to the group. 

 

**Parameters**

* `(string) $employeeId`
: the employee ID of the user assigned to the group  

**Return Values**

`self`




<hr />


### GroupPermissions::setGroupId  

**Description**

```php
public setGroupId (int $groupId)
```

Set the user-specified ID of the group the user is a member of. 

 

**Parameters**

* `(int) $groupId`
: the id of the group the user is a member of  

**Return Values**

`self`




<hr />


### GroupPermissions::setGroupName  

**Description**

```php
public setGroupName (string $groupName)
```

Set the name of the group the user is a member of. 

 

**Parameters**

* `(string) $groupName`
: the name of the group the user is a member of  

**Return Values**

`self`




<hr />


### GroupPermissions::setHomeGroup  

**Description**

```php
public setHomeGroup (bool $homeGroup)
```

Set whether or not this group is the user's home group. 

 

**Parameters**

* `(bool) $homeGroup`
: true if and only if this group is the user's  
home group  

**Return Values**

`self`




<hr />


### GroupPermissions::setPermissions  

**Description**

```php
public setPermissions (array $permissions)
```

Set the container for the permissions to be granted or denied to the
user within the group. 

 

**Parameters**

* `(array) $permissions`
: the container for the permissions  

**Return Values**

`self`




<hr />


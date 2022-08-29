# CBS\SmarterU\DataTypes\Group  

Represents a SmarterU Group.

A group is a collection of users that can be collectively assigned to
training.  





## Methods

| Name | Description |
|------|-------------|
|[getCreatedDate](#groupgetcreateddate)|Returns the date and time when the group was created.|
|[getDashboardSetId](#groupgetdashboardsetid)|Get the identifier of the dashboard set that is assigned to the group.|
|[getDescription](#groupgetdescription)|Returns the group's description.|
|[getGroupId](#groupgetgroupid)|Returns the unique id of the group.|
|[getHomeGroupMessage](#groupgethomegroupmessage)|Returns the group's home message.|
|[getLearningModules](#groupgetlearningmodules)|Get the container for assigning Learning Modules to the group.|
|[getModifiedDate](#groupgetmodifieddate)|Returns the date and time the group was last modified.|
|[getName](#groupgetname)|Returns the name of the group.|
|[getNotificationEmails](#groupgetnotificationemails)|Returns the group's notification email addresses.|
|[getStatus](#groupgetstatus)|Returns the group's status.|
|[getSubscriptionVariants](#groupgetsubscriptionvariants)|Get the container for assigning subscription variants to the group.|
|[getTags](#groupgettags)|Returns the group's tags.|
|[getUserHelpEmail](#groupgetuserhelpemail)|Get the email addresses to which help requests will be sent. If no email
addresses are specified, the help requests will be sent to all
administrators.|
|[getUserHelpEnabled](#groupgetuserhelpenabled)|Get whether a link displays in the header of the learner interface that
enables users who have the group as their home group to request help.|
|[getUserHelpOverrideDefault](#groupgetuserhelpoverridedefault)|Get whether the Enable User Help setting is overriden by the group.|
|[getUserHelpText](#groupgetuserhelptext)|Get the text to display for the help link in the learner interface's
header.|
|[getUserLimitAmount](#groupgetuserlimitamount)|Get the maximum number of users that can be added to the group. Null if
there is no limit.|
|[getUserLimitEnabled](#groupgetuserlimitenabled)|Get whether or not the group has a user limit enabled.|
|[getUsers](#groupgetusers)|Get the container for assigning Users to the group.|
|[setCreatedDate](#groupsetcreateddate)|Sets the date and time when the group was created.|
|[setDashboardSetId](#groupsetdashboardsetid)|Set the identifier of the dashboard set that is assigned to the group.|
|[setDescription](#groupsetdescription)|Sets the group's description.|
|[setGroupId](#groupsetgroupid)|Sets the unique id of the group.|
|[setHomeGroupMessage](#groupsethomegroupmessage)|Sets the group's home message.|
|[setLearningModules](#groupsetlearningmodules)|Set the container for assigning Learning Modules to the group.|
|[setModifiedDate](#groupsetmodifieddate)|Sets the date and time the group was last modified.|
|[setName](#groupsetname)|Sets the name of the group.|
|[setNotificationEmails](#groupsetnotificationemails)|Sets the group's notification email addresses.|
|[setStatus](#groupsetstatus)|Sets the group's status.|
|[setSubscriptionVariants](#groupsetsubscriptionvariants)|Set the container for assigning subscription variants to the group.|
|[setTags](#groupsettags)|Sets the group's tags.|
|[setUserHelpEmail](#groupsetuserhelpemail)|Set the email addresses to which help requests will be sent. If no email
addresses are specified, the help requests will be sent to all
administrators.|
|[setUserHelpEnabled](#groupsetuserhelpenabled)|Set whether a link displays in the header of the learner interface that
enables users who have the group as their home group to request help.|
|[setUserHelpOverrideDefault](#groupsetuserhelpoverridedefault)|Set whether the Enable User Help setting is overriden by the group.|
|[setUserHelpText](#groupsetuserhelptext)|Set the text to display for the help link in the learner interface's
header.|
|[setUserLimitAmount](#groupsetuserlimitamount)|Set the maximum number of users that can be added to the group. Set this
to null to remove the limit.|
|[setUserLimitEnabled](#groupsetuserlimitenabled)|Set whether or not the group has a user limit enabled.|
|[setUsers](#groupsetusers)|Set the container for assigning Users to the group.|
|[toXML](#grouptoxml)|Return an XML representation of the Group to be passed into the SmarterU
API as a parameter for group-related queries.|




### Group::getCreatedDate  

**Description**

```php
public getCreatedDate (void)
```

Returns the date and time when the group was created. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\DateTimeInterface`

> the date and time when the group was created.


<hr />


### Group::getDashboardSetId  

**Description**

```php
public getDashboardSetId (void)
```

Get the identifier of the dashboard set that is assigned to the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the identifier of the dashboard set


<hr />


### Group::getDescription  

**Description**

```php
public getDescription (void)
```

Returns the group's description. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> the group's description.


<hr />


### Group::getGroupId  

**Description**

```php
public getGroupId (void)
```

Returns the unique id of the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the unique id of the group.


<hr />


### Group::getHomeGroupMessage  

**Description**

```php
public getHomeGroupMessage (void)
```

Returns the group's home message. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> the group's home message.


<hr />


### Group::getLearningModules  

**Description**

```php
public getLearningModules (void)
```

Get the container for assigning Learning Modules to the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`array`

> the container for Learning Modules


<hr />


### Group::getModifiedDate  

**Description**

```php
public getModifiedDate (void)
```

Returns the date and time the group was last modified. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\DateTimeInterface`

> the date and time the group was last modified.


<hr />


### Group::getName  

**Description**

```php
public getName (void)
```

Returns the name of the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> the name of the group.


<hr />


### Group::getNotificationEmails  

**Description**

```php
public getNotificationEmails (void)
```

Returns the group's notification email addresses. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string[]`

> the group's notification email addresses.


<hr />


### Group::getStatus  

**Description**

```php
public getStatus (void)
```

Returns the group's status. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> Returns the group's status.


<hr />


### Group::getSubscriptionVariants  

**Description**

```php
public getSubscriptionVariants (void)
```

Get the container for assigning subscription variants to the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`array`

> The container for subscription variants


<hr />


### Group::getTags  

**Description**

```php
public getTags (void)
```

Returns the group's tags. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\Tag[]`

> Returns the group's tags.


<hr />


### Group::getUserHelpEmail  

**Description**

```php
public getUserHelpEmail (void)
```

Get the email addresses to which help requests will be sent. If no email
addresses are specified, the help requests will be sent to all
administrators. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?array`

> the email addresses to which help requests will be sent


<hr />


### Group::getUserHelpEnabled  

**Description**

```php
public getUserHelpEnabled (void)
```

Get whether a link displays in the header of the learner interface that
enables users who have the group as their home group to request help. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?bool`

> true if and only if the link is displayed


<hr />


### Group::getUserHelpOverrideDefault  

**Description**

```php
public getUserHelpOverrideDefault (void)
```

Get whether the Enable User Help setting is overriden by the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?bool`

> true if and only if the Enable User Help setting is  
overriden by the group


<hr />


### Group::getUserHelpText  

**Description**

```php
public getUserHelpText (void)
```

Get the text to display for the help link in the learner interface's
header. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the text to display for the help link


<hr />


### Group::getUserLimitAmount  

**Description**

```php
public getUserLimitAmount (void)
```

Get the maximum number of users that can be added to the group. Null if
there is no limit. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?int`

> The maximum number of users that can be added to a group,  
or null if there is no limit.


<hr />


### Group::getUserLimitEnabled  

**Description**

```php
public getUserLimitEnabled (void)
```

Get whether or not the group has a user limit enabled. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?bool`

> true if and only if the group has a user limit enabled


<hr />


### Group::getUsers  

**Description**

```php
public getUsers (void)
```

Get the container for assigning Users to the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`array`

> the container for the Users


<hr />


### Group::setCreatedDate  

**Description**

```php
public setCreatedDate (\DateTimeImmutable $createdDate)
```

Sets the date and time when the group was created. 

 

**Parameters**

* `(\DateTimeImmutable) $createdDate`
: the date and time when the group was created.  

**Return Values**

`self`




<hr />


### Group::setDashboardSetId  

**Description**

```php
public setDashboardSetId (string $dashboardSetId)
```

Set the identifier of the dashboard set that is assigned to the group. 

 

**Parameters**

* `(string) $dashboardSetId`
: The identifier of the dashboard set  

**Return Values**

`self`




<hr />


### Group::setDescription  

**Description**

```php
public setDescription (string $description)
```

Sets the group's description. 

 

**Parameters**

* `(string) $description`
: the group's description.  

**Return Values**

`self`




<hr />


### Group::setGroupId  

**Description**

```php
public setGroupId (string $groupId)
```

Sets the unique id of the group. 

 

**Parameters**

* `(string) $groupId`
: the unique id of the group.  

**Return Values**

`self`




<hr />


### Group::setHomeGroupMessage  

**Description**

```php
public setHomeGroupMessage (string $homeGroupMessage)
```

Sets the group's home message. 

 

**Parameters**

* `(string) $homeGroupMessage`
: the group's home message.  

**Return Values**

`self`




<hr />


### Group::setLearningModules  

**Description**

```php
public setLearningModules (array $learningModules)
```

Set the container for assigning Learning Modules to the group. 

 

**Parameters**

* `(array) $learningModules`
: the container for LearningModules  

**Return Values**

`self`




<hr />


### Group::setModifiedDate  

**Description**

```php
public setModifiedDate (\DateTimeInterface $modifiedDate)
```

Sets the date and time the group was last modified. 

 

**Parameters**

* `(\DateTimeInterface) $modifiedDate`
: The date and time the group was last modified.  

**Return Values**

`self`




<hr />


### Group::setName  

**Description**

```php
public setName (string $name)
```

Sets the name of the group. 

 

**Parameters**

* `(string) $name`
: the name of the group.  

**Return Values**

`self`




<hr />


### Group::setNotificationEmails  

**Description**

```php
public setNotificationEmails (string[] $notificationEmails)
```

Sets the group's notification email addresses. 

All members of the array must be strings. If they are not, then an  
InvalidArgumentException is thrown. 

**Parameters**

* `(string[]) $notificationEmails`
: The Group's notification email addresses.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> if array members are not strings.

<hr />


### Group::setStatus  

**Description**

```php
public setStatus (string $status)
```

Sets the group's status. 

The status value must be one of STATUS_ACTIVE or STATUS_INACTIVE. If it  
is not a valid value, an InvalidArgumentException is thrown. 

**Parameters**

* `(string) $status`
: The group's status.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> if status is invalid.

<hr />


### Group::setSubscriptionVariants  

**Description**

```php
public setSubscriptionVariants (array $)
```

Set the container for assigning subscription variants to the group. 

 

**Parameters**

* `(array) $`
: The container for subscription variants  

**Return Values**

`self`




<hr />


### Group::setTags  

**Description**

```php
public setTags (\Tag[] $tags)
```

Sets the group's tags. 

 

**Parameters**

* `(\Tag[]) $tags`
: The group's tags.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> if any array members are not a Tag.

<hr />


### Group::setUserHelpEmail  

**Description**

```php
public setUserHelpEmail (array $userHelpEmail)
```

Set the email addresses to which help requests will be sent. If no email
addresses are specified, the help requests will be sent to all
administrators. 

 

**Parameters**

* `(array) $userHelpEmail`
: the email addresses  

**Return Values**

`self`




<hr />


### Group::setUserHelpEnabled  

**Description**

```php
public setUserHelpEnabled (bool $)
```

Set whether a link displays in the header of the learner interface that
enables users who have the group as their home group to request help. 

 

**Parameters**

* `(bool) $`
: userHelpEnabled true if and only if the link is displayed  

**Return Values**

`self`




<hr />


### Group::setUserHelpOverrideDefault  

**Description**

```php
public setUserHelpOverrideDefault (bool $userHelpOverrideDefault)
```

Set whether the Enable User Help setting is overriden by the group. 

 

**Parameters**

* `(bool) $userHelpOverrideDefault`
: true if and only if the Enable  
User Help setting is overriden by the group  

**Return Values**

`void`


<hr />


### Group::setUserHelpText  

**Description**

```php
public setUserHelpText (string $userHelpText)
```

Set the text to display for the help link in the learner interface's
header. 

 

**Parameters**

* `(string) $userHelpText`
: the text to display for the help link  

**Return Values**

`self`




<hr />


### Group::setUserLimitAmount  

**Description**

```php
public setUserLimitAmount (?int $userLimitAmount)
```

Set the maximum number of users that can be added to the group. Set this
to null to remove the limit. 

 

**Parameters**

* `(?int) $userLimitAmount`
: The maximum number of users that can be  
added to the group, or null if there is no maximum  

**Return Values**

`self`




<hr />


### Group::setUserLimitEnabled  

**Description**

```php
public setUserLimitEnabled (bool $userLimitEnabled)
```

Set whether or not the group has a user limit enabled. 

 

**Parameters**

* `(bool) $userLimitEnabled`
: true if and only if the group has a user  
limit enabled  

**Return Values**

`self`




<hr />


### Group::setUsers  

**Description**

```php
public setUsers (array $users)
```

Set the container for assigning Users to the group. 

 

**Parameters**

* `(array) $users`
: A container for assigning Users to the group.  
Each element must be an instance of CBS\SmarterU\DataTypes\  
GroupPermissions. GroupPermissions::$homeGroup, and either  
GroupPermissions::$email or GroupPermissions::$employeeId must be  
set.  

**Return Values**

`self`




<hr />


### Group::toXML  

**Description**

```php
public toXML (?string $accountApi, ?string $userApi, string $methodName)
```

Return an XML representation of the Group to be passed into the SmarterU
API as a parameter for group-related queries. 

 

**Parameters**

* `(?string) $accountApi`
: the account API key of the user making the request  
* `(?string) $userApi`
: the user API key of the user making the request  
* `(string) $methodName`
: the name of the method being called  

**Return Values**

`string`

> an XML representation of the group


<hr />


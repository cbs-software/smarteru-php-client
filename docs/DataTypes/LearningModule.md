# CBS\SmarterU\DataTypes\LearningModule  

A LearningModule represents a course that is assigned to the Users within
a Group.





## Methods

| Name | Description |
|------|-------------|
|[getAction](#learningmodulegetaction)|Get whether the course is to be assigned to or removed from the group.|
|[getAllowSelfEnroll](#learningmodulegetallowselfenroll)|Get whether or not users in the group are able to self-enroll in the course.|
|[getAutoEnroll](#learningmodulegetautoenroll)|Get whether or not users in the group will be automatically enrolled in the course.|
|[getId](#learningmodulegetid)|Get the system-generated identifier of the course to be assigned to the group.|
|[setAction](#learningmodulesetaction)|Set whether the course is to be assigned to or removed from the group.|
|[setAllowSelfEnroll](#learningmodulesetallowselfenroll)|Set whether or not users in the group are able to self-enroll in the course.|
|[setAutoEnroll](#learningmodulesetautoenroll)|Set whether or not users in the group will be automatically enrolled in the course.|
|[setId](#learningmodulesetid)|Set the system-generated identifier of the course to be assigned to the group.|




### LearningModule::getAction  

**Description**

```php
public getAction (void)
```

Get whether the course is to be assigned to or removed from the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> whether the course is to be assigned or removed


<hr />


### LearningModule::getAllowSelfEnroll  

**Description**

```php
public getAllowSelfEnroll (void)
```

Get whether or not users in the group are able to self-enroll in the course. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`bool`

> True if and only if users are able to self-enroll


<hr />


### LearningModule::getAutoEnroll  

**Description**

```php
public getAutoEnroll (void)
```

Get whether or not users in the group will be automatically enrolled in the course. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`bool`

> True if and only if users in the group will be  
automatically enrolled in the course.


<hr />


### LearningModule::getId  

**Description**

```php
public getId (void)
```

Get the system-generated identifier of the course to be assigned to the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> The system-generated identifier of the course


<hr />


### LearningModule::setAction  

**Description**

```php
public setAction (string $action)
```

Set whether the course is to be assigned to or removed from the group. 

 

**Parameters**

* `(string) $action`
: 'Add' to assign the course, 'Remove'  
to remove the course.  

**Return Values**

`self`




<hr />


### LearningModule::setAllowSelfEnroll  

**Description**

```php
public setAllowSelfEnroll (bool $allowSelfEnroll)
```

Set whether or not users in the group are able to self-enroll in the course. 

 

**Parameters**

* `(bool) $allowSelfEnroll`
: True if and only if users are able to  
self-enroll  

**Return Values**

`self`




<hr />


### LearningModule::setAutoEnroll  

**Description**

```php
public setAutoEnroll (bool $autoEnroll)
```

Set whether or not users in the group will be automatically enrolled in the course. 

 

**Parameters**

* `(bool) $autoEnroll`
: True if and only if users in the group will be  
automatically enrolled in the course.  

**Return Values**

`void`


<hr />


### LearningModule::setId  

**Description**

```php
public setId (string $id)
```

Set the system-generated identifier of the course to be assigned to the group. 

 

**Parameters**

* `(string) $id`
: The system-generated identifier of the course  

**Return Values**

`self`




<hr />


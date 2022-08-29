# CBS\SmarterU\DataTypes\LearningModule  

A LearningModule represents a course that is assigned to the Users within
a Group.





## Methods

| Name | Description |
|------|-------------|
|[getAllowSelfEnroll](#learningmodulegetallowselfenroll)|Get whether or not users in the group are able to self-enroll in the
course.|
|[getAutoEnroll](#learningmodulegetautoenroll)|Get whether or not users in the group will be automatically enrolled
in the course.|
|[getId](#learningmodulegetid)|Get the system-generated identifier of the course to be assigned to
the group.|
|[setAllowSelfEnroll](#learningmodulesetallowselfenroll)|Set whether or not users in the group are able to self-enroll in the
course.|
|[setAutoEnroll](#learningmodulesetautoenroll)|Set whether or not users in the group will be automatically enrolled
in the course.|
|[setId](#learningmodulesetid)|Set the system-generated identifier of the course to be assigned to
the group.|




### LearningModule::getAllowSelfEnroll  

**Description**

```php
public getAllowSelfEnroll (void)
```

Get whether or not users in the group are able to self-enroll in the
course. 

 

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

Get whether or not users in the group will be automatically enrolled
in the course. 

 

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

Get the system-generated identifier of the course to be assigned to
the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> The system-generated identifier of the course


<hr />


### LearningModule::setAllowSelfEnroll  

**Description**

```php
public setAllowSelfEnroll (bool $allowSelfEnroll)
```

Set whether or not users in the group are able to self-enroll in the
course. 

 

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

Set whether or not users in the group will be automatically enrolled
in the course. 

 

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

Set the system-generated identifier of the course to be assigned to
the group. 

 

**Parameters**

* `(string) $id`
: The system-generated identifier of the course  

**Return Values**

`self`




<hr />


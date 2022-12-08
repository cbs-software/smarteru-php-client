# CBS\SmarterU\DataTypes\LearnerReport  

Represents a LearnerReport within SmarterU.

A Learner Report, also known as an Enrollment Report, enables course
managers to view the progress of Users who have been assigned to the
course. Please refer to the SmarterU documentation for further information:
https://support.smarteru.com/v1/docs/enrollment-report  





## Methods

| Name | Description |
|------|-------------|
|[getAlternateEmail](#learnerreportgetalternateemail)|Get the User's alternate email address.|
|[getColumns](#learnerreportgetcolumns)|Get the columns specified by the SmarterU API.|
|[getCompletedDate](#learnerreportgetcompleteddate)|Get the UTC date the User completed the Course.|
|[getCourseDuration](#learnerreportgetcourseduration)|Get the duration of the course.|
|[getCourseName](#learnerreportgetcoursename)|Get the course's name.|
|[getCourseSessionId](#learnerreportgetcoursesessionid)|Get the course's session ID.|
|[getCreatedDate](#learnerreportgetcreateddate)|Get the UTC date the enrollment was created.|
|[getCustomFields](#learnerreportgetcustomfields)|Get the custom fields specified by the SmarterU API.|
|[getDivision](#learnerreportgetdivision)|Get the User's division.|
|[getDueDate](#learnerreportgetduedate)|Get the UTC date the Course is due.|
|[getEmployeeId](#learnerreportgetemployeeid)|Get the User's employee ID.|
|[getEnrolledDate](#learnerreportgetenrolleddate)|Get the UTC date the User was enrolled in the Course.|
|[getGivenName](#learnerreportgetgivenname)|Get the user's given name.|
|[getGrade](#learnerreportgetgrade)|Get the grade the User got in the Course.|
|[getGradePercentage](#learnerreportgetgradepercentage)|Get the User's grade expressed as a percentage.|
|[getGroupId](#learnerreportgetgroupid)|Get the ID of the Group containing this training assignment.|
|[getGroupName](#learnerreportgetgroupname)|Get the name of the Group containing this training assignment.|
|[getId](#learnerreportgetid)|Get the system-generated identifier for the user's course enrollment.|
|[getLastAccessedDate](#learnerreportgetlastaccesseddate)|Get the UTC date the User last accessed the Course.|
|[getLearningModuleId](#learnerreportgetlearningmoduleid)|Get the course's system-generated identifier.|
|[getModifiedDate](#learnerreportgetmodifieddate)|Get the UTC date the enrollment was last updated.|
|[getPoints](#learnerreportgetpoints)|Get the points scored by the User in the Course.|
|[getProgress](#learnerreportgetprogress)|Get the User's progress in the Course.|
|[getRoleId](#learnerreportgetroleid)|Get the role ID.|
|[getStartedDate](#learnerreportgetstarteddate)|Get the UTC date the User started the Course.|
|[getSubscriptionName](#learnerreportgetsubscriptionname)|Get the name of the subscription the Course is part of.|
|[getSurname](#learnerreportgetsurname)|Get the user's surname.|
|[getTitle](#learnerreportgettitle)|Get the User's title.|
|[getUserEmail](#learnerreportgetuseremail)|Get the User's email address.|
|[getUserId](#learnerreportgetuserid)|Get the user's system-generated identifier.|
|[getVariantEndDate](#learnerreportgetvariantenddate)|Get the UTC date the subscription variant ends.|
|[getVariantName](#learnerreportgetvariantname)|Get the name of the subscription variant.|
|[getVariantStartDate](#learnerreportgetvariantstartdate)|Get the UTC date the subscription variant started.|
|[setAlternateEmail](#learnerreportsetalternateemail)|Set the User's alternate email address.|
|[setColumns](#learnerreportsetcolumns)|Set the columns specified by the SmarterU API.|
|[setCompletedDate](#learnerreportsetcompleteddate)|Set the UTC date the User completed the Course.|
|[setCourseDuration](#learnerreportsetcourseduration)|Set the duration of the course.|
|[setCourseName](#learnerreportsetcoursename)|Set the course's name.|
|[setCourseSessionId](#learnerreportsetcoursesessionid)|Set the course's session ID.|
|[setCreatedDate](#learnerreportsetcreateddate)|Set the UTC date the enrollment was created.|
|[setCustomFields](#learnerreportsetcustomfields)|Set the custom fields specified by the SmarterU API.|
|[setDivision](#learnerreportsetdivision)|Set the User's division.|
|[setDueDate](#learnerreportsetduedate)|Set the UTC date the Course is due.|
|[setEmployeeId](#learnerreportsetemployeeid)|Set the User's employee ID.|
|[setEnrolledDate](#learnerreportsetenrolleddate)|Set the UTC date the User was enrolled in the Course.|
|[setGivenName](#learnerreportsetgivenname)|Set the user's given name.|
|[setGrade](#learnerreportsetgrade)|Set the grade the User got in the Course.|
|[setGradePercentage](#learnerreportsetgradepercentage)|Set the User's grade expressed as a percentage.|
|[setGroupId](#learnerreportsetgroupid)|Set the ID of the Group containing this training assignment.|
|[setGroupName](#learnerreportsetgroupname)|Set the name of the Group containing this training assignment.|
|[setId](#learnerreportsetid)|Set the system-generated identifier for the user's course enrollment.|
|[setLastAccessedDate](#learnerreportsetlastaccesseddate)|Set the UTC date the User last accessed the Course.|
|[setLearningModuleId](#learnerreportsetlearningmoduleid)|Set the course's system-generated identifier.|
|[setModifiedDate](#learnerreportsetmodifieddate)|Set the UTC date the enrollment was last updated.|
|[setPoints](#learnerreportsetpoints)|Set the points scored by the User in the Course.|
|[setProgress](#learnerreportsetprogress)|Set the User's progress in the Course.|
|[setRoleId](#learnerreportsetroleid)|Set the role ID.|
|[setStartedDate](#learnerreportsetstarteddate)|Set the UTC date the User started the Course.|
|[setSubscriptionName](#learnerreportsetsubscriptionname)|Set the name of the subscription the Course is part of.|
|[setSurname](#learnerreportsetsurname)|Set the user's surname.|
|[setTitle](#learnerreportsettitle)|Set the User's title.|
|[setUserEmail](#learnerreportsetuseremail)|Set the User's email address.|
|[setUserId](#learnerreportsetuserid)|Set the user's system-generated identifier.|
|[setVariantEndDate](#learnerreportsetvariantenddate)|Set the UTC date the subscription variant ends.|
|[setVariantName](#learnerreportsetvariantname)|Set the name of the subscription variant.|
|[setVariantStartDate](#learnerreportsetvariantstartdate)|Set the UTC date the subscription variant started.|




### LearnerReport::getAlternateEmail  

**Description**

```php
public getAlternateEmail (void)
```

Get the User's alternate email address. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The User's alternate email address.


<hr />


### LearnerReport::getColumns  

**Description**

```php
public getColumns (void)
```

Get the columns specified by the SmarterU API. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`array`

> The columns specified by the SmarterU API.


<hr />


### LearnerReport::getCompletedDate  

**Description**

```php
public getCompletedDate (void)
```

Get the UTC date the User completed the Course. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\DateTimeInterface`

> The UTC date the User completed the Course.


<hr />


### LearnerReport::getCourseDuration  

**Description**

```php
public getCourseDuration (void)
```

Get the duration of the course. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The duration of the course.


<hr />


### LearnerReport::getCourseName  

**Description**

```php
public getCourseName (void)
```

Get the course's name. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> The course's name.


<hr />


### LearnerReport::getCourseSessionId  

**Description**

```php
public getCourseSessionId (void)
```

Get the course's session ID. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The course's session ID.


<hr />


### LearnerReport::getCreatedDate  

**Description**

```php
public getCreatedDate (void)
```

Get the UTC date the enrollment was created. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\DateTimeInterface`

> The UTC date the enrollment was created.


<hr />


### LearnerReport::getCustomFields  

**Description**

```php
public getCustomFields (void)
```

Get the custom fields specified by the SmarterU API. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`array`

> The custom fields specified by the SmarterU API.


<hr />


### LearnerReport::getDivision  

**Description**

```php
public getDivision (void)
```

Get the User's division. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The User's division.


<hr />


### LearnerReport::getDueDate  

**Description**

```php
public getDueDate (void)
```

Get the UTC date the Course is due. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\DateTimeInterface`

> The UTC date the Course is due.


<hr />


### LearnerReport::getEmployeeId  

**Description**

```php
public getEmployeeId (void)
```

Get the User's employee ID. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The User's employee ID.


<hr />


### LearnerReport::getEnrolledDate  

**Description**

```php
public getEnrolledDate (void)
```

Get the UTC date the User was enrolled in the Course. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\DateTimeInterface`

> The UTC date the User was enrolled.


<hr />


### LearnerReport::getGivenName  

**Description**

```php
public getGivenName (void)
```

Get the user's given name. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> The user's given name.


<hr />


### LearnerReport::getGrade  

**Description**

```php
public getGrade (void)
```

Get the grade the User got in the Course. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The grade the User got in the Course


<hr />


### LearnerReport::getGradePercentage  

**Description**

```php
public getGradePercentage (void)
```

Get the User's grade expressed as a percentage. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?float`

> the User's grade expressed as a percentage.


<hr />


### LearnerReport::getGroupId  

**Description**

```php
public getGroupId (void)
```

Get the ID of the Group containing this training assignment. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The ID of the Group


<hr />


### LearnerReport::getGroupName  

**Description**

```php
public getGroupName (void)
```

Get the name of the Group containing this training assignment. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The name of the Group.


<hr />


### LearnerReport::getId  

**Description**

```php
public getId (void)
```

Get the system-generated identifier for the user's course enrollment. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> the system-generated identifier


<hr />


### LearnerReport::getLastAccessedDate  

**Description**

```php
public getLastAccessedDate (void)
```

Get the UTC date the User last accessed the Course. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\DateTimeInterface`

> The UTC date the User last accessed the Course.


<hr />


### LearnerReport::getLearningModuleId  

**Description**

```php
public getLearningModuleId (void)
```

Get the course's system-generated identifier. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> The course's system-generated identifier.


<hr />


### LearnerReport::getModifiedDate  

**Description**

```php
public getModifiedDate (void)
```

Get the UTC date the enrollment was last updated. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\DateTimeInterface`

> The UTC date the enrollment was last updated.


<hr />


### LearnerReport::getPoints  

**Description**

```php
public getPoints (void)
```

Get the points scored by the User in the Course. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?int`

> The points scored by the User in the Course.


<hr />


### LearnerReport::getProgress  

**Description**

```php
public getProgress (void)
```

Get the User's progress in the Course. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The User's progress in the Course.


<hr />


### LearnerReport::getRoleId  

**Description**

```php
public getRoleId (void)
```

Get the role ID. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The role ID.


<hr />


### LearnerReport::getStartedDate  

**Description**

```php
public getStartedDate (void)
```

Get the UTC date the User started the Course. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\DateTimeInterface`

> The UTC date the User started the Course.


<hr />


### LearnerReport::getSubscriptionName  

**Description**

```php
public getSubscriptionName (void)
```

Get the name of the subscription the Course is part of. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The name of the subscription.


<hr />


### LearnerReport::getSurname  

**Description**

```php
public getSurname (void)
```

Get the user's surname. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> The user's surname.


<hr />


### LearnerReport::getTitle  

**Description**

```php
public getTitle (void)
```

Get the User's title. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The User's title.


<hr />


### LearnerReport::getUserEmail  

**Description**

```php
public getUserEmail (void)
```

Get the User's email address. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The User's email address.


<hr />


### LearnerReport::getUserId  

**Description**

```php
public getUserId (void)
```

Get the user's system-generated identifier. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> The user's system-generated identifier.


<hr />


### LearnerReport::getVariantEndDate  

**Description**

```php
public getVariantEndDate (void)
```

Get the UTC date the subscription variant ends. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\DateTimeInterface`

> The UTC date the subscription variant ends.


<hr />


### LearnerReport::getVariantName  

**Description**

```php
public getVariantName (void)
```

Get the name of the subscription variant. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The name of the subscription variant.


<hr />


### LearnerReport::getVariantStartDate  

**Description**

```php
public getVariantStartDate (void)
```

Get the UTC date the subscription variant started. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\DateTimeInterface`

> The UTC date the subscription variant started.


<hr />


### LearnerReport::setAlternateEmail  

**Description**

```php
public setAlternateEmail (string $alternateEmail)
```

Set the User's alternate email address. 

 

**Parameters**

* `(string) $alternateEmail`
: The User's alternate email address.  

**Return Values**

`self`




<hr />


### LearnerReport::setColumns  

**Description**

```php
public setColumns (array $columns)
```

Set the columns specified by the SmarterU API. 

 

**Parameters**

* `(array) $columns`
: The columns specified by the SmarterU API.  

**Return Values**

`self`




<hr />


### LearnerReport::setCompletedDate  

**Description**

```php
public setCompletedDate (\DateTimeInterface $completedDate)
```

Set the UTC date the User completed the Course. 

 

**Parameters**

* `(\DateTimeInterface) $completedDate`
: The UTC date the User completed  
the Course.  

**Return Values**

`self`




<hr />


### LearnerReport::setCourseDuration  

**Description**

```php
public setCourseDuration (string $courseDuration)
```

Set the duration of the course. 

 

**Parameters**

* `(string) $courseDuration`
: The duration of the course.  

**Return Values**

`self`




<hr />


### LearnerReport::setCourseName  

**Description**

```php
public setCourseName (string $courseName)
```

Set the course's name. 

 

**Parameters**

* `(string) $courseName`
: The course's name.  

**Return Values**

`self`




<hr />


### LearnerReport::setCourseSessionId  

**Description**

```php
public setCourseSessionId (string $courseSessionId)
```

Set the course's session ID. 

 

**Parameters**

* `(string) $courseSessionId`
: The course's session ID.  

**Return Values**

`self`




<hr />


### LearnerReport::setCreatedDate  

**Description**

```php
public setCreatedDate (\DateTimeInterface $createdDate)
```

Set the UTC date the enrollment was created. 

 

**Parameters**

* `(\DateTimeInterface) $createdDate`
: The UTC date the enrollment was  
created.  

**Return Values**

`self`




<hr />


### LearnerReport::setCustomFields  

**Description**

```php
public setCustomFields (array $customFields)
```

Set the custom fields specified by the SmarterU API. 

 

**Parameters**

* `(array) $customFields`
: The custom fields specified by the SmarterU API.  

**Return Values**

`self`




<hr />


### LearnerReport::setDivision  

**Description**

```php
public setDivision (string $division)
```

Set the User's division. 

 

**Parameters**

* `(string) $division`
: The User's division.  

**Return Values**

`self`




<hr />


### LearnerReport::setDueDate  

**Description**

```php
public setDueDate (\DateTimeInterface $dueDate)
```

Set the UTC date the Course is due. 

 

**Parameters**

* `(\DateTimeInterface) $dueDate`
: The UTC date the Course is due.  

**Return Values**

`self`




<hr />


### LearnerReport::setEmployeeId  

**Description**

```php
public setEmployeeId (string $employeeId)
```

Set the User's employee ID. 

 

**Parameters**

* `(string) $employeeId`
: The User's employee ID.  

**Return Values**

`self`




<hr />


### LearnerReport::setEnrolledDate  

**Description**

```php
public setEnrolledDate (\DateTimeInterface $enrolledDate)
```

Set the UTC date the User was enrolled in the Course. 

 

**Parameters**

* `(\DateTimeInterface) $enrolledDate`
: The UTC date the User was enrolled.  

**Return Values**

`self`




<hr />


### LearnerReport::setGivenName  

**Description**

```php
public setGivenName (string $givenName)
```

Set the user's given name. 

 

**Parameters**

* `(string) $givenName`
: The user's given name.  

**Return Values**

`self`




<hr />


### LearnerReport::setGrade  

**Description**

```php
public setGrade (string $grade)
```

Set the grade the User got in the Course. 

 

**Parameters**

* `(string) $grade`
: The grade the User got in the Course  

**Return Values**

`self`




<hr />


### LearnerReport::setGradePercentage  

**Description**

```php
public setGradePercentage (float $gradePercentage)
```

Set the User's grade expressed as a percentage. 

 

**Parameters**

* `(float) $gradePercentage`
: The User's grade expressed as a percentage.  

**Return Values**

`self`




<hr />


### LearnerReport::setGroupId  

**Description**

```php
public setGroupId (string $groupId)
```

Set the ID of the Group containing this training assignment. 

 

**Parameters**

* `(string) $groupId`
: The ID of the Group  

**Return Values**

`self`




<hr />


### LearnerReport::setGroupName  

**Description**

```php
public setGroupName (string $groupName)
```

Set the name of the Group containing this training assignment. 

 

**Parameters**

* `(string) $groupName`
: The name of the Group.  

**Return Values**

`self`




<hr />


### LearnerReport::setId  

**Description**

```php
public setId (string $id)
```

Set the system-generated identifier for the user's course enrollment. 

 

**Parameters**

* `(string) $id`
: the system-generated identifier  

**Return Values**

`self`




<hr />


### LearnerReport::setLastAccessedDate  

**Description**

```php
public setLastAccessedDate (\DateTimeInterface $lastAccessedDate)
```

Set the UTC date the User last accessed the Course. 

 

**Parameters**

* `(\DateTimeInterface) $lastAccessedDate`
: The UTC date the User last  
accessed the Course.  

**Return Values**

`self`




<hr />


### LearnerReport::setLearningModuleId  

**Description**

```php
public setLearningModuleId (string $learningModuleId)
```

Set the course's system-generated identifier. 

 

**Parameters**

* `(string) $learningModuleId`
: The course's system-generated identifier.  

**Return Values**

`self`




<hr />


### LearnerReport::setModifiedDate  

**Description**

```php
public setModifiedDate (\DateTimeInterface $modifiedDate)
```

Set the UTC date the enrollment was last updated. 

 

**Parameters**

* `(\DateTimeInterface) $modifiedDate`
: The UTC date the enrollment was  
last updated.  

**Return Values**

`self`




<hr />


### LearnerReport::setPoints  

**Description**

```php
public setPoints (int $points)
```

Set the points scored by the User in the Course. 

 

**Parameters**

* `(int) $points`
: The points scored by the User in the Course.  

**Return Values**

`self`




<hr />


### LearnerReport::setProgress  

**Description**

```php
public setProgress (string $progress)
```

Set the User's progress in the Course. 

 

**Parameters**

* `(string) $progress`
: The User's progress in the Course.  

**Return Values**

`self`




<hr />


### LearnerReport::setRoleId  

**Description**

```php
public setRoleId (string $roleId)
```

Set the role ID. 

 

**Parameters**

* `(string) $roleId`
: The role ID.  

**Return Values**

`self`




<hr />


### LearnerReport::setStartedDate  

**Description**

```php
public setStartedDate (\DateTimeInterface $startedDate)
```

Set the UTC date the User started the Course. 

 

**Parameters**

* `(\DateTimeInterface) $startedDate`
: The UTC date the User started the Course.  

**Return Values**

`self`




<hr />


### LearnerReport::setSubscriptionName  

**Description**

```php
public setSubscriptionName (string $subscriptionName)
```

Set the name of the subscription the Course is part of. 

 

**Parameters**

* `(string) $subscriptionName`
: The name of the subscription.  

**Return Values**

`self`




<hr />


### LearnerReport::setSurname  

**Description**

```php
public setSurname (string $surname)
```

Set the user's surname. 

 

**Parameters**

* `(string) $surname`
: The user's surname.  

**Return Values**

`self`




<hr />


### LearnerReport::setTitle  

**Description**

```php
public setTitle (string $title)
```

Set the User's title. 

 

**Parameters**

* `(string) $title`
: The User's title.  

**Return Values**

`self`




<hr />


### LearnerReport::setUserEmail  

**Description**

```php
public setUserEmail (string $userEmail)
```

Set the User's email address. 

 

**Parameters**

* `(string) $userEmail`
: The User's email address.  

**Return Values**

`self`




<hr />


### LearnerReport::setUserId  

**Description**

```php
public setUserId (string $userId)
```

Set the user's system-generated identifier. 

 

**Parameters**

* `(string) $userId`
: The user's system-generated identifier.  

**Return Values**

`self`




<hr />


### LearnerReport::setVariantEndDate  

**Description**

```php
public setVariantEndDate (\DateTimeInterface $variantEndDate)
```

Set the UTC date the subscription variant ends. 

 

**Parameters**

* `(\DateTimeInterface) $variantEndDate`
: THe UTC date the subscription  
variant ends.  

**Return Values**

`self`




<hr />


### LearnerReport::setVariantName  

**Description**

```php
public setVariantName (string $variantName)
```

Set the name of the subscription variant. 

 

**Parameters**

* `(string) $variantName`
: The name of the subscription variant.  

**Return Values**

`self`




<hr />


### LearnerReport::setVariantStartDate  

**Description**

```php
public setVariantStartDate (\DateTimeInterface $variantStartDate)
```

Set the UTC date the subscription variant started. 

 

**Parameters**

* `(\DateTimeInterface) $variantStartDate`
: THe UTC date the subscription  
variant started.  

**Return Values**

`self`




<hr />


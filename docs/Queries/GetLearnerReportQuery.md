# CBS\SmarterU\Queries\GetLearnerReportQuery  

Represents a GetLearnerReport query made to the SmarterU API.





## Methods

| Name | Description |
|------|-------------|
|[getColumns](#getlearnerreportquerygetcolumns)|Get the array of additional columns to include in the report.|
|[getCompletedDates](#getlearnerreportquerygetcompleteddates)|Get the completion date range to include in the response.|
|[getCreatedDate](#getlearnerreportquerygetcreateddate)|Get the date range during which the enrollment was created.|
|[getCustomFields](#getlearnerreportquerygetcustomfields)|Get the array of custom user fields to be included in the report.|
|[getDueDates](#getlearnerreportquerygetduedates)|Get the due date range to include in the response.|
|[getEnrolledDates](#getlearnerreportquerygetenrolleddates)|Get the enrolled date range to include in the response.|
|[getEnrollmentId](#getlearnerreportquerygetenrollmentid)|Get the system-generated identifier for the user's course enrollment.|
|[getEnrollmentStatuses](#getlearnerreportquerygetenrollmentstatuses)|Get the enrollment statuses of courses to return.|
|[getGracePeriodDates](#getlearnerreportquerygetgraceperioddates)|Get the grace period date range to include in the response.|
|[getGroupNames](#getlearnerreportquerygetgroupnames)|Get the list of Group names. If this array is not empty, the report will only return courses from the specified Group(s).|
|[getGroupStatus](#getlearnerreportquerygetgroupstatus)|Get the status of Groups to return.|
|[getGroupTags](#getlearnerreportquerygetgrouptags)|Get the list of Group Tags. If this array is not empty, the report will only return courses from Groups that have the specified Tags.|
|[getLastAccessedDates](#getlearnerreportquerygetlastaccesseddates)|Get the last accessed date range to include in the response.|
|[getLearningModuleNames](#getlearnerreportquerygetlearningmodulenames)|Get the names of courses to return.|
|[getLearningModuleStatus](#getlearnerreportquerygetlearningmodulestatus)|Get the status of courses to return.|
|[getModifiedDate](#getlearnerreportquerygetmodifieddate)|Get the date range during which the enrollment was last modified.|
|[getPage](#getlearnerreportquerygetpage)|Get the page number to return.|
|[getPageSize](#getlearnerreportquerygetpagesize)|Get the maximum number of records to return.|
|[getStartedDates](#getlearnerreportquerygetstarteddates)|Get the started date range to include in the response.|
|[getUserEmailAddresses](#getlearnerreportquerygetuseremailaddresses)|Get the array of email addresses identifying the specific Users to include in the report.|
|[getUserEmployeeIds](#getlearnerreportquerygetuseremployeeids)|Get the array of employee IDs identifying the specific Users to include in the report.|
|[getUserStatus](#getlearnerreportquerygetuserstatus)|Get the User's status.|
|[setColumns](#getlearnerreportquerysetcolumns)|Set the array of additional columns to include in the report.|
|[setCompletedDates](#getlearnerreportquerysetcompleteddates)|Set the completion date range to include in the response.|
|[setCreatedDate](#getlearnerreportquerysetcreateddate)|Set the date range during which the enrollment was created.|
|[setCustomFields](#getlearnerreportquerysetcustomfields)|Set the array of custom user fields to be included in the report.|
|[setDueDates](#getlearnerreportquerysetduedates)|Set the due date range to include in the response.|
|[setEnrolledDates](#getlearnerreportquerysetenrolleddates)|Set the enrolled date range to include in the response.|
|[setEnrollmentId](#getlearnerreportquerysetenrollmentid)|Set the system-generated identifier for the user's course enrollment.|
|[setEnrollmentStatuses](#getlearnerreportquerysetenrollmentstatuses)|Set the enrollment statuses of courses to return. All values in the array must be contained in the "VALID_ENROLLMENT_STATUSES" array defined above.|
|[setGracePeriodDates](#getlearnerreportquerysetgraceperioddates)|Set the grace period date range to include in the response.|
|[setGroupNames](#getlearnerreportquerysetgroupnames)|Set the list of Group names. If this array is not empty, the report will only return courses from the specified Groups. Mutually exclusive with the Group status.|
|[setGroupStatus](#getlearnerreportquerysetgroupstatus)|Set the status of Groups to return. May only be set to 'Active', 'Inactive', or 'All'. Mutually exclusive with the Group names.|
|[setGroupTags](#getlearnerreportquerysetgrouptags)|Set the list of Group Tags. If this array is not empty, the report will only return courses from Groups that have the specified Tags. Every value passed in must be an instance of CBS\SmarterU\DataTypes\Tag.|
|[setLastAccessedDates](#getlearnerreportquerysetlastaccesseddates)|Set the last accessed date range to include in the response.|
|[setLearningModuleNames](#getlearnerreportquerysetlearningmodulenames)|Set the names of courses to return.|
|[setLearningModuleStatus](#getlearnerreportquerysetlearningmodulestatus)|Set the status of courses to return. Acceptable values are "Active", "Inactive", or "Archived". Any other value will result in an exception.|
|[setModifiedDate](#getlearnerreportquerysetmodifieddate)|Set the date range during which the enrollment was last modified.|
|[setPage](#getlearnerreportquerysetpage)|Set the page number to return.|
|[setPageSize](#getlearnerreportquerysetpagesize)|Set the maximum number of records to return. May not be set to more than 1000. If the value passed in is greater than 1000, $pageSize will be set to 1000 instead of the provided value.|
|[setStartedDates](#getlearnerreportquerysetstarteddates)|Set the started date range to include in the response.|
|[setUserEmailAddresses](#getlearnerreportquerysetuseremailaddresses)|Set the array of email addresses identifying the specific Users to include in the report.|
|[setUserEmployeeIds](#getlearnerreportquerysetuseremployeeids)|Set the array of employee IDs identifying the specific Users to include in the report.|
|[setUserStatus](#getlearnerreportquerysetuserstatus)|Set the User's status.|




### GetLearnerReportQuery::getColumns  

**Description**

```php
public getColumns (void)
```

Get the array of additional columns to include in the report. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string[]`

> The array of additional columns.


<hr />


### GetLearnerReportQuery::getCompletedDates  

**Description**

```php
public getCompletedDates (void)
```

Get the completion date range to include in the response. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\DateRangeTag[]`

> The completion date range.


<hr />


### GetLearnerReportQuery::getCreatedDate  

**Description**

```php
public getCreatedDate (void)
```

Get the date range during which the enrollment was created. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\DateRangeTag`

> The date range during which the enrollment was  
created.


<hr />


### GetLearnerReportQuery::getCustomFields  

**Description**

```php
public getCustomFields (void)
```

Get the array of custom user fields to be included in the report. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\CustomField[]`

> The array of custom user fields.


<hr />


### GetLearnerReportQuery::getDueDates  

**Description**

```php
public getDueDates (void)
```

Get the due date range to include in the response. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\DateRangeTag[]`

> The due date range.


<hr />


### GetLearnerReportQuery::getEnrolledDates  

**Description**

```php
public getEnrolledDates (void)
```

Get the enrolled date range to include in the response. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\DateRangeTag[]`

> The enrolled date range.


<hr />


### GetLearnerReportQuery::getEnrollmentId  

**Description**

```php
public getEnrollmentId (void)
```

Get the system-generated identifier for the user's course enrollment. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> The system-generated identifier.


<hr />


### GetLearnerReportQuery::getEnrollmentStatuses  

**Description**

```php
public getEnrollmentStatuses (void)
```

Get the enrollment statuses of courses to return. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string[]`

> The enrollment statuses of courses to return.


<hr />


### GetLearnerReportQuery::getGracePeriodDates  

**Description**

```php
public getGracePeriodDates (void)
```

Get the grace period date range to include in the response. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\DateRangeTag[]`

> The grace period date range.


<hr />


### GetLearnerReportQuery::getGroupNames  

**Description**

```php
public getGroupNames (void)
```

Get the list of Group names. If this array is not empty, the report will only return courses from the specified Group(s). 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string[]`

> The list of Group names.


<hr />


### GetLearnerReportQuery::getGroupStatus  

**Description**

```php
public getGroupStatus (void)
```

Get the status of Groups to return. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The status of Groups to return.


<hr />


### GetLearnerReportQuery::getGroupTags  

**Description**

```php
public getGroupTags (void)
```

Get the list of Group Tags. If this array is not empty, the report will only return courses from Groups that have the specified Tags. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\Tag[]`

> The list of Group Tags.


<hr />


### GetLearnerReportQuery::getLastAccessedDates  

**Description**

```php
public getLastAccessedDates (void)
```

Get the last accessed date range to include in the response. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\DateRangeTag[]`

> The last accessed date range.


<hr />


### GetLearnerReportQuery::getLearningModuleNames  

**Description**

```php
public getLearningModuleNames (void)
```

Get the names of courses to return. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string[]`

> The names of courses to return.


<hr />


### GetLearnerReportQuery::getLearningModuleStatus  

**Description**

```php
public getLearningModuleStatus (void)
```

Get the status of courses to return. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The status of courses to return.


<hr />


### GetLearnerReportQuery::getModifiedDate  

**Description**

```php
public getModifiedDate (void)
```

Get the date range during which the enrollment was last modified. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\DateRangeTag`

> The date range during which the enrollment was  
last modified.


<hr />


### GetLearnerReportQuery::getPage  

**Description**

```php
public getPage (void)
```

Get the page number to return. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`int`

> The page number to return.


<hr />


### GetLearnerReportQuery::getPageSize  

**Description**

```php
public getPageSize (void)
```

Get the maximum number of records to return. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`int`

> The maximum number of records to return.


<hr />


### GetLearnerReportQuery::getStartedDates  

**Description**

```php
public getStartedDates (void)
```

Get the started date range to include in the response. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\DateRangeTag[]`

> The started date range.


<hr />


### GetLearnerReportQuery::getUserEmailAddresses  

**Description**

```php
public getUserEmailAddresses (void)
```

Get the array of email addresses identifying the specific Users to include in the report. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string[]`

> The array of email addresses.


<hr />


### GetLearnerReportQuery::getUserEmployeeIds  

**Description**

```php
public getUserEmployeeIds (void)
```

Get the array of employee IDs identifying the specific Users to include in the report. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string[]`

> The array of employee IDs.


<hr />


### GetLearnerReportQuery::getUserStatus  

**Description**

```php
public getUserStatus (void)
```

Get the User's status. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> The User's status.


<hr />


### GetLearnerReportQuery::setColumns  

**Description**

```php
public setColumns (string[] $columns)
```

Set the array of additional columns to include in the report. 

 

**Parameters**

* `(string[]) $columns`
: The array of additional columns.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If one of the provided columns is not  
one of the columns accepted by the SmarterU API.

<hr />


### GetLearnerReportQuery::setCompletedDates  

**Description**

```php
public setCompletedDates (\DateRangeTag[] $completedDates)
```

Set the completion date range to include in the response. 

 

**Parameters**

* `(\DateRangeTag[]) $completedDates`
: The completion date range.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If $completedDates includes a  
value that is not an instance of  
CBS\SmarterU\Queries\Tags\DateRangeTag.

<hr />


### GetLearnerReportQuery::setCreatedDate  

**Description**

```php
public setCreatedDate (\DateRangeTag $createdDate)
```

Set the date range during which the enrollment was created. 

 

**Parameters**

* `(\DateRangeTag) $createdDate`
: The date range during which the  
enrollment was created.  

**Return Values**

`self`




<hr />


### GetLearnerReportQuery::setCustomFields  

**Description**

```php
public setCustomFields (\CustomField[] $customFields)
```

Set the array of custom user fields to be included in the report. 

 

**Parameters**

* `(\CustomField[]) $customFields`
: The array of custom user fields.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If $customFields contains a value that  
is not an instance of CBS\SmarterU\DataTypes\CustomField.

<hr />


### GetLearnerReportQuery::setDueDates  

**Description**

```php
public setDueDates (\DateRangeTag[] $dueDates)
```

Set the due date range to include in the response. 

 

**Parameters**

* `(\DateRangeTag[]) $dueDates`
: The due date range.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If $dueDates includes a value that  
is not an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.

<hr />


### GetLearnerReportQuery::setEnrolledDates  

**Description**

```php
public setEnrolledDates (\DateRangeTag[] $enrolledDates)
```

Set the enrolled date range to include in the response. 

 

**Parameters**

* `(\DateRangeTag[]) $enrolledDates`
: The enrolled date range.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If $enrolledDates includes a value that  
is not an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.

<hr />


### GetLearnerReportQuery::setEnrollmentId  

**Description**

```php
public setEnrollmentId (string $enrollmentId)
```

Set the system-generated identifier for the user's course enrollment. 

 

**Parameters**

* `(string) $enrollmentId`
: The system-generated identifier.  

**Return Values**

`self`




<hr />


### GetLearnerReportQuery::setEnrollmentStatuses  

**Description**

```php
public setEnrollmentStatuses (string[] $enrollmentStatuses)
```

Set the enrollment statuses of courses to return. All values in the array must be contained in the "VALID_ENROLLMENT_STATUSES" array defined above. 

 

**Parameters**

* `(string[]) $enrollmentStatuses`
: The enrollment statuses of courses  
to return.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If any of the values passed in are  
not strings, or are not one of the valid statuses.

<hr />


### GetLearnerReportQuery::setGracePeriodDates  

**Description**

```php
public setGracePeriodDates (\DateRangeTag[] $gracePeriodDates)
```

Set the grace period date range to include in the response. 

 

**Parameters**

* `(\DateRangeTag[]) $gracePeriodDates`
: The grace period date range.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If $gracePeriodDates includes a value that  
is not an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.

<hr />


### GetLearnerReportQuery::setGroupNames  

**Description**

```php
public setGroupNames (string[] $groupNames)
```

Set the list of Group names. If this array is not empty, the report will only return courses from the specified Groups. Mutually exclusive with the Group status. 

 

**Parameters**

* `(string[]) $groupNames`
: The list of Group names.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If one of the provided Group names is  
not a string.

<hr />


### GetLearnerReportQuery::setGroupStatus  

**Description**

```php
public setGroupStatus (string $groupStatus)
```

Set the status of Groups to return. May only be set to 'Active', 'Inactive', or 'All'. Mutually exclusive with the Group names. 

 

**Parameters**

* `(string) $groupStatus`
: The status of Groups to return.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If "$groupStatus" is not one of the  
values accepted by the SmarterU API.

<hr />


### GetLearnerReportQuery::setGroupTags  

**Description**

```php
public setGroupTags (\Tag[] $groupTags)
```

Set the list of Group Tags. If this array is not empty, the report will only return courses from Groups that have the specified Tags. Every value passed in must be an instance of CBS\SmarterU\DataTypes\Tag. 

 

**Parameters**

* `(\Tag[]) $groupTags`
: The list of Group Tags.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If one of the provided Tags is not  
an instance of CBS\SmarterU\DataTypes\Tag.

<hr />


### GetLearnerReportQuery::setLastAccessedDates  

**Description**

```php
public setLastAccessedDates (\DateRangeTag[] $lastAccessedDates)
```

Set the last accessed date range to include in the response. 

 

**Parameters**

* `(\DateRangeTag[]) $lastAccessedDates`
: The last accessed date range.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If $lastAccessedDates includes a value that  
is not an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.

<hr />


### GetLearnerReportQuery::setLearningModuleNames  

**Description**

```php
public setLearningModuleNames (string[] $learningModuleNames)
```

Set the names of courses to return. 

 

**Parameters**

* `(string[]) $learningModuleNames`
: The names of the courses to return.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If one of the names is not a string.

<hr />


### GetLearnerReportQuery::setLearningModuleStatus  

**Description**

```php
public setLearningModuleStatus (string $learningModuleStatus)
```

Set the status of courses to return. Acceptable values are "Active", "Inactive", or "Archived". Any other value will result in an exception. 

 

**Parameters**

* `(string) $learningModuleStatus`
: The status of courses to return.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If the status is not one of the valid  
statuses recognized by the SmarterU API.

<hr />


### GetLearnerReportQuery::setModifiedDate  

**Description**

```php
public setModifiedDate (\DateRangeTag $modifiedDate)
```

Set the date range during which the enrollment was last modified. 

 

**Parameters**

* `(\DateRangeTag) $modifiedDate`
: The date range during which the  
enrollment was last modified.  

**Return Values**

`self`




<hr />


### GetLearnerReportQuery::setPage  

**Description**

```php
public setPage (int $page)
```

Set the page number to return. 

 

**Parameters**

* `(int) $page`
: The page number to return.  

**Return Values**

`self`




<hr />


### GetLearnerReportQuery::setPageSize  

**Description**

```php
public setPageSize (int $pageSize)
```

Set the maximum number of records to return. May not be set to more than 1000. If the value passed in is greater than 1000, $pageSize will be set to 1000 instead of the provided value. 

 

**Parameters**

* `(int) $pageSize`
: The maximum number of records to return.  

**Return Values**

`self`




<hr />


### GetLearnerReportQuery::setStartedDates  

**Description**

```php
public setStartedDates (\DateRangeTag[] $startedDates)
```

Set the started date range to include in the response. 

 

**Parameters**

* `(\DateRangeTag[]) $startedDates`
: The due date range.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If $startedDates includes a value that  
is not an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.

<hr />


### GetLearnerReportQuery::setUserEmailAddresses  

**Description**

```php
public setUserEmailAddresses (string[] $userEmailAddresses)
```

Set the array of email addresses identifying the specific Users to include in the report. 

 

**Parameters**

* `(string[]) $userEmailAddresses`
: The array of email addresses.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If $userEmailAddresses contains a value  
that is not a string.

<hr />


### GetLearnerReportQuery::setUserEmployeeIds  

**Description**

```php
public setUserEmployeeIds (string[] $userEmployeeIds)
```

Set the array of employee IDs identifying the specific Users to include in the report. 

 

**Parameters**

* `(string[]) $userEmployeeIds`
: The array of employee IDs.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If "$userEmployeeIds" contains a value  
that is not a string.

<hr />


### GetLearnerReportQuery::setUserStatus  

**Description**

```php
public setUserStatus (string $userStatus)
```

Set the User's status. 

 

**Parameters**

* `(string) $userStatus`
: The User's status.  

**Return Values**

`self`




**Throws Exceptions**


`\InvalidArgumentException`
> If the provided value is not one of  
the values accepted by the SmarterU API.

<hr />


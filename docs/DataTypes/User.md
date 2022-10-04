# CBS\SmarterU\DataTypes\User  

A User in SmarterU.





## Methods

| Name | Description |
|------|-------------|
|[getAddress1](#usergetaddress1)|Get the first line of the user's address.|
|[getAddress2](#usergetaddress2)|Get the second line of the user's address.|
|[getAllowFeedback](#usergetallowfeedback)|Get whether or not the user can provide feedback on online courses.|
|[getAlternateEmail](#usergetalternateemail)|Returns the User's alternate email address.|
|[getAuthenticationType](#usergetauthenticationtype)|Returns the User's authentication type.|
|[getCity](#usergetcity)|Get the city of the user's address.|
|[getCountry](#usergetcountry)|Get the country of the user's address.|
|[getCreatedDate](#usergetcreateddate)|Get the date the User was created.|
|[getCustomFields](#usergetcustomfields)|Get the container for the custom fields in your account.|
|[getDivision](#usergetdivision)|Get the user's division.|
|[getEmail](#usergetemail)|Get the email address for the user|
|[getEmployeeId](#usergetemployeeid)|Get provided for the Email tag.|
|[getFax](#usergetfax)|Get the user's fax number.|
|[getGivenName](#usergetgivenname)|Get the given name of the user, also known as their first name.|
|[getGroups](#usergetgroups)|Get the container representing the Groups the User is a member of.|
|[getHomeGroup](#usergethomegroup)|Get the name of the user's home group.|
|[getId](#usergetid)|Gets the user's ID.|
|[getLanguage](#usergetlanguage)|Get the language the user's account uses.|
|[getLearnerNotifications](#usergetlearnernotifications)|Get pending or outstanding courses in SmarterU.|
|[getModifiedDate](#usergetmodifieddate)|Get the date the User was last modified.|
|[getOldEmail](#usergetoldemail)|Get the old email address of the user.|
|[getOldEmployeeId](#usergetoldemployeeid)|Get the old employee ID of the user.|
|[getOrganization](#usergetorganization)|Get the name of the organization to assign to the user.|
|[getPassword](#usergetpassword)|Returns the password to assign to the user.|
|[getPhoneAlternate](#usergetphonealternate)|Get the user's alternate phone number.|
|[getPhoneMobile](#usergetphonemobile)|Get the user's mobile phone number.|
|[getPhonePrimary](#usergetphoneprimary)|Get the user's primary phone number.|
|[getPostalCode](#usergetpostalcode)|Get the user's postal code.|
|[getProvince](#usergetprovince)|Get the province or state of the user's address.|
|[getReceiveNotifications](#usergetreceivenotifications)|Get whether or not the user will receive email notifications.|
|[getRoles](#usergetroles)|Get the container for the learning plans assigned to the user.|
|[getSendEmailTo](#usergetsendemailto)|Returns where the user's email should be sent (Supervisor, Self, or Alternate).|
|[getSendMailTo](#usergetsendmailto)|Get the location where the user's physical mail should be sent.|
|[getStatus](#usergetstatus)|Return the status of the user.|
|[getSupervisorNotifications](#usergetsupervisornotifications)|Get of any users they are responsible for.|
|[getSupervisors](#usergetsupervisors)|Get the container for the user's supervisors.|
|[getSurname](#usergetsurname)|Returns the surname of the user, also known as their last name.|
|[getTeams](#usergetteams)|Get the container for the teams to assign to the user.|
|[getTimezone](#usergettimezone)|Returns the user's timezone.|
|[getTitle](#usergettitle)|Get the user's title.|
|[getVenues](#usergetvenues)|Get the container for assigning venues to the user.|
|[getWages](#usergetwages)|Get the container for adding the user's wages.|
|[getWebsite](#usergetwebsite)|Get the user's website.|
|[setAddress1](#usersetaddress1)|Set the first line of the user's address.|
|[setAddress2](#usersetaddress2)|Set the second line of the user's address.|
|[setAllowFeedback](#usersetallowfeedback)|Set whether or not the user can provide feedback on online courses.|
|[setAlternateEmail](#usersetalternateemail)|Sets the user's alternate email address.|
|[setAuthenticationType](#usersetauthenticationtype)|Sets the user's authentication type.|
|[setCity](#usersetcity)|Set the city of the user's address.|
|[setCountry](#usersetcountry)|Set the country of the user's address. Acceptable values are "Canada", "United States", or "International".|
|[setCreatedDate](#usersetcreateddate)|Set the date the User was created.|
|[setCustomFields](#usersetcustomfields)|Set the container for the custom fields in your account. The custom fields must already exist within your SmarterU account.|
|[setDivision](#usersetdivision)|Set the user's division.|
|[setEmail](#usersetemail)|Set the email address for the user|
|[setEmployeeId](#usersetemployeeid)|Set provided for the employee ID tag.|
|[setFax](#usersetfax)|Set the user's fax number.|
|[setGivenName](#usersetgivenname)|Set the given name of the user, also known as their first name.|
|[setGroups](#usersetgroups)|Set the container representing the Groups the User is a member of.|
|[setHomeGroup](#usersethomegroup)|Set the name of the user's home group.|
|[setId](#usersetid)|Sets the user's ID.|
|[setLanguage](#usersetlanguage)|Set the language the user's account uses.|
|[setLearnerNotifications](#usersetlearnernotifications)|Set pending or outstanding courses in SmarterU.|
|[setModifiedDate](#usersetmodifieddate)|Set the date the User was last modified.|
|[setOldEmail](#usersetoldemail)|Set the old email address of the user.|
|[setOldEmployeeId](#usersetoldemployeeid)|Set the old employee ID of the user.|
|[setOrganization](#usersetorganization)|Set the name of the organization to assign to the user. The organization must already exist within your SmarterU account.|
|[setPassword](#usersetpassword)|Sets the password to assign to the user|
|[setPhoneAlternate](#usersetphonealternate)|Set the user's alternate phone number.|
|[setPhoneMobile](#usersetphonemobile)|Set the user's mobile phone number.|
|[setPhonePrimary](#usersetphoneprimary)|Set the user's primary phone number.|
|[setPostalCode](#usersetpostalcode)|Set the user's postal code.|
|[setProvince](#usersetprovince)|Set the province or state of the user's address.|
|[setReceiveNotifications](#usersetreceivenotifications)|Set whether or not the user will receive email notifications.|
|[setRoles](#usersetroles)|Set the container for the learning plans assigned to the user. The learning plans must already exist within your SmarterU account.|
|[setSendEmailTo](#usersetsendemailto)|Sets where the user's email should be sent (Supervisor, Self, or Alternate).|
|[setSendMailTo](#usersetsendmailto)|Set the location where the user's physical mail should be sent.|
|[setStatus](#usersetstatus)|Set the status of the user.|
|[setSupervisorNotifications](#usersetsupervisornotifications)|Set of any users they are responsible for.|
|[setSupervisors](#usersetsupervisors)|Set the container for the user's supervisors. Each supervisor must already exist in SmarterU.|
|[setSurname](#usersetsurname)|Set the surname of the user, also known as their last name.|
|[setTeams](#usersetteams)|Set the container for the teams to assign to the user. The team names provided must already exist in your SmarterU account.|
|[setTimezone](#usersettimezone)|Sets the user's timezone.|
|[setTitle](#usersettitle)|Set the user's title.|
|[setVenues](#usersetvenues)|Set the container for assigning venues to the user.|
|[setWages](#usersetwages)|Set the container for adding the user's wages.|
|[setWebsite](#usersetwebsite)|Set the user's website.|




### User::getAddress1  

**Description**

```php
public getAddress1 (void)
```

Get the first line of the user's address. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the first line of the user's address


<hr />


### User::getAddress2  

**Description**

```php
public getAddress2 (void)
```

Get the second line of the user's address. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the second line of the user's address.


<hr />


### User::getAllowFeedback  

**Description**

```php
public getAllowFeedback (void)
```

Get whether or not the user can provide feedback on online courses. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`bool`

> true if and only if the user can provide feedback


<hr />


### User::getAlternateEmail  

**Description**

```php
public getAlternateEmail (void)
```

Returns the User's alternate email address. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string|null`

> Returns the user's alternate email address.


<hr />


### User::getAuthenticationType  

**Description**

```php
public getAuthenticationType (void)
```

Returns the User's authentication type. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> Returns the User's authentication type.


<hr />


### User::getCity  

**Description**

```php
public getCity (void)
```

Get the city of the user's address. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the city of the user's address


<hr />


### User::getCountry  

**Description**

```php
public getCountry (void)
```

Get the country of the user's address. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the country of the user's address


<hr />


### User::getCreatedDate  

**Description**

```php
public getCreatedDate (void)
```

Get the date the User was created. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\DateTimeInterface`

> the date the User was created


<hr />


### User::getCustomFields  

**Description**

```php
public getCustomFields (void)
```

Get the container for the custom fields in your account. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`array`

> the custom fields


<hr />


### User::getDivision  

**Description**

```php
public getDivision (void)
```

Get the user's division. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the user's division


<hr />


### User::getEmail  

**Description**

```php
public getEmail (void)
```

Get the email address for the user 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string|null`

> the email address for the user


<hr />


### User::getEmployeeId  

**Description**

```php
public getEmployeeId (void)
```

Get provided for the Email tag. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`




<hr />


### User::getFax  

**Description**

```php
public getFax (void)
```

Get the user's fax number. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the user's fax number


<hr />


### User::getGivenName  

**Description**

```php
public getGivenName (void)
```

Get the given name of the user, also known as their first name. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string|null`

> The given name of the user, also known as their first name.


<hr />


### User::getGroups  

**Description**

```php
public getGroups (void)
```

Get the container representing the Groups the User is a member of. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`array`

> the container for the User's groups


<hr />


### User::getHomeGroup  

**Description**

```php
public getHomeGroup (void)
```

Get the name of the user's home group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the name of the user's home group


<hr />


### User::getId  

**Description**

```php
public getId (void)
```

Gets the user's ID. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the user's ID


<hr />


### User::getLanguage  

**Description**

```php
public getLanguage (void)
```

Get the language the user's account uses. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the language to use


<hr />


### User::getLearnerNotifications  

**Description**

```php
public getLearnerNotifications (void)
```

Get pending or outstanding courses in SmarterU. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`bool`




<hr />


### User::getModifiedDate  

**Description**

```php
public getModifiedDate (void)
```

Get the date the User was last modified. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?\DateTimeInterface`

> the date the User was last modified


<hr />


### User::getOldEmail  

**Description**

```php
public getOldEmail (void)
```

Get the old email address of the user. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the old email address


<hr />


### User::getOldEmployeeId  

**Description**

```php
public getOldEmployeeId (void)
```

Get the old employee ID of the user. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the old employee ID


<hr />


### User::getOrganization  

**Description**

```php
public getOrganization (void)
```

Get the name of the organization to assign to the user. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the name of the organization


<hr />


### User::getPassword  

**Description**

```php
public getPassword (void)
```

Returns the password to assign to the user. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string|null`

> Returns the password to assign to the user.


<hr />


### User::getPhoneAlternate  

**Description**

```php
public getPhoneAlternate (void)
```

Get the user's alternate phone number. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the user's alternate phone number


<hr />


### User::getPhoneMobile  

**Description**

```php
public getPhoneMobile (void)
```

Get the user's mobile phone number. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the user's mobile phone number


<hr />


### User::getPhonePrimary  

**Description**

```php
public getPhonePrimary (void)
```

Get the user's primary phone number. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the user's primary phone number


<hr />


### User::getPostalCode  

**Description**

```php
public getPostalCode (void)
```

Get the user's postal code. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the user's postal code


<hr />


### User::getProvince  

**Description**

```php
public getProvince (void)
```

Get the province or state of the user's address. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the province or state of the user's address


<hr />


### User::getReceiveNotifications  

**Description**

```php
public getReceiveNotifications (void)
```

Get whether or not the user will receive email notifications. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`bool`

> true if and only if the user will receive email notifications


<hr />


### User::getRoles  

**Description**

```php
public getRoles (void)
```

Get the container for the learning plans assigned to the user. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`array`

> the container for the learning plans to assign to the user


<hr />


### User::getSendEmailTo  

**Description**

```php
public getSendEmailTo (void)
```

Returns where the user's email should be sent (Supervisor, Self, or Alternate). 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> Returns where the user's email should be sent.


<hr />


### User::getSendMailTo  

**Description**

```php
public getSendMailTo (void)
```

Get the location where the user's physical mail should be sent. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the location where physical mail should be sent


<hr />


### User::getStatus  

**Description**

```php
public getStatus (void)
```

Return the status of the user. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> the status of the user


<hr />


### User::getSupervisorNotifications  

**Description**

```php
public getSupervisorNotifications (void)
```

Get of any users they are responsible for. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`bool`




<hr />


### User::getSupervisors  

**Description**

```php
public getSupervisors (void)
```

Get the container for the user's supervisors. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`array`

> the container for the user's supervisors


<hr />


### User::getSurname  

**Description**

```php
public getSurname (void)
```

Returns the surname of the user, also known as their last name. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string|null`

> The surname of the user, also known as their last name.


<hr />


### User::getTeams  

**Description**

```php
public getTeams (void)
```

Get the container for the teams to assign to the user. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`array`

> the teams to assign to the user


<hr />


### User::getTimezone  

**Description**

```php
public getTimezone (void)
```

Returns the user's timezone. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> Returns the user's timezone.


<hr />


### User::getTitle  

**Description**

```php
public getTitle (void)
```

Get the user's title. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the user's title


<hr />


### User::getVenues  

**Description**

```php
public getVenues (void)
```

Get the container for assigning venues to the user. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?array`

> the container for the user's venues


<hr />


### User::getWages  

**Description**

```php
public getWages (void)
```

Get the container for adding the user's wages. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?array`

> the container for the user's wages


<hr />


### User::getWebsite  

**Description**

```php
public getWebsite (void)
```

Get the user's website. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> the URL of the user's website


<hr />


### User::setAddress1  

**Description**

```php
public setAddress1 (?string $address1)
```

Set the first line of the user's address. 

 

**Parameters**

* `(?string) $address1`
: the first line of the user's address  

**Return Values**

`self`




<hr />


### User::setAddress2  

**Description**

```php
public setAddress2 (?string $address2)
```

Set the second line of the user's address. 

 

**Parameters**

* `(?string) $address2`
: the second line of the user's address  

**Return Values**

`self`




<hr />


### User::setAllowFeedback  

**Description**

```php
public setAllowFeedback (bool $allowFeedback)
```

Set whether or not the user can provide feedback on online courses. 

 

**Parameters**

* `(bool) $allowFeedback`
: true if and only if the user can provide feedback  

**Return Values**

`self`




<hr />


### User::setAlternateEmail  

**Description**

```php
public setAlternateEmail (string|null $alternateEmail)
```

Sets the user's alternate email address. 

 

**Parameters**

* `(string|null) $alternateEmail`
: The user's alternate email address.  

**Return Values**

`self`




<hr />


### User::setAuthenticationType  

**Description**

```php
public setAuthenticationType (string $authenticationType)
```

Sets the user's authentication type. 

 

**Parameters**

* `(string) $authenticationType`
: The user's authentication type.  

**Return Values**

`self`




<hr />


### User::setCity  

**Description**

```php
public setCity (?string $city)
```

Set the city of the user's address. 

 

**Parameters**

* `(?string) $city`
: the city of the user's address  

**Return Values**

`self`




<hr />


### User::setCountry  

**Description**

```php
public setCountry (?string $country)
```

Set the country of the user's address. Acceptable values are "Canada", "United States", or "International". 

 

**Parameters**

* `(?string) $country`
: the country of the user's address  

**Return Values**

`self`




<hr />


### User::setCreatedDate  

**Description**

```php
public setCreatedDate (\DateTimeInterface $createdDate)
```

Set the date the User was created. 

 

**Parameters**

* `(\DateTimeInterface) $createdDate`
: The date the User was created  

**Return Values**

`self`




<hr />


### User::setCustomFields  

**Description**

```php
public setCustomFields (array $customFields)
```

Set the container for the custom fields in your account. The custom fields must already exist within your SmarterU account. 

 

**Parameters**

* `(array) $customFields`
: the custom fields in your account  

**Return Values**

`self`




<hr />


### User::setDivision  

**Description**

```php
public setDivision (?string $division)
```

Set the user's division. 

 

**Parameters**

* `(?string) $division`
: the user's division  

**Return Values**

`self`




<hr />


### User::setEmail  

**Description**

```php
public setEmail (string|null $email)
```

Set the email address for the user 

 

**Parameters**

* `(string|null) $email`
: the email address for the user  

**Return Values**

`self`




<hr />


### User::setEmployeeId  

**Description**

```php
public setEmployeeId (string $employeeId)
```

Set provided for the employee ID tag. 

 

**Parameters**

* `(string) $employeeId`
: provided for the employee ID tag.  

**Return Values**

`self`




<hr />


### User::setFax  

**Description**

```php
public setFax (?string $fax)
```

Set the user's fax number. 

 

**Parameters**

* `(?string) $fax`
: the user's fax number  

**Return Values**

`self`




<hr />


### User::setGivenName  

**Description**

```php
public setGivenName (string|null $givenName)
```

Set the given name of the user, also known as their first name. 

 

**Parameters**

* `(string|null) $givenName`
: The given name of the user, also known as their first name.  

**Return Values**

`self`




<hr />


### User::setGroups  

**Description**

```php
public setGroups (array $groups)
```

Set the container representing the Groups the User is a member of. 

 

**Parameters**

* `(array) $groups`
: the container for the user's group assignments  

**Return Values**

`self`




<hr />


### User::setHomeGroup  

**Description**

```php
public setHomeGroup (?string $homeGroup)
```

Set the name of the user's home group. 

 

**Parameters**

* `(?string) $homeGroup`
: the name of the user's home group  

**Return Values**

`self`




<hr />


### User::setId  

**Description**

```php
public setId (string $id)
```

Sets the user's ID. 

 

**Parameters**

* `(string) $id`
: the user's ID  

**Return Values**

`self`




<hr />


### User::setLanguage  

**Description**

```php
public setLanguage (?string $language)
```

Set the language the user's account uses. 

 

**Parameters**

* `(?string) $language`
: the language to use for this user  

**Return Values**

`self`




<hr />


### User::setLearnerNotifications  

**Description**

```php
public setLearnerNotifications (bool $learnerNotifications)
```

Set pending or outstanding courses in SmarterU. 

 

**Parameters**

* `(bool) $learnerNotifications`
: pending or outstanding courses in SmarterU.  

**Return Values**

`self`




<hr />


### User::setModifiedDate  

**Description**

```php
public setModifiedDate (\DateTimeInterface $modifiedDate)
```

Set the date the User was last modified. 

 

**Parameters**

* `(\DateTimeInterface) $modifiedDate`
: The date the User was last modified  

**Return Values**

`self`




<hr />


### User::setOldEmail  

**Description**

```php
public setOldEmail (?string $oldEmail)
```

Set the old email address of the user. 

 

**Parameters**

* `(?string) $oldEmail`
: the old email address  

**Return Values**

`self`




<hr />


### User::setOldEmployeeId  

**Description**

```php
public setOldEmployeeId (?string $oldEmployeeId)
```

Set the old employee ID of the user. 

 

**Parameters**

* `(?string) $oldEmployeeId`
: the old employee ID  

**Return Values**

`self`




<hr />


### User::setOrganization  

**Description**

```php
public setOrganization (?string $organization)
```

Set the name of the organization to assign to the user. The organization must already exist within your SmarterU account. 

 

**Parameters**

* `(?string) $organization`
: the name of the organization  

**Return Values**

`self`




<hr />


### User::setPassword  

**Description**

```php
public setPassword (string|null $password)
```

Sets the password to assign to the user 

 

**Parameters**

* `(string|null) $password`
: The password to assign to the user  

**Return Values**

`self`




<hr />


### User::setPhoneAlternate  

**Description**

```php
public setPhoneAlternate (?string $phoneAlternate)
```

Set the user's alternate phone number. 

 

**Parameters**

* `(?string) $phoneAlternate`
: the user's alternate phone number  

**Return Values**

`self`




<hr />


### User::setPhoneMobile  

**Description**

```php
public setPhoneMobile (?string $phoneMobile)
```

Set the user's mobile phone number. 

 

**Parameters**

* `(?string) $phoneMobile`
: the user's mobile phone number  

**Return Values**

`self`




<hr />


### User::setPhonePrimary  

**Description**

```php
public setPhonePrimary (?string $phonePrimary)
```

Set the user's primary phone number. 

 

**Parameters**

* `(?string) $phonePrimary`
: the user's primary phone number  

**Return Values**

`self`




<hr />


### User::setPostalCode  

**Description**

```php
public setPostalCode (?string $postalCode)
```

Set the user's postal code. 

 

**Parameters**

* `(?string) $postalCode`
: the user's postal code  

**Return Values**

`self`




<hr />


### User::setProvince  

**Description**

```php
public setProvince (?string $province)
```

Set the province or state of the user's address. 

 

**Parameters**

* `(?string) $province`
: the province or state of the user's address  

**Return Values**

`self`




<hr />


### User::setReceiveNotifications  

**Description**

```php
public setReceiveNotifications (bool $receiveNotifications)
```

Set whether or not the user will receive email notifications. 

 

**Parameters**

* `(bool) $receiveNotifications`
: true if and only if the user will  
receive email notifications  

**Return Values**

`self`




<hr />


### User::setRoles  

**Description**

```php
public setRoles (array $)
```

Set the container for the learning plans assigned to the user. The learning plans must already exist within your SmarterU account. 

 

**Parameters**

* `(array) $`
: the container for the learning plans to assign to the user  

**Return Values**

`self`




<hr />


### User::setSendEmailTo  

**Description**

```php
public setSendEmailTo (string $sendEmailTo)
```

Sets where the user's email should be sent (Supervisor, Self, or Alternate). 

 

**Parameters**

* `(string) $sendEmailTo`
: Sets where the user's email should be sent.  

**Return Values**

`self`




<hr />


### User::setSendMailTo  

**Description**

```php
public setSendMailTo (?string $sendMailTo)
```

Set the location where the user's physical mail should be sent. 

 

**Parameters**

* `(?string) $sendMailTo`
: the location where physical mail should be sent  

**Return Values**

`self`




<hr />


### User::setStatus  

**Description**

```php
public setStatus (string $status)
```

Set the status of the user. 

 

**Parameters**

* `(string) $status`
: the status of the user  

**Return Values**

`self`




<hr />


### User::setSupervisorNotifications  

**Description**

```php
public setSupervisorNotifications (bool $supervisorNotifications)
```

Set of any users they are responsible for. 

 

**Parameters**

* `(bool) $supervisorNotifications`
: of any users they are responsible for.  

**Return Values**

`self`




<hr />


### User::setSupervisors  

**Description**

```php
public setSupervisors (array $supervisors)
```

Set the container for the user's supervisors. Each supervisor must already exist in SmarterU. 

 

**Parameters**

* `(array) $supervisors`
: the supervisors for this user  

**Return Values**

`self`




<hr />


### User::setSurname  

**Description**

```php
public setSurname (string|null $surname)
```

Set the surname of the user, also known as their last name. 

 

**Parameters**

* `(string|null) $surname`
: The surname of the user, also known as their  
last name.  

**Return Values**

`self`




<hr />


### User::setTeams  

**Description**

```php
public setTeams (array $)
```

Set the container for the teams to assign to the user. The team names provided must already exist in your SmarterU account. 

 

**Parameters**

* `(array) $`
: teams the teams to assign to the user  

**Return Values**

`self`




<hr />


### User::setTimezone  

**Description**

```php
public setTimezone (string $timezone)
```

Sets the user's timezone. 

 

**Parameters**

* `(string) $timezone`
: Sets the user's timezone.  

**Return Values**

`self`




<hr />


### User::setTitle  

**Description**

```php
public setTitle (?string $title)
```

Set the user's title. 

 

**Parameters**

* `(?string) $title`
: the user's title  

**Return Values**

`self`




<hr />


### User::setVenues  

**Description**

```php
public setVenues (array $venues)
```

Set the container for assigning venues to the user. 

 

**Parameters**

* `(array) $venues`
: the container for the user's venues  

**Return Values**

`self`




<hr />


### User::setWages  

**Description**

```php
public setWages (array $wages)
```

Set the container for adding the user's wages. 

 

**Parameters**

* `(array) $wages`
: the container for the user's wages  

**Return Values**

`self`




<hr />


### User::setWebsite  

**Description**

```php
public setWebsite (?string $website)
```

Set the user's website. 

 

**Parameters**

* `(?string) $website`
: the URL of the user's website  

**Return Values**

`self`




<hr />


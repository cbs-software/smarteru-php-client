# PHP SmarterU LMS API CLient

An API Client for the [SmarterU LMS API](https://support.smarteru.com/docs/api).
This project is not affiliated with Neovation.

## Requirements

* PHP 7.4 or greater
* PHP SimpleXML extension enabled

## Third-Party Dependencies

* [Guzzle](https://docs.guzzlephp.org/en/stable/)

## Installation

1. Add the repository to the `repositories` in your composer.json

```json
"repositiories": [
    {
        "type": "package",
        "package": {
            "name": "cbssoftware/smarteru-client",
            "version": "0.1",
            "type": "library",
            "source": {
                "url": "https://github.com/cbs-software/smarteru-client.git",
                "type": "git",
                "reference": "main"
            }
        }
    },
    ...
]
```

2. Add the package to the `require` section of your composer.json

```json
"require": {
    "cbssoftware/smarteru-client": "*",
    ...
}

3. Let `composer` install the library

```sh
composer install
```

## Usage

Currently supported methods:
    
1. [createUser](https://support.smarteru.com/v1/docs/api-createuser)
2. [getUser](https://support.smarteru.com/v1/docs/api-getuser)
3. [listUsers](https://support.smarteru.com/v1/docs/api-listusers)
4. [updateUser](https://support.smarteru.com/v1/docs/api-updateuser)
5. [getUserGroups](https://support.smarteru.com/v1/docs/api-getusergroups)
6. [createGroup](https://support.smarteru.com/v1/docs/api-creategroup)
7. [getGroup](https://support.smarteru.com/v1/docs/api-getgroup)
8. [listGroups](https://support.smarteru.com/v1/docs/api-listgroups)
9. [updateGroup](https://support.smarteru.com/v1/docs/api-updategroup)

### **DataType-Based Methods**

The createUser and updateUser methods both take an instance of 
CBS/SmarterU/DataTypes/User as a parameter, while createGroup and updateGroup
take an instance of CBS/SmarterU/DataTypes/Group. Some attributes of the User
or Group in question may be omitted.

<details><summary>Click here to view the attributes of the Group type.</summary>

1. name: The name of the group. You may not have multiple groups with the same
name in your SmarterU account.

2. groupId: The user-specified identifier for the group. You may not have
multiple groups with the same ID in your SmarterU account.

3. status: The status of the group. Acceptable values are "Active" or "Inactive".
A Group may only be inactive if all Users within the Group are inactive.

4. description: A description for the Group.

5. homeGroupMessage: The message that will be displayed in the learner interface
to Users who have this group set as their home group.

6. notificationEmails: An array of email addresses to which the [Certification
Completion to Home Group Contact](https://support.smarteru.com/v1/docs/system-email-templates-certification-emails#certification-completion-to-home-group-contact)
, [Course Completion to Group Contact](https://support.smarteru.com/v1/docs/system-email-templates-course-emails-general#course-completion-to-group-contact)
, and [Task Completion to Group Contact](https://support.smarteru.com/v1/docs/system-email-templates-course-emails-online-and-scorm#task-completion-to-group-contact)
emails will be sent.

7. userHelpOverrideDefault: Specifies whether the Group's "Enable User Help"
setting overrides the account's setting.

8. userHelpEnabled: Specifies whether a link is displayed in the learner
interface to enable users to request help.

9. userHelpEmail: A list of email addresses to which help requests will be sent.

10. userHelpText: The message that will be displayed alongside the "Help" button.

11. tags: An array of CBS\SmarterU\DataType\Tag instances, representing tags
assigned to the Group.

12. userLimitEnabled: Specifies whether or not there is a limit on the number
of Users that can be added to the Group.

13. userLimitAmount (required if userLimitEnabled is true): The maximum number
of Users that can be added to the Group.

14. users: An array of CBS\SmarterU\DataTypes\GroupPermissions instances
representing the Users who will be members of the new Group and what their
permissions will be within the Group.

15. learningModules: An array of CBS\SmarterU\DataTypes\LearningModule instances
representing the training courses to be assigned to the created Group.

16. subscriptionVariants: An array of CBS\SmarterU\DataTypes\SubscriptionVariant
instances representing the subscriptions assigned to the Group.

17. dashboardSetId: The identifier of the dashboard to be assigned to the Group.
If left blank, the account's default dashboard will be used.

</details><br/>

<details><summary>Click here to view the attributes of the User type.</summary>

1. email: The user's email address.

2. employeeId: The user's employee ID. Must be unique to this user.

3. givenName: The user's given (first) name.

4. surname: The user's surname.

5. password: The password to assign to the user. Must be a minimum of 8
characters. If no password is provided upon the user's creation, one will be
generated randomly. Users must change their password when logging into a new
account for the first time.

6. timeZone: The user's time zone. A list of acceptable values can be found
[here](https://support.smarteru.com/v1/docs/time-zones).

7. learnerNotifications: Specifies whether the user should receive weekly
reminders of their pending or outstanding courses in SmarterU.

8. supervisorNotifications: Specifies whether the user should receive weekly
reports on the status of any users they are responsible for.

9. sendEmailTo: Specifies where the user's emails should be sent. Acceptable
values are either "Supervisor", "Self", or "Alternate". "Alternate" is only
allowed if a valid alternate email address is provided.

10. alternateEmail: An alternate email address for the user.

11. authenticationType: Specifies how the user logs in. Acceptable values:
- "SmarterU" (default): the user logs in through the SmarterU interface.
- "External": the user logs in through an external system using single-sign on.
- "Both": the user logs in through both the SmarterU interface and an external
system.

12. supervisors: An array of email addresses of the user's supervisors.

13. organization: The name of the organization to assign to the user. The
organization name provided must match one that already exists in your SmarterU
account.

14. teams: An array containing the names of the teams the user is a member of.
Team names provided must match ones that already exist in your SmarterU account.

15. customFields: An array containing the custom user fields in your account.
(not yet implemented)

16. language: The language used for the user's account.

17. status: The status of the user. Acceptable values are "Active" or "Inactive".
Defaults to "Active".

18. title: The user's title.

19. division: The user's division.

20. allowFeedback: Specifies whether the user is able to provide feedback for
online courses. False by default.

21. phonePrimary: The user's primary phone number.

22. phoneAlternate: The user's alternate phone number.

23. phoneMobile: The user's mobile phone number.

24. fax: The user's fax number.

25. website: The user's website. Must be provided in full URL format.

26. address1: The first line of the user's address.

27. address2: The second line of the user's address.

28. city: The city of the user's address.

29. province: The province or state of the user's address. Acceptable values
can be found [here](https://support.smarteru.com/v1/docs/api-call-province-and-state-names-1).

30. country: The country of the user's address. Acceptable values are "Canada",
"United States", or "International".

31. postalCode: The postal code of the user.

32. sendMailTo: The location where the user's physical mail should be sent.
Acceptable values are "Personal" or "Organization".

33. roles: An array of CBS\SmarterU\DataTypes\LearningModule instances. Used to
assign training courses to the user. Learning plan name and ID must be set,
all other attributes are unused in this context.

34. receiveNotifications: Specifies whether the user will receive email
notifications. True by default.

35. homeGroup: The name of the user's home group. If not provided, will default
to the first group provided below.

36. groups: An array of CBS\SmarterU\DataTypes\GroupPermissions instances.
Group name and/or group ID, and the array of CBS\SmarterU\DataTypes\Permission
must be set. Other attributes are not used in this context. If the permissions
array is empty, the user will not have any permissions within the group, but
will still be a member. A list of all possible permissions can be found
[here](https://support.smarteru.com/v1/docs/api-createuser#grouppermissions).

37. venues: An array of CBS\SmarterU\DataTypes\Venue instances (not yet implemented).

38. wages: An array of CBS\SmarterU\DataTypes\Wage instances (not yet implemented).

</details><br/>

#### **CreateGroup**

The CreateGroup method takes an instance of CBS\SmarterU\DataTypes\Group as a
parameter. Some attributes may be left blank, while others are required. Leaving
a required attribute empty will result in an exception.

<details><summary>Click here to view which Group attributes are necessary for this method.</summary>

**Required attributes:**

See the "Show Group attributes" section above for a description of each attribute.

1. name

2. status

3. description

4. homeGroupMessage

5. notificationEmails

6. users

7. learningModules

**Optional attributes:**

1. groupId

2. userHelpOverrideDefault

3. userHelpEnabled

4. userHelpEmail

5. userHelpText

6. tags

7. userLimitEnabled

8. userLimitAmount (required if userLimitEnabled is true)

9. subscriptionVariants

10. dashboardSetId

</details><br/>

#### **CreateUser**

The CreateUser method takes an instance of CBS\SmarterU\DataTypes\User as a
parameter. Some attributes may be left blank, while others are required. Leaving
a required attribute empty will result in an exception.

<details><summary>Click here to view which User attributes are necessary for this method.</summary>

**Required Attributes**

See the "Show User Attributes" section above for details on each attribute.

1. email

2. employeeId

3. givenName

4. surname

5. password

6. learnerNotifications

7. supervisorNotifications

8. sendEmailTo

9. authenticationType

10. groups

11. venues

12. wages

**Optional Attributes**

1. timeZone

2. alternateEmail (required if sendEmailTo is set to "Alternate")

3. supervisors

4. organization

5. teams

6. customFields

7. language

8. status (will default to "Active" if left blank)

9. title

10. division

11. allowFeedback (will default to false if left blank)

12. phonePrimary

13. phoneAlternate

14. phoneMobile

15. fax

16. website

17. address1

18. address2

19. city

20. province

21. country

22. postalCode

23. sendMailTo

24. roles

25. receiveNotifications (will default to true if left blank)

26. homeGroup (will default to first group provided if left blank)

</details><br/>

#### **UpdateGroup**

The UpdateGroup method takes an instance of CBS\SmarterU\DataTypes\Group as a
parameter. Some attributes may be left blank, while others are required. Leaving
a required attribute empty will result in an exception.

<details><summary>Click here to view which Group attributes are necessary for this method.</summary>

When updating the Group's name and/or ID, you must first set the "oldName"
and/or "oldGroupId" attributes so that the SmarterU API can identify the
Group.

**Required attributes:**

See the "Show Group attributes" section above for a description of each attribute.

1. users

2. learningModules

3. subscriptionVariants

**Optional attributes:**

1. name (required if groupId is not present)

2. oldName (required if changing the Group's name)

3. groupId (required if name is not present)

4. oldGroupId (required if changing the Group's ID)

5. status

6. description

7. homeGroupMessage

8. notificationEmails

9. userHelpOverrideDefault

10. userHelpEnabled

11. userHelpEmail

12. userHelpText

13. tags

14. userLimitEnabled

15. userLimitAmount (required if the user limit is enabled)

16. dashboardSetId

</details><br/>

#### **UpdateUser**

The updateUser method takes an instance of CBS\SmarterU\DataTypes\User as a
parameter. Some attributes may be left blank, while others are required. Leaving
a required attribute empty will result in an exception. Note that the "password"
field cannot be modified via an updateUser request.

<details><summary>Click here to view which User attributes are necessary for this method.</summary>

When updating the User's email address and/or employee ID, you must first set
the "oldEmail" and/or "oldEmployeeId" attributes so that the SmarterU API can
identify the User.

**Required Attributes**

1. learnerNotifications

2. supervisorNotifications

3. groups

4. venues

5. wages

**Optional Attributes**

1. email (required if employeeId is left blank)

2. oldEmail (required if changing the User's email)

3. employeeId (required if email is left blank)

4. oldEmployeeId (required if changing the User's employee ID)

5. givenName

6. surname

7. timeZone

8. sendEmailTo

9. alternateEmail (required if sendEmailTo is set to "Alternate")

10. authenticationType

11. supervisors

12. organization

13. teams

14. customFields

15. language

16. status

17. title

18. division

19. allowFeedback

20. phonePrimary

21. phoneAlternate

22. phoneMobile

23. fax

24. website

25. address1

26. address2

27. city

28. province

29. country

30. postalCode

31. sendMailTo

32. roles

33. receiveNotifications

34. homeGroup

</details><br/>

### **Query-Based Methods**

All other methods take a query as a parameter. Queries can be found in the
CBS/SmarterU/Queries directory, and usage details for each type of query are
listed below.

#### **GetGroupQuery**

This query is used for the getGroup method. It must contain the group
identifier, which is either the name or the ID of the Group in question.

<details><summary>Click here to view the attributes of the GetGroupQuery type.</summary>

```php
use CBS\SmarterU\Queries\GetGroupQuery;

$query = (new GetGroupQuery())
    ->setName($name);
```

OR

```php
use CBS\SmarterU\Queries\GetGroupQuery;

$query = (new GetGroupQuery())
    ->setGroupId($groupId);
```

All other necessary information will be filled in automatically by the Client.
The two types of group identifier are mutually exclusive with each other, and
only the most recently updated one will be saved. For example, doing this:

```php
use CBS\SmarterU\Queries\GetGroupQuery;

$query = (new GetGroupQuery())
    ->setGroupId($groupId);
    
$query->setName($name);
```

would erase the ID and leave the name as the only group identifier attached to
the query. In the event that a GetGroupQuery passed into the Client does not
have a group identifier attached to it, an exception will be thrown.

</details><br/>

#### **GetUserQuery**
This query is used for the getUser and the getUserGroups methods. It must
contain the user identifier, which is either the ID, email address, or
employee ID of the User in question.

<details><summary>Click here to view the attributes of the GetUserQuery type.</summary>

```php
use CBS\SmarterU\Queries\GetUserQuery;

$query = (new GetUserQuery())
    ->setId($id);
```

OR

```php
use CBS\SmarterU\Queries\GetUserQuery;

$query = (new GetUserQuery())
    ->setEmail($emailAddress);
```

OR

```php
use CBS\SmarterU\Queries\GetUserQuery;

$query = (new GetUserQuery())
    ->setEmployeeId($employeeId);
```

All other necessary information will be filled in automatically by the Client.
The three types of user identifier are mutually exclusive with each other, and
only the most recently updated one will be saved. For example, doing this:

```php
use CBS\SmarterU\Queries\GetUserQuery;

$query = (new GetUserQuery())
    ->setId($id);
    
$query->setEmail($emailAddress);
```

would erase the ID and leave the email address as the only user identifier
attached to the query. In the event that a GetUserQuery passed into the Client
does not have a user identifier attached to it, an exception will be thrown.

</details><br/>

#### **ListGroupsQuery**

This query is used for the listGroups method. It does not have to contain any
data beyond what is automatically filled in by the Client, however it may
contain up to three optional parameters:

<details><summary>Click here to view the attributes of the ListGroupsQuery type.</summary>

1. groupName: an instance of CBS\SmarterU\Queries\MatchTag. Specifies the
name of the group(s) to return, and whether the groups returned must match
the supplied name exactly or contain it. If left blank, all groups matching
both groupStatus and tags will be returned.
2. groupStatus: a string indicating whether to read active groups or inactive
groups. If left blank, both active and inactive groups matching the criteria
specified by groupName and tags will be returned.
3. tags: an array of one or more instances of CBS\SmarterU\DataTypes\Tag.
Specifies the tag(s) that must be attached to all Groups to return. If left
blank, all groups matching both groupName and groupStatus will be returned.

If all three optional parameters are left blank, then all Groups will be
returned without applying any filters.

</details><br/>

#### **ListUsersQuery**

This query is used for the listUsers method. It does not have to contain any
data beyond what is automatically filled in by the Client, however it may
contain up to fourteen optional parameters:

<details><summary>Click here to view the attributes of the ListUsersQuery type.</summary>

1. page: Which page on the list of users to get. If left blank, it will default
to 1.
2. pageSize: The maximum number of users to return. If left blank, it will
default to 50. May not be set above 1000.
3. sortField: The field that will be used to sort the results. May be either
'NAME' or 'EMPLOYEE_ID'. If left blank, it will default to 'NAME'.
4. sortOrder: Whether results will be sorted in ascending ('ASC') or descending
('DESC') order. If left blank, it will default to 'ASC'.
5. email: Must be an instance of CBS\SmarterU\Queries\MatchTag. Specifies
the email address of the User(s) to return, as well as whether to return Users
with exactly that email address or an email address that contains the specified
email address. For example, the following would return all users who have an
example.com email address:

```php
use CBS\SmarterU\Queries\ListUsersQuery;
use CBS\SmarterU\Queries\Tags\MatchTag;

$email = (new MatchTag())
    ->setMatchType('Contains')
    ->setValue('@example.com');

$query = (new ListUsersQuery())
    ->setEmail($email);
```

If left blank, results will not be filtered by email address.

6. employeeId: Must be an instance of CBS\SmarterU\Queries\Tags\MatchTag.
Specifies the employee ID of the User(s) to return, as well as whether to
return Users with exactly that employee ID or an employee ID that contains the
specified value. If left blank, results will not be filtered by employee ID.

7. name: Must be an instance of CBS\SmarterU\Queries\Tags\MatchTag. Specifies
the name of the User(s) to return, as well as whether to return Users with
exactly that name or a name that contains the specified value. Full names must
be formatted as "Last,First". In the following example, query 1 would return
all Users named John Smith, and query 2 would return all Users whose name
contains "Smith".

```php
use CBS\SmarterU\Queries\ListUsersQuery;
use CBS\SmarterU\Queries\Tags\MatchTag;

$name1 = (new MatchTag())
    ->setMatchType('Exact')
    ->setValue('Smith,John');

$name2 = (new MatchTag())
    ->setMatchType('Contains')
    ->setValue('Smith');

$query1 = (new ListUsersQuery())
    ->setName($name1);

$query2 = (new ListUsersQuery())
    ->setName($name2);
```
If left blank, results will not be filtered by name.

8. homeGroup: If set, the query will only return Users who have the specified
Group set as their home group. If left blank, results will not be filtered by
home group.

9. groupName: If set, the query will only return Users who are a member of the
specified Group. If left blank, results will not be filtered by group
membership.

10. userStatus: Whether to return active users, inactive users, or all users.
Acceptable values are 'Active', 'Inactive', or 'All'. If left blank, it will
default to 'All'.

11. createdDate: Must be an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.
If set, the query will only return Users whose account was created between the
two specified dates. If left blank, results will not be filtered by the date
of account creation.

12. modifiedDate: Must be an instance of CBS\SmarterU\Queries\Tags\DateRangeTag.
If set, the query will only return Users whose account was last modified
between the two specified dates. If left blank, results will not be filtered
by the last modified date.

13. teams: Must be an array of team names. If set, the query will only return
Users who are a member of at least one of the specified teams. If left blank,
results will not be filtered by team membership.

14. customFields: Must be an array of custom fields. If set, the query will
only return Users who have the specified Custom Field attached to their
account. If left blank, results will not be filtered by custom fields.

If all parameters are left blank, then the first 50 Users, when sorted in
ascending order by name, will be returned.

</details><br/>

### **Output**

All Client methods will return a similarly structured result:

```php
[
    'Response' => [
        'attribute1Name' => attribute1Value,
        'attribute2Name' => attribute2Value,
        ...
    ],
    'Errors' => [
        'error1ID' => error1Message,
        'error2ID' => error2Message,
        ...
    ]
]
```

The 'Response' array will contain all of the data the SmarterU API returned
when the request was made. See the SmarterU documentation linked above for
details on the output of each method. If no errors occur (i.e. all data
provided as input is valid), then the 'Errors' array will be empty. If a
non-fatal error occurs (i.e. an optional value is invalid and was not set,
however the rest of the data in your request was valid and went through),
then the error code(s) and corresponding message(s) will be included in the
'Errors' array. If a fatal error occurs, an exception will be thrown, and any
error(s) returned by the SmarterU API will be listed in the exception message.
A list of all possible error codes related to a specific method can be found at
the bottom of that method's page in the documentation linked above, and a list
of all errors in general can be found
[here](https://support.smarteru.com/docs/api-error-codes).

## Tests

This library comes with a set of unit tests for use with
[PHPunit](https://phpunit.readthedocs.io). These test can be run with the
`vendor/bin/phpunit` command provided you have installed the developer
dependencies with `composer`.

```sh
composer install
vendor/bin/phpunit
```

## License

MIT License

Copyright (c) 2022 Core Business Solutions

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

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
"repositories": [
    {
        "type": "package",
        "package": {
            "name": "cbssoftware/smarteru-client",
            "version": "0.1",
            "type": "library",
            "source": {
                "url": "https://github.com/cbs-software/smarteru-php-client.git",
                "type": "git",
                "reference": "main"
            },
            "require": {
                "guzzlehttp/guzzle": "^7.0",
                "ext-SimpleXML": "*"
            },
            "autoload": {
                "psr-4": {
                    "CBS\\SmarterU\\": "src/"
                }
            }
        }
    },
    ...
],
```

2. Let `composer` install the library

```sh
composer require cbssoftware/smarteru-client
```

## Usage

The class `CBS\SmarterU\Client` is used to interact with the SmarterU API.
Its constructor requires two parameters: your SmarterU Account API Key and
your SmarterU User API Key. Instructions to find these keys can be found
[here](https://support.smarteru.com//v1/docs/api-call-xml-package).

```php

use CBS\SmarterU\Client;

$accountAPIkey = 'insert your account API key here';
$userAPIkey = 'insert your user API key here';

$client = new Client($accountAPIkey, $userAPIkey);

```

The following is a list of public methods in Client.php that interact with the SmarterU API:
    
1. [createUser](docs/Client.md#clientcreateuser)
2. [readUserById](docs/Client.md#clientreaduserbyid)
3. [readUserByEmail](docs/Client.md#clientreaduserbyemail)
4. [readUserByEmployeeId](docs/Client.md#clientreaduserbyemployeeid)
5. [listUsers](docs/Client.md#clientlistusers)
6. [updateUser](docs/Client.md#clientupdateuser)
7. [readGroupsForUserById](docs/Client.md#clientreadgroupsforuserbyid)
8. [readGroupsForUserByEmail](docs/Client.md#clientreadgroupsforuserbyemail)
9. [readGroupsForUserByEmployeeId](docs/Client.md#clientreadgroupsforuserbyemployeeid)
10. [createGroup](docs/Client.md#clientcreategroup)
11. [readGroupById](docs/Client.md#clientreadgroupbyid)
12. [readGroupByName](docs/Client.md#clientreadgroupbyname)
13. [listGroups](docs/Client.md#clientlistgroups)
14. [updateGroup](docs/Client.md#clientupdategroup)
15. [addUsersToGroup](docs/Client.md#clientadduserstogroup)
16. [removeUsersFromGroup](docs/Client.md#clientremoveusersfromgroup)
17. [grantPermissions](docs/Client.md#clientgrantpermissions)
18. [revokePermissions](docs/Client.md#clientrevokepermissions)
19. [getLearnerReport](docs/Client.md#clientgetlearnerreport)
20. [requestExternalAuthorizationByEmail](docs/Client.md#clientrequestexternalauthorizationbyemail)
21. [requestExternalAuthorizationByEmployeeId](docs/Client.md#clientrequestexternalauthorizationbyemployeeid)

For usage details on each method, please see [docs/Client.md](docs/Client.md).

## Data Types

A CustomField is a way to assign extra values to a User or a LearnerReport in
addition to the values that are built-in to the data type. All attributes are
required when assigning a CustomField to a User or a LearnerReport. More
information can be found in
[docs/DataTypes/CustomField.md](docs/DataTypes/CustomField.md).

ExternalAuthorization is a container for the information returned by SmarterU
when a user is authenticated via single sign-on from an external website.
More information can be found in
[docs/DataTypes/ExternalAuthorization.md](docs/DataTypes/ExternalAuthorization.md).

A Group is a collection of Users within SmarterU that can all be assigned to
the same training courses. When creating a Group, the "groupId",
"userHelpOverrideDefault", "userHelpEnabled", "userHelpEmail", "userHelpText",
"tags", "userLimitEnabled", "userLimitAmount", "subscriptionVariants", and
"dashboardSetId" attributes are optional. The "users", "permissions",
"oldName", and "oldGroupId" attributes will not be used.  All other attributes
are required.If you would like to add Users to your new Group, you may do so
using the `addUsersToGroup` method in Client. If you would like these users to
have elevated permissions within the Group, you may grant these permissions
using the `grantPermissions` method in Client. When updating a Group
without changing its name or ID, either the "name" or "groupId" attribute must
be set in order to identify the Group to be updated, and the only other
attributes that must be set are the ones you wish to update. Group membership
(i.e. "users" and "permissions") cannot be updated using the "updateGroup"
method in Client, and instead rely on their own dedicated methods. When changing
the Group's name or ID, you must set the "oldName" or "oldGroupId" attributes to
identify the Group to be updated, and the corresponding "name" or "groupId"
attributes to identify the new value. Making the request to SmarterU will clear
out any values from the "oldName" and "oldGroupId" fields in order to prevent
mistakenly using outdated information when using the same Group instance for
future requests to the API. More information can be found in
[docs/DataTypes/Group.md](docs/DataTypes/Group.md).

A LearnerReport, also known as an Enrollment Report, enables training managers
to view the progress of Users who have been assigned to the course, and to see
the Users' results once the course has been completed. LearnerReports are read
from the API using the `getLearnerReport` method in Client, which takes an
instance of `CBS\SmarterU\Queries\GetLearnerReportQuery` as a parameter. All
attributes that are marked as nullable are optional, and all attributes that
are not marked as nullable are required. More information can be found in
[docs/DataTypes/LearnerReport.md](docs/DataTypes/LearningModule.md).

A LearningModule, also known as a Course, is a training assignment that can be
given to a Group. When adding or removing a LearningModule from a Group via the
`updateGroup` method, all attributes are required. When adding a LearningModule
to a Group via the `createGroup` method, "action" is not used, while all other
attributes are required. More information can be found in
[docs/DataTypes/LearningModule.md](docs/DataTypes/LearningModule.md).
 
A SubscriptionVariant is a record of a subscription that is assigned to a Group.
When adding or removing a SubscriptionVariant from a Group via the `updateGroup`
method, all attributes are required. When adding a SubscriptionVariant to a
Group via the `createGroup` method, "action" is not used, while all other
attributes are required. More information can be found in
[docs/DataTypes/SubscriptionVariant.md](docs/DataTypes/SubscriptionVariant.md).

A Tag is a way to assign extra values to a Group in addition to the values that
come built-in to the data type. The "tagValues" attribute is always required,
but the "tagName" and "tagId" attributes are mutually exclusive. **Exactly one
out of those two** is required. Attempting to set both of these attributes in the
same instance of Tag will result in only the most recently updated one being
saved, while the less recently updated one will be automatically set to null.
More information can be found in
[docs/DataTypes/Tag.md](docs/DataTypes/Tag.md).

A User is a record of a user account within SmarterU. Two values will be set by
default: "status" will be active and "receiveNotifications" will be true. If
you would like to set a User to inactive or to not receive notifications, you
must explicitly specify that. When creating a User, at least one of either the
"email" field or the "employeeId" field must be specified. You may set both if
you wish to. The "givenName", "surname", "password", "learnerNotifications",
"supervisorNotifications", "sendEmailTo", "authenticationType", and "homeGroup"
fields are required. If the "sendEmailTo" attribute is set to "Alternate", then
the User's "alternateEmail" field must also be set. The "Groups" field is not
used. By default, the User will be a regular user with no administrative
permissions in their home Group and will not be a member of any other Groups.
If you would like to assign the User to any additional Groups, you may do so
using the `addUsersToGroup` method in Client. If you would like the User to
have any administrative permissions in any of their Groups, you may grant those
permissions using the `grantPermissions` method in Client. All other values are
optional. When updating a User, you must set either the "email" or "employeeId"
field to identify the User being updated. The only required values other than
those fields are the values you wish to update. The `updateUser` method cannot be
used to change a User's Group memberships, or to change the User's password.
The User's home Group can only be changed if the User is a member of multiple
Groups. If you would like to update the User's email address or employee ID,
you may do so by setting the "oldEmail" or "oldEmployeeId" fields to the
current value, then setting the "email" or "employeeId" fields to the new
value. The "oldEmail" and "oldEmployeeId" fields will be set to null when
making the request to SmarterU in order to prevent mistakenly passing outdated
information to SmarterU when making a future request on the same User object.
More information can be found in
[docs/DataTypes/User.md](docs/DataTypes/User.md).

## Queries

Three Client methods, `listUsers`, `listGroups`, and `getLearnerReport`, take
an instance of their respective `CBS\SmarterU\Queries\` objects as a parameter.
`GetUserQuery` and `GetGroupQuery` are only used internally by a private helper
method in Client, while the other Query classes must be handled by the user.
When constructing a query to pass into the Client, any value that is not marked
as nullable and does not have a default value is required, while any value that
is marked as nullable or has a default value is optional. The SmarterU API
will return an array of every User, Group, or LearnerReport that matches the
criteria provided by the query.

### Tags

The classes contained in the `CBS\SmarterU\Queries\Tags` directory are query
segments that are used when filling in certain attributes in the query classes.
The `DateRangeTag` uses two DateTimeInterface objects to filter the results of
your query by a specific time period, for example returning only the 
LearnerReports for courses that were completed between the provided dates. The
`MatchTag` is used to filter the results of a ListGroups request by the Group's
name, or to filter the results of a ListUsers request by the User's email
address, employee ID, or name. The following example would only return Users
whose name is John Smith: 

```php
use CBS\SmarterU\Queries\ListUsersQuery;
use CBS\SmarterU\Queries\Tags\MatchTag;

$matchTag = (new MatchTag())
    ->setMatchType('EXACT')
    ->setValue('John Smith');

$query = (new ListUsersQuery())
    ->setName($matchTag);
```

And this example would return any User who has an example.com email address:

```php
use CBS\SmarterU\Queries\ListUsersQuery;
use CBS\SmarterU\Queries\Tags\MatchTag;

$matchTag = (new MatchTag())
    ->setMatchType('CONTAINS')
    ->setValue('@example.com');

$query = (new ListUsersQuery())
    ->setEmail($matchTag);
```


## Output

All methods in the Client class that interact with the SmarterU API will return
either the appropriate DataType (i.e. the `readUserByEmailAddress` method will
return an instance of `User`), an array of the appropriate DataType if the SmarterU
API returns multiple values, or null if there are no results matching the query
being passed into the API (i.e. calling `readUserByEmailAddress` on an email address
that does not match any Users in your SmarterU account). A more detailed
description of the return values can be found in [docs/Client.md](docs/Client.md).

Some methods in Client take an array of a specific type, such as an array of Users,
as a parameter. If one of these methods is provided with input that is not one of
the acceptable values (i.e. an array of Users that contains anything that is not
an instance of User), a `CBS\SmarterU\Exceptions\InvalidArgumentException` will
be thrown. The exception message will name the parameter that triggered it.

SmarterU requires that certain attributes be set in the DataTypes being
passed in as parameters. Since the exact attributes differ from method to method
using the same DataType, it would be impractical to require these attributes
in the constructor like the API keys in the Client class. Instead, if one of
the required attributes is left unset when the DataType is passed into the method
in Client, a `CBS\SmarterU\Exceptions\MissingValueException` will be thrown. The
exception message will name the attribute that triggered it.

In the event that some kind of HTTP error prevents your request from executing,
a `GuzzleHttp\Exception\ClientException` will be thrown.

If the SmarterU API reports a failure, a `CBS\SmarterU\Exceptions\SmarterUException`
will be thrown, and any error(s) returned by the SmarterU API will be listed in
the exception message. A list of all possible SmarterU errors can be found
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

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

A CustomField is a way to assign extra values to a User or a LearningReport in
addition to the values that are built-in to the data type. More information can
be found in [docs/DataTypes/CustomField.md](docs/DataTypes/CustomField.md).

ExternalAuthorization is a container for the information returned by SmarterU
when a user is authenticated via single sign-on from an external website.
More information can be found in
[docs/DataTypes/ExternalAuthorization.md](docs/DataTypes/ExternalAuthorization.md).

A Group is a collection of Users within SmarterU that can all be assigned to
the same training courses. More information can be found in
[docs/DataTypes/Group.md](docs/DataTypes/Group.md).

A LearnerReport, also known as an Enrollment Report, enables training managers
to view the progress of Users who have been assigned to the course, and to see
the Users' results once the course has been completed. More information can be
found in [docs/DataTypes/LearnerReport.md](docs/DataTypes/LearningModule.md).

A LearningModule, also known as a Course, is a training assignment that can be
given to a Group. More information can be found in
[docs/DataTypes/LearningModule.md](docs/DataTypes/LearningModule.md).
 
A SubscriptionVariant is a record of a subscription that is assigned to a Group.
More information can be found in
[docs/DataTypes/SubscriptionVariant.md](docs/DataTypes/SubscriptionVariant.md).

A Tag is a way to assign extra values to a Group in addition to the values that
come built-in to the data type. More information can be found in
[docs/DataTypes/Tag.md](docs/DataTypes/Tag.md).

A User is a record of a user account within SmarterU. More information can be found
in [docs/DataTypes/User.md](docs/DataTypes/User.md).


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

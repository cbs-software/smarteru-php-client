# CBS\SmarterU\DataTypes\SubscriptionVariant  

A SubscriptionVariant represents the subscriptions that are assigned to
a group.





## Methods

| Name | Description |
|------|-------------|
|[getId](#subscriptionvariantgetid)|Get the system-generated identifier for the subscription variant.|
|[getRequiresCredits](#subscriptionvariantgetrequirescredits)|Get whether enrollments in the subscription require credits.|
|[setId](#subscriptionvariantsetid)|Set the system-generated identifier for the subscription variant.|
|[setRequiresCredits](#subscriptionvariantsetrequirescredits)|Set whether enrollments in the subscription require credits.|




### SubscriptionVariant::getId  

**Description**

```php
public getId (void)
```

Get the system-generated identifier for the subscription variant. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> The system-generated identifier of the subscription


<hr />


### SubscriptionVariant::getRequiresCredits  

**Description**

```php
public getRequiresCredits (void)
```

Get whether enrollments in the subscription require credits. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`bool`

> True if and only if the subscription requires credits.


<hr />


### SubscriptionVariant::setId  

**Description**

```php
public setId (string $Id)
```

Set the system-generated identifier for the subscription variant. 

 

**Parameters**

* `(string) $Id`
: The system-generated identifier of the subscription  

**Return Values**

`self`




<hr />


### SubscriptionVariant::setRequiresCredits  

**Description**

```php
public setRequiresCredits (bool $requiresCredits)
```

Set whether enrollments in the subscription require credits. 

 

**Parameters**

* `(bool) $requiresCredits`
: True if and only if the subscription  
requires credits.  

**Return Values**

`self`




<hr />


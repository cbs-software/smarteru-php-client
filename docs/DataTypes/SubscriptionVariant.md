# CBS\SmarterU\DataTypes\SubscriptionVariant  

A SubscriptionVariant represents the subscriptions that are assigned to
a group.





## Methods

| Name | Description |
|------|-------------|
|[getAction](#subscriptionvariantgetaction)|Get whether the variant is to be assigned to or removed from the group.|
|[getId](#subscriptionvariantgetid)|Get the system-generated identifier for the subscription variant.|
|[getRequiresCredits](#subscriptionvariantgetrequirescredits)|Get whether enrollments in the subscription require credits.|
|[setAction](#subscriptionvariantsetaction)|Set whether the variant is to be assigned to or removed from the group.|
|[setId](#subscriptionvariantsetid)|Set the system-generated identifier for the subscription variant.|
|[setRequiresCredits](#subscriptionvariantsetrequirescredits)|Set whether enrollments in the subscription require credits.|




### SubscriptionVariant::getAction  

**Description**

```php
public getAction (void)
```

Get whether the variant is to be assigned to or removed from the group. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`?string`

> whether the variant is to be assigned or removed


<hr />


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


### SubscriptionVariant::setAction  

**Description**

```php
public setAction (string $action)
```

Set whether the variant is to be assigned to or removed from the group. 

 

**Parameters**

* `(string) $action`
: 'Add' to add the variant to the  
group, 'Remove' to remove it.  

**Return Values**

`self`




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


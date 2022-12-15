# CBS\SmarterU\Queries\Tags\DateRangeTag  

This class represents a range of dates to pass into a query.





## Methods

| Name | Description |
|------|-------------|
|[getDateFrom](#daterangetaggetdatefrom)|Return the first date to include in the DateRange filter.|
|[getDateTo](#daterangetaggetdateto)|Return the last date to include in the DateRange filter.|
|[setDateFrom](#daterangetagsetdatefrom)|Set the first date to include in the DateRange filter.|
|[setDateTo](#daterangetagsetdateto)|Set the last date to include in the DateRange filter.|




### DateRangeTag::getDateFrom  

**Description**

```php
public getDateFrom (void)
```

Return the first date to include in the DateRange filter. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\DateTimeInterface`

> the first date to include in the DateRange filter.


<hr />


### DateRangeTag::getDateTo  

**Description**

```php
public getDateTo (void)
```

Return the last date to include in the DateRange filter. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`\DateTimeInterface`

> the last date to include in the DateRange filter.


<hr />


### DateRangeTag::setDateFrom  

**Description**

```php
public setDateFrom (\DateTimeInterface $dateFrom)
```

Set the first date to include in the DateRange filter. 

 

**Parameters**

* `(\DateTimeInterface) $dateFrom`
: the first date to include in the DateRange filter  

**Return Values**

`self`




<hr />


### DateRangeTag::setDateTo  

**Description**

```php
public setDateTo (\DateTimeInterface $dateTo)
```

Set the last date to include in the DateRange filter. 

 

**Parameters**

* `(\DateTimeInterface) $dateTo`
: the last date to include in the DateRange filter  

**Return Values**

`self`




<hr />


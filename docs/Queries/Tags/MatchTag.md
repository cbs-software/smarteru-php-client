# CBS\SmarterU\Queries\Tags\MatchTag  

This class represents the value passed into several different query parameters
determining whether to retrieve results that exactly match the input or that
just contain the input.





## Methods

| Name | Description |
|------|-------------|
|[getMatchType](#matchtaggetmatchtype)|Return the type of match to retrieve.|
|[getValue](#matchtaggetvalue)|Return the value the query results must match.|
|[setMatchType](#matchtagsetmatchtype)|Set the type of match to retrieve.|
|[setValue](#matchtagsetvalue)|Set the value the query results must match.|




### MatchTag::getMatchType  

**Description**

```php
public getMatchType (void)
```

Return the type of match to retrieve. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> the type of match to retrieve.


<hr />


### MatchTag::getValue  

**Description**

```php
public getValue (void)
```

Return the value the query results must match. 

 

**Parameters**

`This function has no parameters.`

**Return Values**

`string`

> the value the query results must match


<hr />


### MatchTag::setMatchType  

**Description**

```php
public setMatchType (string $matchType)
```

Set the type of match to retrieve. 

 

**Parameters**

* `(string) $matchType`
: The type of match to retrieve.  

**Return Values**

`self`




<hr />


### MatchTag::setValue  

**Description**

```php
public setValue (string $value)
```

Set the value the query results must match. 

 

**Parameters**

* `(string) $value`
: The value the query results must match.  

**Return Values**

`self`




<hr />


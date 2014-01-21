PHP Inversion of Control
========================

IoC framework for PHP

[![Coverage Status](https://coveralls.io/repos/zeeke/php-ioc/badge.png)](https://coveralls.io/r/zeeke/php-ioc)
[![Build Status](https://travis-ci.org/zeeke/php-ioc.png?branch=master)](https://travis-ci.org/zeeke/php-ioc)


Using an Inversion Of Control container you can define the business
logic of an application without depending on the specific framework. All
the core logic can be placed in simple PHP classes, where dependency are
injected by the container.

## Installation

The library can be installed using Composer:

```

# Install Composer
curl -sS https://getcomposer.org/installer | php

# Add php-ioc as a dependency (use dev-master until a stable release is out)
php composer.phar require laborra/php-ioc:dev-master
```

## Basic Usage

The main library class is AppContext, that can be built using the ContextFactory helper,

```PHP

$config = [ 
    // Bean configuration as PHP Array
    //... 
];

$context = ContextFactory::buildFromPHPArray($config);

// Or

$context = ContextFactory::buildFromFile('path/to/configurationfile.php');

```

Below is an example of context configuration with a bas

```PHP

// config.php
return [
    'beans' => [
        'beanOne' => [
            'ClassOne',
            'properties' => [
                'valueProp' => 42,
            ],
        ],
        'beanTwo' => [
            'ClassTwo',
            'properties' => [
                'refOne' => '@beanOne',
            ],
        ],

        'beanThree' => [
            'ClassThree',
            'constructorArgs' => [
                @beanTwo,
                'Answer to life the universe and everything'
            ],
        ],
    ],

]

class ClassOne
{
    public $valueProp;
}

class ClassTwo
{
    /** @var ClassOne */
    public $refOne;
}

class ClassThree
{
    /** @var ClassTwo */
    private $refTwo;

    /** @var string */
    private $strProp

    public function __constructor (ClassTwo $refTwo, $strAtg)
    {
        $this->strProp = $strArg;
    }

    public function getIt ()
    {
        return $this->strProp.' = '.$this->refTwo->refOne->valueProp;
    }
}

// In application logic

$beanObj = $context->getBean('beanThree');
echo $beanObj->getIt(); // Answer to life the universe and everything = 42

```

## Features

The current supported features are:
- Bean definition as request singleton scope
- Definition of relationships between beans
- Constructor arguments
- Class calls after bean creation
- Definition of context parameters
- Configuration based on PHP array, ConfigurationBuilder helper and YAML files

Features available in future releases:
- Prototype scope for beans
- Support for Aspect Oriented Programming
- Cache for configuration and application context
- Support for multiple configuration files

## Defining Application Modules

Using an IoC container it is possible to build modularized applications.
Each module consists of a set of library classes, a set of dependency
and list of bean that must be implemented by the module client.
In this way, you can define an entire unit of business logic without
referencing any framework or specific environment.

### Example of user management module

```PHP

class UserService
{
    /** @var IUserDAO
    public $userDAO;

    public function authenticate ($username, $password)
    {
        // Business logic to authenticate the user
        $user = $this->userDAO->getByUserName($username);

        if ($user == null) {
            throw new Exception("User $username not found");
        }

        if ($user->password != $password) {
            throw new Exception("Invalid credentials");
        }
        
        return $user;
    }
    
    public function register ($username, $password)
    {
        if ($username == "") {
            throw new UserException("Username field cannot be blank");
        }

        if ($password == "") {
            throw new UserException("Password field cannot be blank");
        }

        $user = $this->userDAO->create($username, $password);
        return $user;
    }
}

interface IUserDAO
{
    function getByUsername ($username);

    function create ($username, $password);
}

class User
{
    public $username;

    public $password;
}

```

beans:
    userService:
        class: UserService
        properties:
            userDAO: @userDAO


When using the module in a complete application, we have to implement
the IUserDAO interface and declare it as a context bean.

class SpecificUserDAO implements IUserDAO
{
    function getByUsername ($username)
    {
        // Query the database, possible using framework specific helpers
        // ...
    }
    
    function create ()
    {
        // ...
    }
}

beans:
    userDAO:
        class:  SpecificUserDAO


## ConfigurationBuilder utility

In order to produce readable and maintenable configuration files, you
can use the ConfigurationBuilder class.
Below there is an example of a configuration built using this class.

```PHP
$c = new ConfigurationBuilder();

$c->bean('beanOne', '\vendorName\library\ClassName')
    ->property('valueProp', 12)
    ->property('refProp', '@beanTwo')
    ->constructorArg('String value')
    ->constructorArg(42);

return $c->build();

```

You can extend the configuration builder class to add your own custom
methods. Doing so, you can create shortcut to some very often used
configuration parts.

```PHP

class SpecificConfigurationBuilder extends ConfigurationBuilder
{
    public function beanOneRef()
    {
        return $this->prop('refProp', '@beanOneId');
    }
}

```

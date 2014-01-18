PHP Inversion of Control
========================

IoC framework for PHP

[![Coverage Status](https://coveralls.io/repos/zeeke/php-spring/badge.png)](https://coveralls.io/r/zeeke/php-spring)
[![Build Status](https://travis-ci.org/zeeke/php-spring.png?branch=master)](https://travis-ci.org/zeeke/php-spring)

# PHP IoC

Using an Inversion Of Control container you can define the business
logic of an application without depending on the specific framework. All
the core logic can be placed in simple PHP classes, where dependency are
injected by the container.

# Configuration

TBD

# Basic Usage

The main library class is AppContext, which offers methods to
initialize and use the IoC context. Using the AppContext::init(...)
function it is possible to specify a configuration for the IoC
container.

```PHP

$config = [ 
    // Bean configuration as PHP Array
    //... 
];

$config = 'path/to/configurationfile.[php|yaml]

AppContext::init($config);

```

# Example

beans:
    beanOne:
        class:  ClassOne
        properties:
            valueProp: 42
    
    beanTwo:
        class:  ClassTwo
        properties:
            refOne: @beanOne
    
    beanThree:
        class:  ClassThree
        properties:
            refOne: @beanOne
            valueProp:  15
    
    beanFour:
        class: ClassFour
        constructor-args:
            - @beanTwo
            - 25
            - "String value"

```PHP

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
    /** @var ClassOne */
    public $refOne;
    
    public $valueProp;
}


class ClassFour
{
    private $calulatedValue;
    private $strProp;
    
    public function __constructor (ClassOne $refOne, $intValue, $strValue)
    {
        $this->calculatedValue = $refOne->valueProp + $intValue;
        $this->strProp = $strValue;
    }
    
    public function getIt ()
    {
        return "$this->strProp : $this->calculatedValue";
    }
}

// In application logic

$beanObj = AppContext::getBean('beanFour);
echo $beanObj->gett(); // String value : 67

```


# Defining Application Modules

Using an IoC container it is possible to build modularized applications.
Each module consists of a set of library classes, a set of dependency
and list of bean that must be implemented by the module users.
In this way, we can define an entire unit of business logic without
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

intreface IUserDAO
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


# ConfigurationBuilder utility

In order to produce readable and maintenable configuration files, you
can use the ConfigurationBuilder class.
Below there is an example of a configuration built using this class.

```PHP
$c = new ConfigurationBuilder();

$c->bean('beanOneId', '\vendorName\library\BeanOneClass')
    ->prop('valueProp', 12)
    ->prop('refProp', '@beanTwoId')
    ->constructorArg('String value')
    ->constructorArg(42)
    ->build();


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
